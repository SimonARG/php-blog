<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class CommentController
{
    protected $baseUrl;
    protected $commentModel;
    protected $postModel;

    public function __construct()
    {
        $this->baseUrl = $GLOBALS['config']['base_url'];
        $this->commentModel = new Comment();
        $this->postModel = new Post();
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

        return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Comentario Creado#comment-1');
    }

    public function update($id, $request)
    {
        $comment = $this->commentModel->getCommentById($id);
        $post = $this->postModel->getPostById($request['post_id']);

        if(!($comment['user_id'] === $_SESSION['user_id'])) {
            return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Solo puedes editar tus propios comentarios#comment-1');
        }

        // Sanitize
        $body = htmlspecialchars($request['body']);

        // Validate
        if (strlen($request['body']) < 1) {
            return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Comentario demasiado corto#comment-1');
        } elseif (strlen($request['body']) > 1600) {
            return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Comentario demasiado largo#comment-1');
        } elseif ($request['body'] == $comment['body']) {
            return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=El nuevo comentario es identico al original#comment-1');
        }

        $dbEntry['body'] = $body;

        $this->commentModel->update($dbEntry, $id);

        return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Comentario Editado#comment-1');
    }

    public function delete($request)
    {
        $id = $request['comment_id'];

        $comment = $this->commentModel->getCommentById($id);

        if(!($comment['user_id'] === $_SESSION['user_id'])) {
            return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Solo puedes eliminar tus propios comentarios#comment-1');
        }

        $this->commentModel->softDelete($id);

        return header('Location:' . $this->baseUrl . 'post/' . $request['post_id'] . '?popup_content=Comentario Eliminado#comment-1');
    }
}