USE gestion;

DELIMITER $$

DROP FUNCTION IF EXISTS nuevoEmpleado$$
CREATE FUNCTION nuevoEmpleado (
    _idEmpleado Varchar(15),
    _nombre Varchar (30),
    _apellido1 Varchar (15),
    _apellido2 Varchar (15),
    _correo Varchar (100),
    _celular Varchar (9))
    RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from empleado where idEmpleado = _idEmpleado;
    if _cant < 1 then
        insert into empleado(idEmpleado, nombre, apellido1, apellido2, correo, celular) 
            values (_idEmpleado, _nombre, _apellido1, _apellido2, _correo, _celular );
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS editarEmpleado$$
CREATE FUNCTION editarEmpleado(
    _id INT, _idEmpleado VARCHAR(15), 
    _nombre VARCHAR(30), 
    _apellido1 VARCHAR(15), 
    _apellido2 VARCHAR(15), 
    _correo VARCHAR(100),
    _celular VARCHAR(9)) 
    RETURNS int(1)
begin
    declare _cant int;
    select count(id) into _cant from empleado where id = _id;
    if _cant > 0 then
        select count(id) into _cant from empleado where idEmpleado = _idEmpleado and id <> _id;
        if _cant = 0 THEN
        	set _cant = 1;
        	update empleado set
                idEmpleado = _idEmpleado,
                nombre = _nombre,
                apellido1 = _apellido1,
                apellido2 = _apellido2,
                correo = _correo,
                celular = _celular             
        	where id = _id;
        else
        	set _cant = 2;
        end if;
    end if;
    return _cant;
end$$

DROP PROCEDURE IF EXISTS buscarEmpleado$$
CREATE PROCEDURE buscarEmpleado (_id int, _idEmpleado varchar(15)) 
begin
    select * from empleado where id = _id or _idEmpleado = idEmpleado; 
end$$

DROP PROCEDURE IF EXISTS buscarAdministrador$$
CREATE PROCEDURE buscarAdministrador (_id int, _idEmpleado varchar(15)) 
begin
    select * from empleado where id = _id or _idEmpleado = idEmpleado; 
end$$

DROP PROCEDURE IF EXISTS buscarGerente$$
CREATE PROCEDURE buscarGerente (_id int, _idEmpleado varchar(15)) 
begin
    select * from empleado where id = _id or _idEmpleado = idEmpleado; 
end$$

DROP PROCEDURE IF EXISTS filtrarEmpleado$$
CREATE PROCEDURE filtrarEmpleado (
    _parametros varchar(250), 
    _pagina SMALLINT UNSIGNED, 
    _cantRegs SMALLINT UNSIGNED)
begin
    SELECT cadenaFiltro(_parametros, 'idEmpleado&nombre&apellido1&apellido2&') INTO @filtro;
    SELECT concat("SELECT * from empleado where ", @filtro, " LIMIT ", 
        _pagina, ", ", _cantRegs) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS numRegsEmpleado$$
CREATE PROCEDURE numRegsEmpleado (
    _parametros varchar(250))
begin
    SELECT cadenaFiltro(_parametros, 'idEmpleado&nombre&apellido1&apellido2&') INTO @filtro;
    SELECT concat("SELECT count(id) from empleado where ", @filtro) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS buscarTodoEmpleado$$
CREATE PROCEDURE buscarTodoEmpleado () 
begin
    select * from empleado;
end$$


DROP FUNCTION IF EXISTS eliminarEmpleado$$
CREATE FUNCTION eliminarEmpleado (_id INT(1)) RETURNS INT(1)
begin
    declare _cant int;
    declare _resp int;
    set _resp = 0;
    select count(id) into _cant from empleado where id = _id;
    if _cant > 0 then
        set _resp = 1;
        select count(id) into _cant from usuario where idUsuario = _id;
        if _cant = 0 then
            delete from empleado where id = _id;
        else 
            -- select 2 into _resp;
            set _resp = 2;
        end if;
    end if;
    return _resp;
end$$

DELIMITER ;