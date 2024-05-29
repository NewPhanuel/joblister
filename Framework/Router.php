<?php
declare(strict_types=1);

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected array $routes = [];


    /**
     * Adds a route to the array from the http verbs methods
     *
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function registerRoute(string $method, string $uri, string $action): void
    {
        list($controller, $controllerMethod) = explode('@', $action);
        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controller" => $controller,
            "controllerMethod" => $controllerMethod,
        ];
    }

    /**
     * Adds a GET route
     *
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function get(string $uri, string $action): void
    {
        $this->registerRoute("GET", $uri, $action);
    }

    /**
     * Adds a POST route
     *
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function post(string $uri, string $action): void
    {
        $this->registerRoute("POST", $uri, $action);
    }

    /**
     * Adds a PUT route
     *
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function put(string $uri, string $action): void
    {
        $this->registerRoute("PUT", $uri, $action);
    }

    /**
     * Adds a DELETE route
     *
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function delete(string $uri, string $action): void
    {
        $this->registerRoute("DELETE", $uri, $action);
    }

    /**
     * Routes the request (Calls the controller if the uri exists)
     *
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            if ($route["uri"] === $uri && $route["method"] === $method) {
                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];

                // Instantiate and call the method
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();
                return;
            }
        }

        ErrorController::notFound();
    }
}
