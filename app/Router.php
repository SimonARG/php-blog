<?php

namespace App;

class Router
{
    protected $routes = [];

    public function add($uri, $controller)
    {
        $this->routes[$uri] = $controller;
    }

    public function dispatch($requestUri)
    {
        // Check if the request URI matches a defined route exactly
        if (array_key_exists($requestUri, $this->routes)) {
            $this->callControllerAction($this->routes[$requestUri], [$_POST]);
        } else {
            // Check if the request URI matches a route with parameters
            foreach ($this->routes as $route => $controller) {
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
                $pattern = str_replace('/', '\/', $pattern);
                if (preg_match('/^' . $pattern . '$/', $requestUri, $matches)) {
                    array_shift($matches); // Remove the full match
                    $params = array_merge($matches, [$_POST]); // Add POST data as the last parameter
                    $this->callControllerAction($controller, $params);
                    return;
                }
            }
            // If no route matches, return a 404 error
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
        }
    }

    protected function callControllerAction($controllerAction, $params = [])
    {
        $controllerAction = explode('@', $controllerAction);
        $controllerName = $controllerAction[0];
        $actionName = $controllerAction[1];

        $controller = new $controllerName();
        // Call the controller action with parameters if provided
        if (!empty($params)) {
            call_user_func_array([$controller, $actionName], $params);
        } else {
            $controller->$actionName();
        }
    }
}
