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
    public function route(string $uri): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {

            // Split the current uri into segments
            $uriSegments = explode('/', trim($uri, '/'));

            // Split the current route method
            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            // Check if number of segment matches
            if (count($uriSegments) === count($routeSegments) && (strtoupper($route['method']) === strtoupper($requestMethod))) {
                $match = true;
                $params = [];

                // If the url does not match and there is no param
                for ($i = 0; $i < count($routeSegments); $i++) {
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    // Check for the param and add it to the $params array
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if ($match) {

                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Instantiate and call the method
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}
