<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

use App\Router;

session_start();

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'guest';
}

// Initialize Router
$router = new Router();

// Load routes
$routes = require_once __DIR__ . '/../config/routes.php';
foreach ($routes as $uri => $controller) {
    $router->add($uri, $controller);
}

// Dispatch the current request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($requestUri);