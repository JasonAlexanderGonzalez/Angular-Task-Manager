<?php

use Psr\Container\ContainerInterface;

//Guarda en el contenedor el objeto que se hizo en config.
$container->set('bd', function(ContainerInterface $c){
  $conf = $c->get('config_bd');
  $opc = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//El modo de error y puede ser exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ  
  ];
  $dsn = "mysql:host=$conf->host;dbname=$conf->bd;charset=$conf->charset";
  try{
    $con = new PDO($dsn, $conf->usr, $conf->pass, $opc);
  }catch(PDOExeption $e){
    print "error" . $e->getMessage() . "<br>"; //quitar en produccion
    die();
  }
  return $con;
});