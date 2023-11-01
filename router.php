<?php
    require_once 'database/config.php';
    require_once 'libs/router.php';
    require_once 'app/controllers/ViajeApiController.php';
    require_once 'app/controllers/ClienteApiController.php';

    $router = new Router();

    //revisar por las dudas, creo que es asi 

    #                 endpoint      verbo     controller           método
    $router->addRoute('viajes',     'GET',    'ViajeApiController', 'get'   );
    $router->addRoute('viajes',     'POST',   'ViajeApiController', 'create');
    $router->addRoute('viajes/:ID', 'GET',    'ViajeApiController', 'get'   );
    $router->addRoute('viajes/:ID', 'PUT',    'ViajeApiController', 'update');
    $router->addRoute('viajes/:ID', 'DELETE', 'ViajeApiController', 'delete');
    $router->addRoute('viajes/:ID/:subrecurso', 'GET',    'ViajeApiController', 'get'   );
    $router->addRoute('clientes',     'GET',    'ClienteApiController', 'get'   );
    $router->addRoute('clientes',     'POST',   'ClienteApiController', 'create');
    $router->addRoute('clientes/:ID', 'GET',    'ClienteApiController', 'get'   );
    $router->addRoute('clientes/:ID', 'PUT',    'ClienteApiController', 'update');
    $router->addRoute('clientes/:ID', 'DELETE', 'ClienteApiController', 'delete');
    $router->addRoute('clientes/:ID/:subrecurso', 'GET',    'ClienteApiController', 'get'   );
    

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);