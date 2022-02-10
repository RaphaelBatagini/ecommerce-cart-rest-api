<?php

namespace App;

use CoffeeCode\Router\Router;

$domain = $_ENV['PROJECT_URL'] ?: 'localhost';
$router = new Router($domain);

define('CONTROLLERS_NAMESPACE', 'Root\HashBackendChallenge\Controllers');

/* Create your routes here */
$router->namespace(CONTROLLERS_NAMESPACE);
$router->get('/', 'IndexController:index');

$router->group('cart')->namespace(CONTROLLERS_NAMESPACE);
$router->post('/add/{productId}', 'CartsController:addProduct');

$router->group('error')->namespace(CONTROLLERS_NAMESPACE);
$router->get('/{errcode}', 'IndexController:error');

/* End of project routes */

$router->dispatch();

/*
 * Redirect all errors
 */
if ($router->error()) {
    $router->redirect("/error/{$router->error()}");
}
