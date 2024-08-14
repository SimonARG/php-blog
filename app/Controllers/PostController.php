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
        $this->service = new PostService();
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

        return;
    }

    public function create(): void
    {
        $this->helpers->view('posts.create');

        return;
    }

    public function store(array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            $this->helpers->view('posts.create');

            return;
        }

        if (!$this->security->canPost()) {
            $this->helpers->setPopup('Operacion no autorizada');

            $this->helpers->view('posts.create');

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

        return;
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

        return;
    }

    public function edit(int $id): void
    {
        $post = $this->post->getPostById($id);

        $this->helpers->view('posts.edit', [
            'post' => $post
        ]);

        return;
    }

    public function update(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /');

            return;
        }

        if (!$this->security->verifyIdentity($id)) {
            $this->helpers->setPopup('Solo puedes editar tus propios posts');

            header('Location: /');

            return;
        }

        if (!$this->security->canPost()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $post = $this->post->getPostById($id);
        $thumb = $_FILES['thumb']['name'] ? $_FILES['thumb'] : null;

        $result = $this->service->sanitizeAndValidate($request);

        $cleanRequest = $result['sanitized_request'];
        $requestErrors = $result['errors'];

        // Validate thumb
        $thumbErrors = [];
        if (!$thumb) {
            $dbEntry['thumb'] = $post['thumb'];
        } else {
            // Check if the image is new or old and process accordingly
            if (isset($_FILES["thumb"]) && $_FILES["thumb"]["error"] != UPLOAD_ERR_NO_FILE) {
                $result = $this->service->handleThumb($_FILES["thumb"]);
                $thumb = $result['new_thumb_name'];
                $thumbErrors = $result['errors'];
            } elseif (isset($request['previous_thumb'])) {
                $thumb = basename($request['previous_thumb']);
            }
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
            } else {
                $request['thumb'] = $post['thumb'];
            }

            $this->helpers->view('posts.edit', ['post' => $post, 'request' => $request, 'errors' => $errors]);

            return;
        }

        $dbEntry['title'] = $cleanRequest['title'];
        $dbEntry['subtitle'] = $cleanRequest['subtitle'];
        $dbEntry['thumb'] = $thumb . '2.webp';
        $dbEntry['body'] = $cleanRequest['body'];
        $dbEntry['user_id'] = $cleanRequest['user_id'];

        $this->post->update($dbEntry, $id);

        $post = $this->post->getPostById($id);

        $this->helpers->setPopup('Post editado');

        header('Location: /post/' . $post['id']);

        return;
    }

    public function delete(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /');

            return;
        }

        if (!$this->security->verifyIdentity($id)) {
            $this->helpers->setPopup('Solo puedes editar tus propios posts');

            header('Location: /');

            return;
        }

        if (!$this->security->canPost()) {
            $this->helpers->setPopup('Operacion no autorizada');

            header('Location: /');

            return;
        }

        $post = $this->post->getPostById($id);

        if (!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios posts');

            header('Location: /');
        }

        $this->post->softDelete($id);

        $this->helpers->setPopup('Post eliminado');

        header('Location: /');

        return;
    }
}
