<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Helpers\Security;
use App\Helpers\Helpers;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class CommentController
{
    protected $commentModel;
    protected $postModel;
    protected $security;
    protected $helpers;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->postModel = new Post();
        $this->security = new Security();
        $this->helpers = new Helpers();
    }

    public function store($request)
    {
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
            return view('posts.single', [
                'request' => $request,
                'errors' => $errors
            ]);
        }

        $this->commentModel->storeComment($request);

        $this->helpers->setPopup('Comentario creado');

        return header('Location: /post/' . $request['post_id'] . '#comment-1');
    }

    public function update($id, $request)
    {
        $comment = $this->commentModel->getCommentById($id);
        $post = $this->postModel->getPostById($request['post_id']);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes editar tus propios comentarios');

            return header('Location: /post/' . $request['post_id'] . '#comment-1');
        }

        // Sanitize
        $body = htmlspecialchars($request['body']);

        // Validate
        if (strlen($request['body']) < 1) {
            $this->helpers->setPopup('Comentario demasiado corto');

            return header('Location: /post/' . $request['post_id'] . '#comment-1');
        } elseif (strlen($request['body']) > 1600) {
            $this->helpers->setPopup('Comentario demasiado largo');

            return header('Location: /post/' . $request['post_id'] . '#comment-1');
        } elseif ($request['body'] == $comment['body']) {
            $this->helpers->setPopup('El nuevo comentario es identico al original');

            return header('Location: /post/' . $request['post_id'] . '#comment-1');
        }

        $dbEntry['body'] = $body;

        $this->commentModel->update($dbEntry, $id);
        
        $$this->helpers->setPopup('Comentario editado');

        return header('Location: /post/' . $request['post_id'] . '#comment-1');
    }

    public function delete($request)
    {
        $id = $request['comment_id'];

        $comment = $this->commentModel->getCommentById($id);

        if(!$this->security->verifyIdentity($comment['user_id'])) {
            $this->helpers->setPopup('Solo puedes eliminar tus propios comentarios');

            return header('Location: /post/' . $request['post_id'] . '#comment-1');
        }

        $this->commentModel->softDelete($id);

        $this->helpers->setPopup('Comentario eliminado');

        return header('Location: /post/' . $request['post_id'] . '#comment-1');
    }
}