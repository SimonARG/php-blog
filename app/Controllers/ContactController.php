<?php

namespace App\Controllers;

use App\Models\Contact;
use App\Controllers\Controller;

class ContactController extends Controller
{
    protected $contact;

    public function __construct()
    {
        $this->contact = new Contact();

        parent::__construct();
    }

    public function index(): void
    {
        $contacts = $this->contact->getContacts();

        if (!$contacts) {
            $this->helpers->view('blog.contact');

            return;
        }

        $this->helpers->view('blog.contact', ['contacts' => $contacts]);

        return;
    }

    public function store(array $request): void
    {
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

    public function update(array $request): void
    {
        $contact['title'] = $request['name'];
        $contact['url'] = $request['url'];
        $id = $request['id'];

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

    public function delete(array $request): void
    {
        $id = $request['id'];

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