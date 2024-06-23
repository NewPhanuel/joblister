<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";
require '../helpers.php';

use Framework\Router;
use Framework\Session;

Session::start();

// Instantiatig the router
$router = new Router();

// Get routes
require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request
$router->route($uri);