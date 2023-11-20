<?php

//inyector de dependencias
use DI\Container;

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

//contenedor de inyeccion de dependencias
$cont_aux = new \DI\Container();

//los dos puntos significa que lo usa de forma estatica
AppFactory::setContainer($cont_aux);

$app = AppFactory::create();

$container = $app->getContainer();
include_once 'config.php';

$app->add(new Tuupola\Middleware\JwtAuthentication([
  "secure" => false, // false en caso de que no tenga el ccl, true en caso de que lo tenga (certificado de seguridad)
  //"path" => ['/cliente'], // lo que quiero que se asegure
  "ignore" => ['/usuario','/sesion', '/empleado', '/asignaciones', '/tarea', '/proyecto'],  //ignorar lo que no queremos que este protegido
  "secret" => $container->get('clave'),
  "algorithm" => ['HS256', 'HS384']
]));

include_once 'routes.php';
include_once 'conexion.php';

$app->run();

