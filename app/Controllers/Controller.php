<?php

namespace App\Controllers;

use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Models\Blog;

abstract class Controller
{
    protected $security;
    protected $helpers;
    protected $blogModel;
    protected $blogConfig;

    public function __construct(Security $security, Helpers $helpers, Blog $blog)
    {
        $this->security = $security;
        $this->helpers = $helpers;
        $this->blogModel = $blog;

        $this->blogConfig = $this->blogModel->getBlogConfig();
    }
}
