<?php

namespace App\Core;

class Router
{

	private array $routes = [];

	/**
	 * This method is used to add a GET route
	 * @param string $route
	 * @param array $callback
	 */
	public function get(string $route, array $callback): void
	{
		$this->routes['GET'][$route] = $callback;
	}

	/**
	 * This method is used to add a POST route
	 * @param string $route
	 * @param array $callback
	 */
	public function post(string $route, array $callback): void
	{
		$this->routes['POST'][$route] = $callback;
	}

	/**
	 * This method is used to dispatch the router
	 */
	public function dispatch()
	{
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$method = $_SERVER['REQUEST_METHOD'];

		// Check if a route exists for this method and path
		foreach ($this->routes[$method] as $route => $callback) {
			// Convert route parameters to a regex pattern, e.g., /articles/delete/{id} -> /articles/delete/(\d+)
			$routePattern = preg_replace('/\{[a-zA-Z_]+\}/', '(\d+)', $route);
			$routePattern = str_replace('/', '\/', $routePattern); // Escape slashes for regex

			// Check if the current path matches the route pattern
			if (preg_match('/^' . $routePattern . '$/', $path, $matches)) {
				array_shift($matches); // Remove the full match, leaving only captured groups

				// Load the controller and method specified in the callback
				[$controllerName, $action] = $callback;
				$controllerName = 'App\\Controllers\\' . $controllerName;

				// Verify the controller class exists
				if (class_exists($controllerName)) {
					$controller = new $controllerName();

					// Verify the method exists in the controller
					if (method_exists($controller, $action)) {
						// Call the controller action, passing dynamic route parameters as arguments
						return $controller->$action(...$matches);
					} else {
						http_response_code(500);
						echo "Error: Method $action not found in controller $controllerName.";
						return;
					}
				} else {
					http_response_code(500);
					echo "Error: Controller $controllerName not found.";
					return;
				}
			}
		}
		// If no route is matched, return a 404 response
		http_response_code(404);
		echo "404 - Not Found";
	}
}
