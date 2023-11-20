<?php
namespace App\controller;
use Psr\Container\ContainerInterface;
use PDO;
use Firebase\JWT\JWT;

class Token{

  private function modificarToken(string $idUsuario, string $tokenRef = ""){
    $this->accederToken('modificar',$idUsuario,$tokenRef);
  }

  public function generar(string $idUsuario, int $rol, string $nombre){
    $key = 'Alguna clave'; //crear una clave
    $payload = [
      'iss' => $_SERVER['SERVER_NAME'],
      'iat' => time(), //cuando fue que se genero
      'exp' => time() + 60, //tiempo de expiracion
      'sub' => $idUsuario, //el que tiene la sesion iniciada
      'rol' => $rol,
      'nom' => $nombre
    ];
    $payloadRef = [
      'iss' => $_SERVER['SERVER_NAME'],
      'iat' => time(), //cuando fue que se genero
      'rol' => $rol
    ];
    $tkRef = JWT::encode($payloadRef, $key, 'HS256');
    //guardar el token de refresco
    $this->modificarToken(idUsuario: $idUsuario, tokenRef: $tkRef);
    return [
      "token" =>  $tk = JWT::encode($payload, $key, 'HS256'),
      "refreshToken" => $tkRef
    ];
  }
}
