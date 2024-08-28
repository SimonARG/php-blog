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
    // Users
    '/user/{id}' => UserController::class . '@show',
    '/user/update/{id}' => UserController::class . '@update',
    '/user/delete/{id}' => UserController::class . '@delete',
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
    '/' => PostController::class . '@index',
    '/post/new' => PostController::class . '@create',
    '/post/store' => PostController::class . '@store',
    '/post/{id}' => PostController::class . '@show',
    '/post/edit/{id}' => PostController::class . '@edit',
    '/post/update/{id}' => PostController::class . '@update',
    '/post/delete/{id}' => PostController::class . '@delete',
    '/post/save' => PostController::class . '@save',
    // Search
    '/search' => SearchController::class . '@search',
    '/search/user/posts/{id}' => SearchController::class . '@getUserPosts',
    '/search/user/saved/{id}' => SearchController::class . '@saved',
    // Comments
    '/comments/store' => CommentController::class . '@store',
    '/comments/update/{id}' => CommentController::class . '@update',
    '/comments/delete/{id}' => CommentController::class . '@delete',
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
    '/admin/settings/main-scrollbar' => SettingController::class . '@updateMainScrollbar',
    '/admin/settings/input-scrollbar' => SettingController::class . '@updateInputScrollbar',
    '/admin/settings/popup-bg' => SettingController::class . '@updatePopupBgColor',
    // Moderation
    '/admin/reports' => ReportController::class . '@index',
    '/report' => ReportController::class . '@store',
    '/admin/report/{id}' => ReportController::class . '@show',
    '/admin/report/reset/{id}' => ReportController::class . '@reset',
    '/admin/report/review/{id}' => ReportController::class . '@review',
    // Contact
    '/contacts' => ContactController::class . '@index',
    '/admin/contacts/store' => ContactController::class . '@store',
    '/admin/contacts/update/{id}' => ContactController::class . '@update',
    '/admin/contacts/delete/{id}' => ContactController::class . '@delete',
    // Friend
    '/friends' => FriendController::class . '@index',
    '/admin/friends/store' => FriendController::class . '@store',
    '/admin/friends/update/{id}' => FriendController::class . '@update',
    '/admin/friends/delete/{id}' => FriendController::class . '@delete',
    // Link
    '/links' => LinkController::class . '@index',
    '/admin/links/store' => LinkController::class . '@store',
    '/admin/links/update/{id}' => LinkController::class . '@update',
    '/admin/links/delete/{id}' => LinkController::class . '@delete',
    // About
    '/about' => SettingController::class . '@about',
    '/admin/about/update' => SettingController::class . '@updateAbout'
];