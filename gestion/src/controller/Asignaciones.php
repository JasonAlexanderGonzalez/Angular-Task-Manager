<?php

namespace App\controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use PDO;

class Asignaciones extends AccesoBD{

  const RECURSO = "Asignacion";

  public function crear(Request $request, Response $response, $args){
    $body = json_decode($request->getBody());
    $res = $this->crearBD($body, self::RECURSO);
    $status = match($res){
      '0',0 => 201,
      '1',1 => 409,
      '2',2 => 404
    };
    return $response->withStatus($status);
  }

  public function buscar(Request $request, Response $response, $args){
    $id= $args['id'];
    $res = $this->buscarAsig($id,self::RECURSO);
    $status = !$res ? 404 : 200;
    if($res){
      $response->getBody()->write(json_encode($res));
    }
    return $response->withHeader('Content-type', 'Application/json')->withStatus($status);
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