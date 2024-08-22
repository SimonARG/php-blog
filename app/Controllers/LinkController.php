<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Link;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;

class LinkController extends Controller
{
    protected $link;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Link $link)
    {
        parent::__construct($security, $helpers, $blog);

        $this->link = $link;
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
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /links');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /links');

            return;
        }

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
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /links');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /links');

            return;
        }

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

    public function delete(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /links');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /links');

            return;
        }

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
