<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Friend;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;
use App\Interfaces\BlogInfoInterface;
use App\Traits\BlogInfoTrait;

class FriendController extends Controller implements BlogInfoInterface
{
    use BlogInfoTrait;

    protected $friend;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Friend $friend)
    {
        parent::__construct($security, $helpers, $blog);

        $this->friend = $friend;
    }
}