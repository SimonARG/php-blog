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
        $this->security->verifyCsrf($request['csrf'] ?? '');
        // Sanitize
        htmlspecialchars($request['body']);

        // Validate
        if (strlen($request['body']) < 1) {
            $errors['body_error'] = 'El comentario es demasiado corto';
        } elseif (strlen($request['body']) > 1600) {
            $errors['body_error'] = 'El comentario es demasiado largo';
        }

        // Return errors if any
        if (!empty($errors)) {
            view('posts.single', [
                'request' => $request,
                'errors' => $errors
            ]);
        }

        $this->comment->storeComment($request);

        $this->helpers->setPopup('Comentario creado');

        header('Location: /post/' . $request['post_id'] . '#comment-1');
    }

    public function update(int $id, array $request): void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $comment = $this->comment->getCommentById($id);
        $post = $this->post->getPostById($request['post_id']);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');
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
    }

    public function delete(int $id, array $request): void
    {
        $this->security->verifyCsrf($request['csrf'] ?? '');

        $id = $request['comment_id'];

        $comment = $this->comment->getCommentById($id);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios comentarios');

            header('Location: /post/' . $request['post_id'] . '#comment-1');
        }

        $this->comment->softDelete($id);

        $this->helpers->setPopup('Comentario eliminado');

        header('Location: /post/' . $request['post_id'] . '#comment-1');
    }
}
