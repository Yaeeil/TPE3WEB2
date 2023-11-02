<?php
require_once 'database/config.php';
require_once 'libs/router.php';
require_once 'app/controllers/ViajeApiController.php';
require_once 'app/controllers/ClienteApiController.php';

$router = new Router();

//revisar por las dudas, creo que es asi 

#                 endpoint      verbo     controller           método
//viajes
$router->addRoute('viajes', 'GET', 'ViajeApiController', 'get');
$router->addRoute('viajes', 'POST', 'ViajeApiController', 'create');
$router->addRoute('viajes/:ID', 'GET', 'ViajeApiController', 'get');
$router->addRoute('viajes/:ID', 'PUT', 'ViajeApiController', 'update');
$router->addRoute('viajes/:ID', 'DELETE', 'ViajeApiController', 'delete');
$router->addRoute('viajes/:ID/:subrecurso', 'GET', 'ViajeApiController', 'get');
//este es el orden fijo
$router->addRoute('viajesOrderDestino', 'GET', 'ViajeApiController', 'getOrderDestino');
//clientes
$router->addRoute('clientes', 'GET', 'ClienteApiController', 'get');
$router->addRoute('clientes', 'POST', 'ClienteApiController', 'create');
$router->addRoute('clientes/:ID', 'GET', 'ClienteApiController', 'get');
$router->addRoute('clientes/:ID', 'PUT', 'ClienteApiController', 'update');
$router->addRoute('clientes/:ID', 'DELETE', 'ClienteApiController', 'delete');
$router->addRoute('clientes/:ID/:subrecurso', 'GET', 'ClienteApiController', 'get');
//este es el orden fijo
$router->addRoute('clientes/orderNombre', 'GET', 'ClienteApiController', 'get');

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);