<?php
declare(strict_types=1);

class Router
{
    protected array $routes = [];


    /**
     * Adds a route to the array from the http verbs methods
     *
     * @param string $method
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function registerRoute(string $method, string $uri, string $controller): void
    {
        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controller" => $controller,
        ];
    }

    /**
     * Adds a GET route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->registerRoute("GET", $uri, $controller);
    }

    /**
     * Adds a POST route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->registerRoute("POST", $uri, $controller);
    }

    /**
     * Adds a PUT route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put(string $uri, string $controller): void
    {
        $this->registerRoute("PUT", $uri, $controller);
    }

    /**
     * Adds a DELETE route
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete(string $uri, string $controller): void
    {
        $this->registerRoute("DELETE", $uri, $controller);
    }

    /**
     * Loads 
     *
     * @param integer $httpResCode
     * @return void
     */
    public function error(int $httpResCode = 404): void
    {
        http_response_code($httpResCode);
        loadView('error/' . $httpResCode);
        exit;
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
                require basePath('App/' . $route['controller']);
                return;
            }
        }

        $this->error();
    }
}