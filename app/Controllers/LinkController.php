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

    public function links(): void
    {
        $links = $this->link->getLinks();

        if (!$links) {
            $this->helpers->view('blog.links');

            return;
        }

        $this->helpers->view('blog.links', ['links' => $links]);

        return;
    }
}