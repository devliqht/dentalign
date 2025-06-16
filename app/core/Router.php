<?php

class Router {
    private $routes = [];

    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    public function handleRequest($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = trim($url, '/');

        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
            $this->dispatch($action);
        } else {
            http_response_code(404);
            include 'app/views/404.php';
        }
    }

    private function dispatch($action) {
        list($controller, $method) = explode('@', $action);
        
        $controllerFile = "app/controllers/{$controller}.php";
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                
                if (method_exists($controllerInstance, $method)) {
                    $controllerInstance->$method();
                } else {
                    echo "Method {$method} not found in {$controller}";
                }
            } else {
                echo "Controller {$controller} not found";
            }
        } else {
            echo "Controller file {$controllerFile} not found";
        }
    }
}
?>
