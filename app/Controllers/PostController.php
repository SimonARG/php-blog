<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Controllers\Controller;
use App\Services\PostService;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class PostController extends Controller
{
    protected $post;
    protected $comment;
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->post = new Post();
        $this->comment = new Comment();
        $this->service = new postService();
    }

    public function index(): void
    {
        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $posts = $this->post->getPosts($currentPage);

        // Get the total number of posts to calculate pagination
        $totalPosts = $this->post->getPostCount();

        $converter = new GithubFlavoredMarkdownConverter([]);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);

            $posts[$key]['body'] = $convertedContent->getContent();
        }

        $posts = $this->helpers->formatDates($posts);

        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        $this->helpers->view('posts.index', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function create(): void
    {
        if (!$this->security->canPost()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $this->helpers->view('posts.create');
    }

    public function store(array $request): void
    {
        if (!$this->security->canPost()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $result = $this->service->sanitizeAndValidate($request);

        $cleanRequest = $result['sanitized_request'];
        $requestErrors = $result['errors'];

        // Initialize variables for thumbnail validation
        $thumb = null;
        $thumbErrors = [];

        // Check if the image is new or old and process accordingly
        if (isset($_FILES["thumb"]) && $_FILES["thumb"]["error"] != UPLOAD_ERR_NO_FILE) {
            $result = $this->service->handleThumb($_FILES["thumb"]);
            $thumb = $result['new_thumb_name'];
            $thumbErrors = $result['errors'];
        } elseif (isset($request['previous_thumb'])) {
            $thumb = basename($request['previous_thumb']);
        }

        // Merge request and file errors
        $errors = array_merge($requestErrors, $thumbErrors);

        // Return errors if any
        if (!empty($errors)) {
            $storageDir = "imgs/thumbs/"; // Set storage directory

            if ($thumb) {
                $request['thumb'] = $storageDir . $thumb;
            } elseif (isset($request['previous_thumb'])) {
                $request['thumb'] = $request['previous_thumb'];
            }

            $this->helpers->view('posts.create', ['request' => $request, 'errors' => $errors]);

            return;
        }

        // Return if no thumb is available
        if (!$thumb) {
            $this->helpers->setPopup('Error: No se ha proporcionado una imagen');

            $this->helpers->view('posts.create', ['request' => $request]);

            return;
        }

        $dbEntry['title'] = $cleanRequest['title'];
        $dbEntry['subtitle'] = $cleanRequest['subtitle'];
        $dbEntry['thumb'] = $thumb . '2.webp';
        $dbEntry['body'] = $cleanRequest['body'];
        $dbEntry['user_id'] = $cleanRequest['user_id'];

        $this->post->create($dbEntry);
        $postId = $this->post->getPostByTitle($request['title'])['id'];


        if (!$postId) {
            $this->helpers->setPopup('Error al procesar la imagen');

            $this->helpers->view('posts.create', ['request' => $request]);

            return;
        }

        $this->helpers->setPopup('Post creado');

        header('Location: /post/' . $postId);
    }

    public function show(int $id): void
    {
        $post = $this->post->getPostById($id);

        $converter = new GithubFlavoredMarkdownConverter();

        $convertedContent = $converter->convert($post['body']);
        $post['body'] = $convertedContent->getContent();

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $comments = $this->comment->getCommentsForPost($id, $currentPage);

        $post = $this->helpers->formatDates($post);

        if ($comments) {
            $comments = $this->helpers->formatDates($comments);

            $this->helpers->view('posts.single', [
                'post' => $post,
                'comments' => $comments,
            ]);
            return;
        }

        $this->helpers->view('posts.single', [
            'post' => $post
        ]);
    }

    public function edit(int $id): void
    {
        $post = $this->post->getPostById($id);

        if (!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios posts');

            header('Location: /');
        }

        $this->helpers->view('posts.edit', [
            'post' => $post
        ]);
    }

    public function update(int $id, array $request): void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $post = $this->post->getPostById($id);

        if (!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios posts');

            header('Location: /');
        }

        // Sanitize
        $title = htmlspecialchars($request['title']);
        $subtitle = htmlspecialchars($request['subtitle']);
        $thumb = $_FILES['thumb']['name'] ? $_FILES['thumb'] : NULL;
        $body = $request['body'];

        // Validate title
        if (strlen($title) < 4) {
            $errors['title_error'] = 'El titulo es demasiado corto';
        } elseif (strlen($title) > 40) {
            $errors['title_error'] = 'El titulo es demasiado largo';
        }

        // Validate subtitle
        if (strlen($subtitle) < 4) {
            $errors['subtitle_error'] = 'El subtitulo es demasiado corto';
        } elseif (strlen($subtitle) > 50) {
            $errors['subtitle_error'] = 'El subtitulo es demasiado largo';
        }

        // Validate body
        if (strlen($body) < 10) {
            $errors['body_error'] = 'El cuerpo es demasiado corto';
        } elseif (strlen($body) > 40000) {
            $errors['body_error'] = 'El cuerpo es demasiado largo';
        }

        // Validate thumb
        if (!$thumb) {
            $dbEntry['thumb'] = $post['thumb'];
        } else {
            $storage_dir = "imgs/thumbs/";
            $file = $storage_dir . basename($thumb["name"]);
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $size = $_FILES['thumb']['size'];
            $imageInfo = getimagesize($_FILES['thumb']['tmp_name']);

            // Define allowed mime types and maximum file size
            $allowedMimeTypes = ['jpeg', 'png', 'jfif', 'avif', 'webp', 'jpg'];
            $maxFileSize = 40 * 1024 * 1024; // 40 MB in bytes

            if ($size > $maxFileSize) {
                $errors['thumb_error'] = 'La imagen pesa mas que 40MB';
            } else if (!in_array($extension, $allowedMimeTypes)) {
                $errors['thumb_error'] = 'Solo se permiten imagenes jpeg, png, jfif, avif, webp y jpg';
            } else if ($imageInfo === false) {
                $errors['thumb_error'] = 'La imagen no es valida';
            }

            $new_thumb_name = random_int(1000000000000000, 9999999999999999);
            $dbEntry['thumb'] = $new_thumb_name . '2.webp';

            move_uploaded_file($_FILES["thumb"]["tmp_name"], $storage_dir . $new_thumb_name . '.' . $extension);

            $sourcePath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/thumbs/' . $new_thumb_name . '.' . $extension;
            $destinationPath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/thumbs/' . $new_thumb_name . '2.webp';

            $imgError = $this->helpers->processImage($sourcePath, $destinationPath);
        }

        // Return errors if any
        if (!empty($errors)) {
            $this->helpers->view('posts.edit', ['request' => $request, 'errors' => $errors]);
        }

        $dbEntry['title'] = $title;
        $dbEntry['subtitle'] = $subtitle;
        $dbEntry['body'] = $body;

        $this->post->update($dbEntry, $id);
        $post = $this->post->getPostById($id);

        $this->helpers->setPopup('Post editado');

        header('Location: /post/' . $post['id']);
    }

    public function delete(array $request): void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $id = $request['post_id'];

        $post = $this->post->getPostById($id);

        if (!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios posts');

            header('Location: /');
        }

        $this->post->softDelete($id);

        $this->helpers->setPopup('Post eliminado');

        header('Location: /');
    }
}
