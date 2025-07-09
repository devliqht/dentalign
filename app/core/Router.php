<?php

require_once "app/helpers/LayoutHelper.php";

class Router
{
    private $routes = [];

    public function get($route, $action)
    {
        $this->routes["GET"][$route] = $action;
        // GET Routes
        // 0: /login => $
    }

    public function post($route, $action)
    {
        $this->routes["POST"][$route] = $action;
    }

    public function put($route, $action)
    {
        $this->routes["PUT"][$route] = $action;
    }

    public function delete($route, $action)
    {
        $this->routes["DELETE"][$route] = $action;
    }

    /**
     * Group routes by HTTP method and controller. useful If you have any routes that use the same controller.
     *
     * @param string|array $methods HTTP method(s) - 'GET', 'POST' or ['GET', 'POST']
     * @param string $controller Controller class name
     * @param array $routes Associative array of route => method pairs
     *
     * Example usage (from web.php):
     * $router->group('GET', 'AuthController', [
     *     '' => 'showLogin',
     *     'login' => 'showLogin',
     *     'signup' => 'showSignup',
     *     'home' => 'home'
     * ]);
     *
     * Or with multiple HTTP methods:
     * $router->group(['GET', 'POST'], 'AuthController', [
     *     'profile' => 'profile'
     * ]);
     */
    public function group($methods, $controller, $routes)
    {
        if (!is_array($methods)) {
            $methods = [$methods];
        }

        $controller = str_replace("::class", "", $controller);

        foreach ($methods as $method) {
            $method = strtoupper($method);

            foreach ($routes as $route => $action) {
                $fullAction = $controller . "@" . $action;
                $this->routes[$method][$route] = $fullAction;
            }
        }
    }

    public function handleRequest($url)
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $url = trim($url, "/");

        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
            $this->dispatch($action);
            return;
        }

        // try dynamic route matching
        foreach ($this->routes[$method] as $route => $action) {
            $params = $this->matchDynamicRoute($route, $url);
            if ($params !== false) {
                $this->dispatch($action, $params);
                return;
            }
        }

        http_response_code(404);
        LayoutHelper::render(
            "404",
            [],
            ["hideHeader" => true, "hideFooter" => true, "title" => "404"]
        );
    }

    private function dispatch($action, $params = [])
    {
        list($controller, $method) = explode("@", $action);

        $controllerFile = "app/controllers/{$controller}.php";

        if (file_exists($controllerFile)) {
            require_once "app/core/Controller.php";
            require_once $controllerFile;

            if (class_exists($controller)) {
                $controllerInstance = new $controller();

                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array(
                        [$controllerInstance, $method],
                        $params
                    );
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

    private function matchDynamicRoute($route, $url)
    {
        $pattern = preg_replace("/\{[^}]+\}/", "([^/]+)", $route);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $url, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }
}
