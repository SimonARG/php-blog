<?php

use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\ReportController;
use App\Controllers\SearchController;
use App\Controllers\CommentController;

return [
    '/' => PostController::class . '@index',
    // Users
    '/user/{id}' => UserController::class . '@show',
    '/user/update/{id}' => UserController::class . '@update',
    '/user/saved/save' => UserController::class . '@save',
    '/user/saved/delete' => UserController::class . '@deleteSaved',
    '/user/role/{id}' => UserController::class . '@changeRole',
    // Auth
    '/register' => UserController::class . '@create',
    '/users/store' => UserController::class . '@store',
    '/login' => AuthController::class . '@login',
    '/auth' => AuthController::class . '@authenticate',
    '/logout' => AuthController::class . '@logout',
    // Posts
    '/post/new' => PostController::class . '@create',
    '/post/store' => PostController::class . '@store',
    '/post/{id}' => PostController::class . '@show',
    '/post/edit/{id}' => PostController::class . '@edit',
    '/post/update/{id}' => PostController::class . '@update',
    '/post/delete' => PostController::class . '@delete',
    '/post/save' => PostController::class . '@save',
    // Search
    '/search' => SearchController::class . '@search',
    '/search/user/posts/{id}' => SearchController::class . '@getUserPosts',
    '/search/user/saved/{id}' => SearchController::class . '@saved',
    // Comments
    '/comments/store' => CommentController::class . '@store',
    '/comments/update/{id}' => CommentController::class . '@update',
    '/comments/delete' => CommentController::class . '@delete',
    // Reports
    '/admin/reports' => ReportController::class . '@index',
    '/report' => ReportController::class . '@create',
    // Admin
    '/admin/settings' => BlogController::class . '@settings',
    // Misc
    '/contact' => BlogController::class . '@contact',
    '/friends' => BlogController::class . '@friends',
    '/links' => BlogController::class . '@links',
    '/about' => BlogController::class . '@about'
];