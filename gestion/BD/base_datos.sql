DROP DATABASE IF EXISTS gestion;
CREATE DATABASE gestion DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

USE gestion;

DROP TABLE IF EXISTS empleado;
CREATE TABLE empleado(
  id int(11) NOT NULL AUTO_INCREMENT,
  idEmpleado Varchar(15) not null,
  nombre Varchar(30) not null,
  apellido1 Varchar(15) not null,
  apellido2 Varchar(15) not null,
  correo Varchar(100) not null,
  celular Varchar(9),
  fechaIngreso Datetime Default now(),
  primary key(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS usuario;
CREATE TABLE usuario (
  id int(11) NOT NULL AUTO_INCREMENT,  
  idUsuario Varchar(15) NOT NULL,
  rol int not NULL,
  passw varchar(255) not NULL,
  ultimoAcceso Datetime,
  PRIMARY KEY (id),
  UNIQUE KEY idx_Usuario (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS proyectos;
CREATE TABLE proyectos(
  id int(11) NOT NULL AUTO_INCREMENT,
  nombreProyecto Varchar(50) not null,
  descripcion Varchar(200) not null,
  fechaInicio Datetime not null,
  fechaFinalizacion Datetime not null,
  primary key(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS tareas;
CREATE TABLE tareas(
  id int(11) NOT NULL AUTO_INCREMENT,
  nombreTarea Varchar(50) not null,
  descripcionTarea Varchar(200) not null,
  fechaInicio Datetime not null,
  fechaFinalizacion Datetime not null,
  primary key(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS asignaciones;
CREATE TABLE asignaciones(
  id int(11) NOT NULL AUTO_INCREMENT,
  primary key(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;