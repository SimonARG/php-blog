<?php

namespace App\Controllers;

use App\Models\Friend;
use App\Controllers\Controller;

class FriendController extends Controller
{
    protected $friend;

    public function __construct(Friend $friend)
    {
        parent::__construct();
        
        $this->friend = $friend;
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
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /friends');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /friends');

            return;
        }

        $friend['title'] = $request['name'];
        $friend['url'] = $request['url'];
        $friend['comment'] = $request['comment'];

        $result = $this->friend->store($friend);

        if (!$result) {
            $this->helpers->setPopup('Error al añadir el blog');

            header('Location: /friends');

            return;
        }

        $this->helpers->setPopup($friend['title'] . ' agregado');

        header('Location: /friends');

        return;
    }

    public function update(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /friends');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /friends');

            return;
        }

        $friend['title'] = $request['name'];
        $friend['url'] = $request['url'];
        $friend['comment'] = $request['comment'];

        $result = $this->friend->update($id, $friend);

        if (!$result) {
            $this->helpers->setPopup('Error al editar el blog');

            header('Location: /friends');

            return;
        }

        $this->helpers->setPopup($friend['title'] . ' editado');

        header('Location: /friends');

        return;
    }

    public function delete(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /friends');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /friends');

            return;
        }

        $result = $this->friend->delete($id);

        if (!$result) {
            $this->helpers->setPopup('Error al eliminar el blog');

            header('Location: /friends');

            return;
        }

        $title = $request['title'];

        $this->helpers->setPopup($title . ' eliminado');

        header('Location: /friends');

        return;
    }
}
