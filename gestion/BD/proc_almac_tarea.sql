USE gestion;

DELIMITER $$

DROP FUNCTION IF EXISTS nuevoTarea$$
CREATE FUNCTION nuevoTarea (
    _idTarea Varchar(15),
    _nombreTarea Varchar (50),
    _descripcionTarea Varchar (200),
    _fechaInicio date,
    _fechaFinalizacion date,
    _id_proyecto int (11),
    _estado Varchar(20))
    RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from tareas where idTarea = _idTarea;
    if _cant < 1 then
        insert into tareas(idTarea, nombreTarea, descripcionTarea, fechaInicio, fechaFinalizacion, id_proyecto, estado) 
            values (_idTarea, _nombreTarea, _descripcionTarea, _fechaInicio, _fechaFinalizacion, _id_proyecto, _estado );
    end if;
    return _cant;
end$$

DROP FUNCTION IF EXISTS eliminarTarea$$
CREATE FUNCTION eliminarTarea (_id INT(1)) RETURNS INT(1)
begin
    declare _cant int;
    declare _resp int;
    set _resp = 0;
    select count(id) into _cant from tareas where id = _id;
    if _cant > 0 then
        set _resp = 1;
        delete from tareas where id = _id;
    end if;
    return _resp;
end$$

DROP PROCEDURE IF EXISTS buscarTarea$$
CREATE PROCEDURE buscarTarea (_id int) 
begin
    select * from tareas where id = _id; 
end$$

DROP PROCEDURE IF EXISTS filtrarTarea$$
CREATE PROCEDURE filtrarTarea (
    _parametros varchar(250), 
    _pagina SMALLINT UNSIGNED, 
    _cantRegs SMALLINT UNSIGNED)
begin
    SELECT cadenaFiltro(_parametros, 'idTarea&nombreTarea&descripcionTarea&estado&') INTO @filtro;
    SELECT concat("SELECT * from tareas where ", @filtro, " LIMIT ", 
        _pagina, ", ", _cantRegs) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS numRegsTarea$$
CREATE PROCEDURE numRegsTarea (
    _parametros varchar(250))
begin
    SELECT cadenaFiltro(_parametros, 'idTarea&nombreTarea&descripcionTarea&fechaInicio&') INTO @filtro;
    SELECT concat("SELECT count(id) from tareas where ", @filtro) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS buscarTodoTarea$$
CREATE PROCEDURE buscarTodoTarea () 
begin
    select * from tareas;
end$$

DROP PROCEDURE IF EXISTS buscarTodoEstaTarea$$
CREATE PROCEDURE buscarTodoEstaTarea () 
begin
    select * from tareas where estado = "pendiente";
end$$


DROP FUNCTION IF EXISTS editarTarea$$
CREATE FUNCTION editarTarea(
    _id INT, _idTarea VARCHAR(15), 
    _nombreTarea Varchar (50),
    _descripcionTarea Varchar (200),
    _fechaInicio date,
    _fechaFinalizacion date,
    _id_proyecto int (11),
    _estado Varchar(20)) 
    RETURNS int(1)
begin
    declare _cant int;
    select count(id) into _cant from tareas where id = _id;
    if _cant > 0 then
        select count(id) into _cant from tareas where idTarea = _idTarea and id <> _id;
        if _cant = 0 THEN
        	set _cant = 1;
        	update tareas set
                idTarea = _idTarea,
                nombreTarea = _nombreTarea,
                descripcionTarea = _descripcionTarea,
                fechaInicio = _fechaInicio,
                fechaFinalizacion = _fechaFinalizacion,
                id_proyecto = _id_proyecto,
                estado = _estado             
        	where id = _id;
        else
        	set _cant = 2;
        end if;
    end if;
    return _cant;
end$$

DELIMITER ;