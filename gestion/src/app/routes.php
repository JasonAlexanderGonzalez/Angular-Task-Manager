<?php

namespace App\controller;
use Slim\Routing\RouteCollectorProxy;
//require __DIR__ .'/../controller/Artefato.php';

$app->group('/empleado',function(RouteCollectorProxy $empleado){
  $empleado->get('/{pagina}/{limite}', Empleado::class.':filtrar'); //listo
  $empleado->get('/{id}/{idEmpleado}/', Empleado::class.':buscar'); //listo
  $empleado->post('', Empleado::class.':crear'); //listo
  $empleado->put('/{id}', Empleado::class.':editar'); //listo
  $empleado->delete('/{id}', Empleado::class.':eliminar'); //listo
  $empleado->get('', Empleado::class.':numRegs');
  $empleado->post('/crear', Empleado::class.':crearIdPassw');
  $empleado->get('/', Empleado::class.':buscarTodo');
});

$app->group('/usuario', function(RouteCollectorProxy $usuario){
  $usuario->patch('/rol/{id}', Usuario::class.':cambiarRol');

  $usuario->group('/passw', function(RouteCollectorProxy $passw){
    $passw->patch('/cambio/{id}',Usuario::class.':cambiarPassw');
    $passw->patch('/reset/{id}',Usuario::class.':resetPassw');
  });
});

$app->group('/proyecto',function(RouteCollectorProxy $proyecto){
  $proyecto->get('/{id}', Proyecto::class.':buscar'); //listo
  $proyecto->get('/{pagina}/{limite}', Proyecto::class.':filtrar'); //listo
  $proyecto->post('', Proyecto::class.':crear'); //listo
  $proyecto->put('/{id}', Proyecto::class.':editar'); //listo
  $proyecto->delete('/{id}', Proyecto::class.':eliminar'); //listo
  $proyecto->get('', Proyecto::class.':numRegs');
  $proyecto->get('/', Proyecto::class.':buscarTodo');
});

$app->group('/tarea',function(RouteCollectorProxy $tarea){
  $tarea->get('/{id}', Tarea::class.':buscar'); //listo
  $tarea->get('/{pagina}/{limite}', Tarea::class.':filtrar'); //listo
  $tarea->post('', Tarea::class.':crear'); //listo
  $tarea->put('/{id}', Tarea::class.':editar'); //listo
  $tarea->delete('/{id}', Tarea::class.':eliminar'); //listo
  $tarea->get('', Tarea::class.':numRegs');
  $tarea->get('/', Tarea::class.':buscarTodo');
  $tarea->get('/est/pen/', Tarea::class.':buscarTodoEsta');
});

$app->group('/sesion', function(RouteCollectorProxy $sesion){
  $sesion->patch('/iniciar/{id}', Sesion::class.':iniciar');
  $sesion->patch('/cerrar/{id}', Sesion::class.':cerrar');
  $sesion->patch('/refrescar/{id}', Sesion::class.':refrescar');
});

$app->group('/asignaciones',function(RouteCollectorProxy $asignaciones){
  $asignaciones->get('/{id}', Asignaciones::class.':buscar'); //listo
  $asignaciones->post('', Asignaciones::class.':crear'); //listo
  $asignaciones->get('/{pagina}/{limite}', Asignaciones::class.':filtrar'); 
  $asignaciones->get('', Asignaciones::class.':numRegs');
});