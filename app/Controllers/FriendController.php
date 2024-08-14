<?php

namespace App\Controllers;

use App\Models\Friend;
use App\Controllers\Controller;

class FriendController extends Controller
{
    protected $friend;

    public function __construct()
    {
        $this->friend = new Friend();

        parent::__construct();
    }

    public function index(): void
    {
        $friends = $this->friend->getAll();

        if (!$friends) {
            $this->helpers->view('blog.friends');

            return;
        }

        $this->helpers->view('blog.friends', ['friends' => $friends]);

        return;
    }

    public function store(array $request): void
    {
        $friend['title'] = $request['name'];
        $friend['url'] = $request['url'];
        $friend['comment'] = $request['comment'];

        $result = $this->friend->store($friend);

        if (!$result) {
            $this->helpers->setPopup('Error al añadir el blog');

            header('Location: /friends');

            return;
        }

        $this->helpers->setPopup('Blog agregado');

        header('Location: /friends');

        return;
    }

    public function update(int $id, array $request): void
    {
        $friend['title'] = $request['name'];
        $friend['url'] = $request['url'];
        $friend['comment'] = $request['comment'];

        $result = $this->friend->update($id, $friend);

        if (!$result) {
            $this->helpers->setPopup('Error al editar el blog');

            header('Location: /friends');

            return;
        }

        $this->helpers->setPopup('Blog editado');

        header('Location: /friends');

        return;
    }

    public function delete(int $id): void
    {
        $result = $this->friend->delete($id);

        if (!$result) {
            $this->helpers->setPopup('Error al eliminar el blog');

            header('Location: /friends');

            return;
        }

        $this->helpers->setPopup('Blog eliminado');

        header('Location: /friends');

        return;
    }
}
