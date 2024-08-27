<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Comment;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;
use App\Services\CommentService;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class CommentController extends Controller
{
    protected $comment;
    protected $post;
    protected $service;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Post $post, Comment $comment, CommentService $commentService)
    {
        parent::__construct($security, $helpers, $blog);

        $this->comment = $comment;
        $this->post = $post;
        $this->service = $commentService;
    }

    public function store(array $request): void
    {
        $postId = $request['post_id'];

        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $postId);

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes publicar comentarios');

            header('Location: /post/' . $postId);

            return;
        }

        $comment = $request['body'];
        $userId = $request['user_id'];

        $errors = [];

        $data = [
            'body' => $comment,
            'user_id' => $userId,
            'post_id' => $postId
        ];

        // Sanitize and validate
        $cleanData = $this->service->sanitize($data);

        $comment = $cleanData['body'];
        $userId = $cleanData['user_id'];
        $postId = $cleanData['post_id'];

        if (!is_string($userId) || !is_string($postId)) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $postId . '#comment-1');

            return;
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

        header('Location: /post/' . $postId . '#comment-1');

        return;
    }

    public function update(int $id, array $request): void
    {
        $postId = $request['post_id'];

        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes editar comentarios');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        $oldComment = $this->comment->getCommentById($id);

        if(!$this->security->verifyIdentity($oldComment['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios comentarios');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        $comment = $request['body'];
        $userId = $request['user_id'];

        $errors = [];

        $data = [
            'body' => $comment,
            'user_id' => $userId,
            'post_id' => $postId
        ];

        // Sanitize and validate
        $cleanData = $this->service->sanitize($data);

        $comment = $cleanData['body'];
        $userId = $cleanData['user_id'];
        $postId = $cleanData['post_id'];

        if (!is_string($userId) || !is_string($postId)) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $postId . '#comment-1');

            return;
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

        if ($comment == $oldComment['body']) {
            $this->helpers->setPopup('El nuevo comentario es identico al original');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        $this->comment->update($comment, $id);

        $this->helpers->setPopup('Comentario editado');

        header('Location: /post/' . $postId . '#comment-1');

        return;
    }

    public function delete(int $id, array $request): void
    {
        $postId = $request['post_id'];

        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        if (!$this->security->canComment()) {
            $this->helpers->setPopup('No puedes eliminar comentarios');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        $comment = $this->comment->getCommentById($id);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios comentarios');

            header('Location: /post/' . $postId . '#comment-1');

            return;
        }

        $this->comment->softDelete($id);

        $this->helpers->setPopup('Comentario eliminado');

        header('Location: /post/' . $postId . '#comment-1');

        return;
    }
}
