<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Link;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;
use App\Interfaces\BlogInfoInterface;
use App\Traits\BlogInfoTrait;

class LinkController extends Controller implements BlogInfoInterface
{
    use BlogInfoTrait;

    protected $link;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Link $link)
    {
        parent::__construct($security, $helpers, $blog);

        $this->link = $link;
    }
}