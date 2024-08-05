<?php

namespace App\Controllers;

use App\Models\Friend;
use App\Controllers\Controller;

class FriendController extends Controller
{
    protected $friend;
    protected $service;

    public function __construct()
    {
        $this->friend = new Friend();

        parent::__construct();
    }

    public function friends(): void
    {
        $friends = $this->friend->getFriends();

        if (!$friends) {
            $this->helpers->view('blog.friends');

            return;
        }

        $this->helpers->view('blog.friends', ['friends' => $friends]);

        return;
    }
}