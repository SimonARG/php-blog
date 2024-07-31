<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    protected $blog;

    public function __construct()
    {
        parent::__construct();

        $this->blog = new Blog();
    }

    public function contact(): void
    {
        $contacts = $this->blog->getContacts();

        if (!$contacts) {
            $this->helpers->view('blog.contact');

            return;
        }

        $this->helpers->view('blog.contact', ['contacts' => $contacts]);

        return;
    }

    public function friends(): void
    {
        $friends = $this->blog->getFriends();

        if (!$friends) {
            $this->helpers->view('blog.friends');

            return;
        }

        $this->helpers->view('blog.friends', ['friends' => $friends]);

        return;
    }

    public function links(): void
    {
        $links = $this->blog->getLinks();

        if (!$links) {
            $this->helpers->view('blog.links');

            return;
        }

        $this->helpers->view('blog.links', ['links' => $links]);

        return;
    }

    public function about(): void
    {
        $this->helpers->view('blog.about');

        return;
    }
}