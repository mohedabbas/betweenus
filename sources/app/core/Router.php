<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $route, array $callback): void
    {
        $this->routes['GET'][$route] = $callback;
    }

    public function post(string $route, array $callback): void
    {
        $this->routes['POST'][$route] = $callback;
    }

    public function dispatch(): void
    {
        $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            [$controllerName, $action] = $this->routes[$method][$path];
            $controllerName = "App\\Controllers\\" . $controllerName;

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $action)) {
                    call_user_func([$controller, $action]);
                    return;
                }
                http_response_code(500);
                echo "Méthode $action non trouvée dans $controllerName.";
                return;
            }
            http_response_code(500);
            echo "Contrôleur $controllerName introuvable.";
            return;
        }

        // 404
        http_response_code(404);
        echo "404 - Not Found";
    }
}
