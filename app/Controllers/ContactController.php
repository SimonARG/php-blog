<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Contact;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;

class ContactController extends Controller
{
    protected $contact;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Contact $contact)
    {
        parent::__construct($security, $helpers, $blog);

        $this->contact = $contact;
    }

    public function index(): void
    {
        $contacts = $this->contact->getAll();

        if (!$contacts) {
            $this->helpers->view('blog.contact');

            return;
        }

        $this->helpers->view('blog.contact', ['contacts' => $contacts]);

        return;
    }

    public function store(array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /contact');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /contact');

            return;
        }

        $contact['title'] = $request['name'];
        $contact['url'] = $request['url'];

        $result = $this->contact->store($contact);

        if (!$result) {
            $this->helpers->setPopup('Error al añadir medio de contacto');

            header('Location: /contact');

            return;
        }

        $this->helpers->setPopup('Medio de contacto agregado');

        header('Location: /contact');

        return;
    }

    public function update(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /contact');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /contact');

            return;
        }

        $contact['title'] = $request['name'];
        $contact['url'] = $request['url'];

        $result = $this->contact->update($id, $contact);

        if (!$result) {
            $this->helpers->setPopup('Error al editar el medio de contacto');

            header('Location: /contact');

            return;
        }

        $this->helpers->setPopup('Medio de contacto editado');

        header('Location: /contact');

        return;
    }

    public function delete(int $id, array $request): void
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');

            header('Location: /contact');

            return;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');

            header('Location: /contact');

            return;
        }

        $result = $this->contact->delete($id);

        if (!$result) {
            $this->helpers->setPopup('Error al eliminar el medio de contacto');

            header('Location: /contact');

            return;
        }

        $this->helpers->setPopup('Medio de contacto eliminado');

        header('Location: /contact');

        return;
    }
}
