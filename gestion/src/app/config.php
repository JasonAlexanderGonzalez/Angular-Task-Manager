<?php

$container->set('config_bd', function(){
  return(object)[
    "host" => "localhost",
    "bd" => "gestion",
    "usr" => "root",
    "pass" => "",
    "charset" => "utf8mb4"
  ];
});

$container->set('clave', function(){
  return "jasdhgJHAGSjhguye237128JHGJH63dxgJHGh*+*";
});