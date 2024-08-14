<?php

namespace App\Controllers;

use App\Models\Link;
use App\Controllers\Controller;

class LinkController extends Controller
{
    protected $link;

    public function __construct()
    {
        $this->link = new Link();

        parent::__construct();
    }

    public function index(): void
    {
        $links = $this->link->getAll();

        if (!$links) {
            $this->helpers->view('blog.links');

            return;
        }

        $this->helpers->view('blog.links', ['links' => $links]);

        return;
    }

    public function store(array $request): void
    {
        $link['title'] = $request['name'];
        $link['url'] = $request['url'];

        $result = $this->link->store($link);

        if (!$result) {
            $this->helpers->setPopup('Error al añadir el link');

            header('Location: /links');

            return;
        }

        $this->helpers->setPopup('Link agregado');

        header('Location: /links');

        return;
    }

    public function update(int $id, array $request): void
    {
        $link['title'] = $request['name'];
        $link['url'] = $request['url'];

        $result = $this->link->update($id, $link);

        if (!$result) {
            $this->helpers->setPopup('Error al editar el link');

            header('Location: /links');

            return;
        }

        $this->helpers->setPopup('Link editado');

        header('Location: /links');

        return;
    }

    public function delete(int $id): void
    {
        $result = $this->link->delete($id);

        if (!$result) {
            $this->helpers->setPopup('Error al eliminar el link');

            header('Location: /links');

            return;
        }

        $this->helpers->setPopup('Link eliminado');

        header('Location: /links');

        return;
    }
}
