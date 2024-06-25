<?php
declare(strict_types=1);

// Get routes
$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingsController@index');
$router->get('/listings/create', 'ListingsController@create', ['auth']);
$router->get('/listings/edit/{id}', 'ListingsController@edit', ['auth']);
$router->get('/listings/search', 'ListingsController@search');
$router->get('/listings/{id}', 'ListingsController@show');

// Post routes
$router->post('/listings', 'ListingsController@store', ['auth']);

// Delete routes
$router->delete('/listings/{id}', 'ListingsController@destroy', ['auth']);

// Put routes
$router->put('/listings', 'ListingsController@update', ['auth']);

// Authentication routes
$router->get('/auth/register', 'UserController@create', ['guest']);
$router->get('/auth/login', 'UserController@login', ['guest']);
$router->post('/auth/register', 'Usercontroller@store', ['guest']);
$router->post('/auth/logout', 'UserController@logout', ['auth']);
$router->post('/auth/login', 'UserController@authenticate', ['guest']);