USE gestion;

DELIMITER $$

DROP FUNCTION IF EXISTS nuevoAsignacion$$
CREATE FUNCTION nuevoAsignacion (
    _idAsignacion varchar(15),
    _tarea_id int(11),
    _idUsuario varchar(15))
    RETURNS INT(1) 
begin
    declare _cant int;
    select count(id) into _cant from asignaciones where idAsignacion = _idAsignacion;
    if _cant < 1 then
        insert into asignaciones(idAsignacion, tarea_id, idUsuario) 
            values (_idAsignacion, _tarea_id, _idUsuario);
    end if;
    return _cant;
end$$

DROP PROCEDURE IF EXISTS buscarAsignacion$$
CREATE PROCEDURE buscarAsignacion (_id int)
begin
    SELECT E.idEmpleado, E.nombre, E.apellido1, P.idProyecto, P.nombreProyecto, P.descripcion, T.idTarea, T.nombreTarea, T.descripcionTarea
    FROM empleado E JOIN usuario U ON E.idEmpleado = U.idUsuario 
    JOIN asignaciones A ON A.idUsuario = U.idUsuario 
    JOIN tareas T ON A.tarea_id = T.id 
    JOIN proyectos P ON P.id = T.id_Proyecto 
    WHERE A.id = _id;
end$$

DROP PROCEDURE IF EXISTS filtrarAsignacion$$
CREATE PROCEDURE filtrarAsignacion (
    _parametros varchar(250), 
    _pagina SMALLINT UNSIGNED, 
    _cantRegs SMALLINT UNSIGNED)
begin
    SELECT cadenaFiltro(_parametros, 'idAsignacion&tarea_id&idUsuario') INTO @filtro;
    SELECT concat("SELECT * from asignaciones where ", @filtro, " LIMIT ", 
        _pagina, ", ", _cantRegs) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$

DROP PROCEDURE IF EXISTS numRegsAsignacion$$
CREATE PROCEDURE numRegsAsignacion (
    _parametros varchar(250))
begin
    SELECT cadenaFiltro(_parametros, 'idAsignacion&tarea_id&idUsuario') INTO @filtro;
    SELECT concat("SELECT count(id) from asignaciones where ", @filtro) INTO @sql;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
end$$



DELIMITER ;