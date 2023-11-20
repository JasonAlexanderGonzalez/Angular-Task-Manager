USE gestion;

DELIMITER $$

DROP FUNCTION IF EXISTS nuevoProyecto$$
CREATE FUNCTION nuevoProyecto (
    _idProyecto Varchar(15),
    _nombreProyecto Varchar(50),
    _descripcion Varchar (200),
    _fechaInicio date,
    _fechaFinalizacion date,
    _estado varchar(20))
    RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from proyectos where idProyecto = _idProyecto;
    if _cant < 1 then
        insert into proyectos(idProyecto, nombreProyecto, descripcion, fechaInicio, fechaFinalizacion, estado) 
            values (_idProyecto, _nombreProyecto, _descripcion, _fechaInicio, _fechaFinalizacion, _estado );
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS editarProyecto$$
CREATE FUNCTION editarProyecto(
    _id INT, _idProyecto VARCHAR(15), 
    _nombreProyecto VARCHAR(50), 
    _descripcion VARCHAR(200), 
    _fechaInicio date, 
    _fechaFinalizacion date,
    _estado varchar(15)) 
    RETURNS int(1)
begin
    declare _cant int;
    select count(id) into _cant from proyectos where id = _id;
    if _cant > 0 then
        select count(id) into _cant from proyectos where idProyecto = _idProyecto and id <> _id;
        if _cant = 0 THEN
        	set _cant = 1;
        	update proyectos set
                idProyecto = _idProyecto,
                nombreProyecto = _nombreProyecto,
                descripcion = _descripcion,
                fechaInicio = _fechaInicio,
                fechaFinalizacion = _fechaFinalizacion,
                estado = _estado             
        	where id = _id;
        else
        	set _cant = 2;
        end if;
    end if;
    return _cant;
end$$

DROP PROCEDURE IF EXISTS buscarProyecto$$
CREATE PROCEDURE buscarProyecto (_id int) 
begin
    select * from proyectos where id = _id; 
end$$

DROP PROCEDURE IF EXISTS buscarTodoProyecto$$
CREATE PROCEDURE buscarTodoProyecto () 
begin
    select * from proyectos;
end$$

DROP PROCEDURE IF EXISTS filtrarProyecto$$
CREATE PROCEDURE filtrarProyecto (
    _parametros varchar(250), 
    _pagina SMALLINT UNSIGNED, 
    _cantRegs SMALLINT UNSIGNED)
begin
    SELECT cadenaFiltro(_parametros, 'idProyecto&nombreProyecto&estado') INTO @filtro;
    SELECT concat("SELECT * from proyectos where ", @filtro, " LIMIT ", 
        _pagina, ", ", _cantRegs) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS numRegsProyecto$$
CREATE PROCEDURE numRegsProyecto (
    _parametros varchar(250))
begin
    SELECT cadenaFiltro(_parametros, 'idProyecto&nombreProyecto&descripcion&fechaInicio&') INTO @filtro;
    SELECT concat("SELECT count(id) from proyectos where ", @filtro) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP FUNCTION IF EXISTS eliminarProyecto$$
CREATE FUNCTION eliminarProyecto (_id INT(1)) RETURNS INT(1)
begin
    declare _cant int;
    declare _resp int;
    set _resp = 0;
    select count(id) into _cant from proyectos where id = _id;
    if _cant > 0 then
        set _resp = 1;
        select count(id) into _cant from tareas where id_proyecto = _id;
        if _cant = 0 then
            delete from proyectos where id = _id;
        else 
            -- select 2 into _resp;
            set _resp = 2;
        end if;
    end if;
    return _resp;
end$$




DELIMITER ;