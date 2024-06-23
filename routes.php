<?php
declare(strict_types=1);

// Get routes
$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingsController@index');
$router->get('/listings/create', 'ListingsController@create');
$router->get('/listings/edit/{id}', 'ListingsController@edit');
$router->get('/listings/{id}', 'ListingsController@show');

// Post routes
$router->post('/listings', 'ListingsController@store');

// Delete routes
$router->delete('/listings/{id}', 'ListingsController@destroy');

// Put routes
$router->put('/listings', 'ListingsController@update');

// Authentication routes
$router->get('/auth/register', 'UserController@create');
$router->get('/auth/login', 'UserController@login');
$router->post('/auth/register', 'Usercontroller@store');
$router->post('/auth/logout', 'UserController@logout');
$router->post('/auth/login', 'UserController@authenticate');