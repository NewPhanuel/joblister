<?php
declare(strict_types=1);

use App\Controllers\ListingsController;

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingsController@index');
$router->get('/listings/create', 'ListingsController@create');
$router->get('/listings/{id}', 'ListingsController@show');

$router->post('/listings', 'ListingsController@store');

$router->delete('/listings/{id}', 'ListingsController@destroy');
