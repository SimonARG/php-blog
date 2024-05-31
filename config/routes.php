<?php

use App\Controllers\PostController;
use App\Controllers\UserController;

return [
    '/' => PostController::class . '@index',
    '/users' => UserController::class . '@index',
    '/post/{id}' => PostController::class . '@show',
];