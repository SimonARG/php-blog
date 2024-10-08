<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

use App\Models\Blog;
use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use App\Models\Report;
use App\Helpers\Router;
use App\Models\Comment;
use App\Models\Contact;
use App\Helpers\Helpers;
use App\Helpers\Security;
use App\Services\BlogService;
use App\Services\PostService;
use App\Controllers\Controller;
use League\Container\Container;
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
use App\Services\AuthService;
use App\Services\CommentService;
use PharIo\Manifest\Author;

session_start();

// Create the container
$container = new Container();

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

$container->add('App\Controllers\Controller', function() {
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    
    return new Controller($security, $helpers, $blog);
});

$container->add('App\Controllers\AuthController', function() {
    $user = new User();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $authService = new AuthService($user);
    
    return new AuthController($security, $helpers, $blog, $user, $authService);
});

$container->add('App\Controllers\CommentController', function() {
    $post = new Post();
    $comment = new Comment();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $commentService = new CommentService();
    
    return new CommentController($security, $helpers, $blog, $post, $comment, $commentService);
});

$container->add('App\Controllers\ContactController', function() {
    $contact = new Contact();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    
    return new ContactController($security, $helpers, $blog, $contact);
});

$container->add('App\Controllers\FriendController', function() {
    $friend = new Friend();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    
    return new FriendController($security, $helpers, $blog, $friend);
});

$container->add('App\Controllers\LinkController', function() {
    $link = new Link();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    
    return new LinkController($security, $helpers, $blog, $link);
});

$container->add('App\Controllers\PostController', function() {
    $post = new Post();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $service = new PostService($helpers);
    $comment = new Comment();
    
    return new PostController($security, $helpers, $blog, $post, $comment, $service);
});

$container->add('App\Controllers\ReportController', function() {
    $report = new Report();
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $comment = new Comment();
    
    return new ReportController($security, $helpers, $blog, $comment, $report);
});

$container->add('App\Controllers\SearchController', function() {
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $post = new Post();
    $comment = new Comment();
    $user = new User();
    
    return new SearchController($security, $helpers, $blog, $post, $comment, $user);
});

$container->add('App\Controllers\SettingController', function() {
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $blogService = new BlogService($helpers);
    
    return new SettingController($security, $helpers, $blog, $blogService);
});

$container->add('App\Controllers\UserController', function() {
    $security = new Security();
    $blog = new Blog();
    $helpers = new Helpers($blog, $security);
    $post = new Post();
    $comment = new Comment();
    $user = new User();
    $authService = new AuthService($user);
    $authController = new AuthController($security, $helpers, $blog, $user, $authService);
    
    return new UserController($security, $helpers, $blog, $user, $post, $comment, $authController);
});

// Initialize Router
$router = new Router($container);

// Load routes
$routes = require_once __DIR__ . '/../routes/web.php';
foreach ($routes as $uri => $controller) {
    $router->add($uri, $controller);
}

// Dispatch the current request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($requestUri);