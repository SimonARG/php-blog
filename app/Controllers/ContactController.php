<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Models\Contact;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Controllers\Controller;
use App\Interfaces\BlogInfoInterface;
use App\Traits\BlogInfoTrait;

class ContactController extends Controller implements BlogInfoInterface
{
    use BlogInfoTrait;

    protected $contact;

    public function __construct(Security $security, Helpers $helpers, Blog $blog, Contact $contact)
    {
        parent::__construct($security, $helpers, $blog);

        $this->contact = $contact;
    }
}