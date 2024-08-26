<?php

namespace App\Helpers;

use League\Container\Container;

class Router
{
    protected $routes = [];
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add($uri, $controller)
    {
        $this->routes[$uri] = $controller;
    }

    public function dispatch($requestUri)
    {
        if (array_key_exists($requestUri, $this->routes)) {
            $this->callControllerAction($this->routes[$requestUri], [$_POST]);
        } else {
            foreach ($this->routes as $route => $controller) {
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
                $pattern = str_replace('/', '\/', $pattern);
                if (preg_match('/^' . $pattern . '$/', $requestUri, $matches)) {
                    array_shift($matches);
                    $params = array_merge($matches, [$_POST]);
                    $this->callControllerAction($controller, $params);
                    return;
                }
            }
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
        }
    }

    protected function callControllerAction($controllerAction, $params = [])
    {
        $controllerAction = explode('@', $controllerAction);
        $controllerName = $controllerAction[0];
        $actionName = $controllerAction[1];

        // Use the container to resolve the controller
        $controller = $this->container->get($controllerName);

        if (!empty($params)) {
            call_user_func_array([$controller, $actionName], $params);
        } else {
            $controller->$actionName();
        }
    }
}
