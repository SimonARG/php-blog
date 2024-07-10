<?php

namespace App\Controllers;

use App\Helpers\Helpers;
use App\Helpers\Security;

class Controller
{
    protected $security;
    protected $helpers;

    public function __construct()
    {
        $this->security = new Security();
        $this->helpers = new Helpers();
    }
}