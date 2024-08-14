<?php

namespace App\Controllers;

use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Models\Blog;

class Controller
{
    protected $security;
    protected $helpers;
    protected $blogModel;
    protected $blogConfig;

    public function __construct()
    {
        $this->security = new Security();
        $this->helpers = new Helpers();
        $this->blogModel = new Blog();

        $this->blogConfig = $this->blogModel->getBlogConfig();
    }
}
