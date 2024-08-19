<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Controllers\Controller;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class CommentController extends Controller
{
    protected $comment;
    protected $post;

    public function __construct()
    {
        parent::__construct();

        $this->comment = new Comment();
        $this->post = new Post();
    }

    public function store(array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $request['post_id']);

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes publicar comentarios');

            header('Location: /post/' . $request['post_id']);

            return;
        }

        function sanitizeComment($comment)
        {
            // Remove HTML tags
            $comment = strip_tags($comment);
    
            // Convert special characters to HTML entities
            $comment = htmlspecialchars($comment, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
            return $comment;
        }

        $comment = $request['body'];
        $userId = $request['user_id'];
        $postId = $request['post_id'];

        $errors = [];

        // Sanitize
        $comment = sanitizeComment($comment);
        $userId = sanitizeComment($userId);
        $postId = sanitizeComment($postId);

        // Validate
        if (!is_string($comment)) {
            $errors['body_error'] = 'Error de seguridad';
        } elseif (strlen($comment) < 1) {
            $errors['body_error'] = 'El comentario es demasiado corto';
        } elseif (strlen($comment) > 1600) {
            $errors['body_error'] = 'El comentario es demasiado largo';
        }

        if (!is_string($userId) || !is_string($postId)) {
            $errors['body_error'] = 'Error de seguridad';
        }

        // Return errors if any
        if (!empty($errors)) {
            $post = $this->post->getPostById($postId);

            $converter = new GithubFlavoredMarkdownConverter();
    
            $convertedContent = $converter->convert($post['body']);
            $post['body'] = $convertedContent->getContent();
    
            // Get the current page from the query parameters, default to 1 if not set
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
            $comments = $this->comment->getCommentsForPost($postId, $currentPage);
    
            $post = $this->helpers->formatDates($post);
    
            if ($comments) {
                $comments = $this->helpers->formatDates($comments);
    
                $this->helpers->view('posts.single', [
                    'post' => $post,
                    'comments' => $comments,
                    'errors' => $errors
                ]);
                return;
            }
    
            $this->helpers->view('posts.single', [
                'post' => $post,
                'errors' => $errors
            ]);

            return;
        }

        $this->comment->storeComment($request);

        $this->helpers->setPopup('Comentario creado');

        header('Location: /post/' . $request['post_id'] . '#comment-1');

        return;
    }

    public function update(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes editar comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        $comment = $this->comment->getCommentById($id);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        // Sanitize
        $body = htmlspecialchars($request['body']);

        // Validate
        if (strlen($request['body']) < 1) {
            $this->helpers->setPopup('Comentario demasiado corto');

            header('Location: /post/' . $request['post_id'] . '#comment-1');
        } elseif (strlen($request['body']) > 1600) {
            $this->helpers->setPopup('Comentario demasiado largo');

            header('Location: /post/' . $request['post_id'] . '#comment-1');
        } elseif ($request['body'] == $comment['body']) {
            $this->helpers->setPopup('El nuevo comentario es identico al original');

            header('Location: /post/' . $request['post_id'] . '#comment-1');
        }

        $dbEntry['body'] = $body;

        $this->comment->update($dbEntry, $id);

        $$this->helpers->setPopup('Comentario editado');

        header('Location: /post/' . $request['post_id'] . '#comment-1');

        return;
    }

    public function delete(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes eliminar comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        $comment = $this->comment->getCommentById($id);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');

            return;
        }

        $this->comment->softDelete($id);

        $this->helpers->setPopup('Comentario eliminado');

        header('Location: /post/' . $request['post_id'] . '#comment-1');

        return;
    }
}
