<?php

namespace App\controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use PDO;

class Empleado extends AccesoBD{

  const RECURSO = "Empleado";
  const ID = 'idEmpleado';
  const ROL = 1;

  public function crear(Request $request, Response $response, $args){
    $body = json_decode($request->getBody());
    //hash password
    $body->passw = password_hash($body->passwF, PASSWORD_BCRYPT, ['cost' => 10]);
    unset($body->passwF);
    $res = $this->crearUsrBD($body, self::RECURSO, self::ROL, self::ID);
    $status = match($res){
      '0', 0 => 201,
      '1', 1 => 409,
      '2', 2 => 500
    };
   return $response->withStatus($status);
 }

 public function buscarTodo(Request $request, Response $response, $args){
  $res = $this->buscarBDATodo(self::RECURSO);
  $status = !$res ? 404 : 200;
  if($res){
    $response->getBody()->write(json_encode($res));
  }
  return $response->withHeader('Content-type', 'Application/json')->withStatus($status);
}

 public function crearIdPassw(Request $request, Response $response, $args){
  $body = json_decode($request->getBody());
  //hash password
  $body->passw = password_hash($body->idEmpleado, PASSWORD_BCRYPT, ['cost' => 10]);
  $res = $this->crearUsrBD($body, self::RECURSO, self::ROL, self::ID);
  $status = match($res){
    '0', 0 => 201,
    '1', 1 => 409,
    '2', 2 => 500
  };
 return $response->withStatus($status);
}

  public function editar(Request $request, Response $response, $args){
    $id = $args['id'];

    $body = json_decode($request->getBody(),1);
    $res = $this->editarBD($body, self::RECURSO, $id);

    $status = match($res[0]){
    '0', 0 => 404,//no se encontro
    '1', 1 => 200,//se edito correctamente
    '2', 2 => 409 // no encontro al cliente
    };
    return $response->withStatus($status);
  }

  public function buscar(Request $request, Response $response, $args){
    $id= $args['id'];
    $idEmpleado = $args['idEmpleado'];
    $res = $this->buscarBD($id,$idEmpleado,self::RECURSO);
    $status = !$res ? 404 : 200;
    if($res){
      $response->getBody()->write(json_encode($res));
    }
    return $response->withHeader('Content-type', 'Application/json')->withStatus($status);
  }

  public function eliminar(Request $request, Response $response, $args){
    $id = $args['id'];
    $res = $this->eliminarBD($id,self::RECURSO);
    $status = match($res){
      '0', 0 => 404,
      '1', 1 => 200,
      '2', 2 => 412
    };
    return $response->withStatus($status);
  }

  public function filtrar(Request $request, Response $response, $args){
    $datos = $request->getQueryParams();
    $res = $this->filtrarBD($datos,$args,self::RECURSO);
    $response->getBody()->write(json_encode($res));
    return $response->withHeader('Content-type', 'Application/json')->withStatus(200);
  }


  public function numRegs(Request $request, Response $response, $args){
    $datos = $request->getQueryParams();
    $res['cant'] = $this->numRegsBD($datos,self::RECURSO);
    $response->getBody()->write(json_encode($res));
    return $response->withHeader('Content-type', 'Application/json')->withStatus(200);
  }
  

}