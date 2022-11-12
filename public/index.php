<?php

//require_once
 
$done = require_once __DIR__.'/../vendor/autoload.php';
session_start();

use Framework\Routing\Router;

if ($done) {
   $router = new Router();
}else {
    die ("Erro: the file does not exist");
    exit ;

}

$dotenv = Dotenv\Dotenv ::createImmutable(__DIR__. '/..');
$dotenv->load();


$routes = require_once __DIR__. '/../app/routes.php';

if ($routes) {
    $routes ($router);
    return print $router->dispatch();
}
