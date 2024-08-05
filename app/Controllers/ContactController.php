<?php

namespace App\Controllers;

use App\Models\Contact;
use App\Controllers\Controller;

class ContactController extends Controller
{
    protected $contact;
    protected $service;

    public function __construct()
    {
        $this->contact = new Contact();

        parent::__construct();
    }

    public function contact(): void
    {
        $contacts = $this->contact->getContacts();

        if (!$contacts) {
            $this->helpers->view('blog.contact');

            return;
        }

        $this->helpers->view('blog.contact', ['contacts' => $contacts]);

        return;
    }
}