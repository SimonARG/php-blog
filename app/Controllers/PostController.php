<?php

namespace App\Controllers;

use DateTime;
use App\Models\Post;
use App\Models\Comment;
use App\Controllers\Controller;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class PostController extends Controller
{
    protected $post;
    protected $comment;

    public function __construct()
    {
        parent::__construct();
        $this->post = new Post();
        $this->comment = new Comment();
    }

    public function index() : void
    {
        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $posts = $this->post->getPosts($currentPage);

        // Get the total number of posts to calculate pagination
        $totalPosts = $this->post->getPostCount();

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();

            $postDate = new DateTime($post['created_at']);
            $postStrdate = $postDate->format('Y/m/d H:i');
            $posts[$key]['created_at'] = $postStrdate;
    
            if (isset($post['updated_at'])) {
                $postUpDate = new DateTime($post['updated_at']);
                $postUpStrdate = $postUpDate->format('Y/m/d H:i');
                $posts[$key]['updated_at'] = $postUpStrdate;
            }
        }
        
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

    public function create() : void
    {   
        if (!$_SESSION) {
            header('Location: /');
        }
        $this->helpers->view('posts.create');
    }

    public function store(array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        // Sanitize
        $title = htmlspecialchars($request['title']);
        $subtitle = htmlspecialchars($request['subtitle']);
        $thumb = $_FILES["thumb"];
        $body = $request['body'];
        $user_id = $request['user_id'];

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
        $storage_dir = "imgs/thumbs/";
        $file = $storage_dir . basename($thumb["name"]);
        $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
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

        // Return errors if any
        if (!empty($errors)) {
            $this->helpers->view('posts.create', ['request' => $request, 'errors' => $errors]);
        }

        $new_thumb_name = random_int(1000000000000000, 9999999999999999);

        $dbEntry['title'] = $title;
        $dbEntry['subtitle'] = $subtitle;
        $dbEntry['thumb'] = $new_thumb_name . '2.webp';
        $dbEntry['body'] = $body;
        $dbEntry['user_id'] = $user_id;

        move_uploaded_file($_FILES["thumb"]["tmp_name"], $storage_dir . $new_thumb_name . '.' . $extension);

        $sourcePath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/thumbs/' . $new_thumb_name . '.' . $extension;
        $destinationPath = 'D:/Programs/Apache/Apache24/htdocs/blog/public/imgs/thumbs/' . $new_thumb_name . '2.webp';

        $imgError = $this->helpers->processImage($sourcePath, $destinationPath);

        $this->post->create($dbEntry);
        $post = $this->post->getPostByTitle($request['title']);

        $this->helpers->setPopup('Post creado');

        header('Location: /post/' . $post['id']);
    }

    public function show(int $id) : void
    {
        $post = $this->post->getPostById($id);

        $converter = new GithubFlavoredMarkdownConverter();

        $convertedContent = $converter->convert($post['body']);
        $post['body'] = $convertedContent->getContent();

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $comments = $this->comment->getCommentsForPost($id, $currentPage);

        $postDate = new DateTime($post['created_at']);
        $postStrdate = $postDate->format('Y/m/d H:i');
        $post['created_at'] = $postStrdate;

        if (isset($post['updated_at'])) {
            $postUpDate = new DateTime($post['updated_at']);
            $postUpStrdate = $postUpDate->format('Y/m/d H:i');
            $post['updated_at'] = $postUpStrdate;
        }

        if ($comments) {
            foreach ($comments as $key => $comment) {
                // $comment->comment = Markdown::convert($comment->comment)->getContent();
                
                $comDate = new DateTime($comment['created_at']);
                $comStrdate = $comDate->format('Y/m/d H:i');
                $comments[$key]['created_at'] = $comStrdate;

                if (isset($comment['updated_at'])) {
                    $comUpDate = new DateTime($comment['updated_at']);
                    $comUpStrdate = $comUpDate->format('Y/m/d H:i');
                    $comments[$key]['updated_at'] = $comUpStrdate;
                }
            }
        }

        $this->helpers->view('posts.single', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    public function edit(int $id) : void
    {
        $post = $this->post->getPostById($id);

        if(!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios posts');

            header('Location: /');
        }

        $this->helpers->view('posts.edit', [
            'post' => $post
        ]);
    }
    
    public function update(int $id, array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $post = $this->post->getPostById($id);

        if(!$this->security->verifyIdentity($post['user_id'])) {
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
            $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
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

    public function delete(array $request) : void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');
        
        $id = $request['post_id'];

        $post = $this->post->getPostById($id);

        if(!$this->security->verifyIdentity($post['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios posts');

            header('Location: /');
        }

        $this->post->softDelete($id);

        $this->helpers->setPopup('Post eliminado');

        header('Location: /');
    }
}