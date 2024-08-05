<?php

use App\Controllers\AuthController;
use App\Controllers\LinkController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\FriendController;
use App\Controllers\ReportController;
use App\Controllers\SearchController;
use App\Controllers\CommentController;
use App\Controllers\ContactController;
use App\Controllers\SettingController;

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
    // Contacts
    '/contact' => ContactController::class . '@contact',
    // Friends
    '/friends' => FriendController::class . '@friends',
    // Links
    '/links' => LinkController::class . '@links',
    // About
    '/about' => SettingController::class . '@about',
    // --------------------- Admin --------------------- //
    // Settings
    '/admin/settings' => SettingController::class . '@settings',
    '/admin/settings/title' => SettingController::class . '@updateTitle',
    '/admin/settings/bg-color' => SettingController::class . '@updateBgColor',
    '/admin/settings/bg-image' => SettingController::class . '@updateBgImage',
    '/admin/settings/panel-bg' => SettingController::class . '@updatePanelBgColor',
    '/admin/settings/panel-h' => SettingController::class . '@updatePanelHoverColor',
    '/admin/settings/panel-a' => SettingController::class . '@updatePanelActiveColor',
    '/admin/settings/text' => SettingController::class . '@updateTextColor',
    '/admin/settings/text-dim' => SettingController::class . '@updateTextDim',
    '/admin/settings/icon' => SettingController::class . '@updateIcon',
    // Moderation
    '/admin/reports' => ReportController::class . '@index',
    '/report' => ReportController::class . '@create',
];