DROP PROCEDURE IF EXISTS `esp_respuestas_facultad`;

DELIMITER $$

CREATE PROCEDURE `esp_respuestas_facultad`(
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	-- Respuestas Materia
	SELECT	idClave, R.idPregunta, 
			IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),R.opcion) as 'opcion', 
			R.texto
	FROM 	Respuestas R INNER JOIN Preguntas P ON R.idPregunta = P.idPregunta
	WHERE	R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	ORDER BY idClave;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_respuestas_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_respuestas_departamento`(
	pidDepartamento SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	-- Respuestas Materia
	SELECT	idClave, R.idPregunta, 
			IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),R.opcion) as 'opcion', 
			R.texto
	FROM 	Respuestas R INNER JOIN Preguntas P ON R.idPregunta = P.idPregunta
						 INNER JOIN Carreras C ON C.idCarrera = R.idCarrera
	WHERE	R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario AND 
			C.idDepartamento = pidDepartamento
	ORDER BY idClave;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS `esp_respuestas_carrera`;

DELIMITER $$

CREATE PROCEDURE `esp_respuestas_carrera`(
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	-- Respuestas Materia
	SELECT	idClave, R.idPregunta, 
			IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),R.opcion) as 'opcion', 
			R.texto
	FROM 	Respuestas R INNER JOIN Preguntas P ON R.idPregunta = P.idPregunta
	WHERE	R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario AND 
			R.idCarrera = pidCarrera
	ORDER BY idClave;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS `esp_respuestas_materia`;

DELIMITER $$

CREATE PROCEDURE `esp_respuestas_materia`(
	pidCarrera SMALLINT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	-- Respuestas Materia
	SELECT	idClave, R.idDocente, R.idPregunta, 
			IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),R.opcion) as 'opcion', 
			R.texto
	FROM 	Respuestas R INNER JOIN Preguntas P ON R.idPregunta = P.idPregunta
	WHERE	R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario AND 
			R.idCarrera = pidCarrera AND R.idMateria = pidMateria
	ORDER BY idClave, R.idDocente;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_devolucion`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_devolucion`(
	pidDevolucion INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
    SELECT  idDevolucion, idMateria, idEncuesta, idFormulario, fecha,
			fortalezas, debilidades, alumnos, docentes, mejoras
    FROM	Devoluciones
	WHERE 	idDevolucion = pidDevolucion AND idMateria = pidMateria AND 
			idEncuesta = pidEncuesta AND idFormulario = pidFormulario;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS `esp_listar_devoluciones_materia`;

DELIMITER $$

CREATE PROCEDURE `esp_listar_devoluciones_materia`(
	pidMateria SMALLINT UNSIGNED,
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idDevolucion, idMateria, idEncuesta, idFormulario, fecha,
			fortalezas, debilidades, alumnos, docentes, mejoras
    FROM	Devoluciones
	WHERE 	idMateria = ?
    ORDER BY fecha DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pidMateria;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_devoluciones_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_devoluciones_materia`(
	pidMateria SMALLINT UNSIGNED)
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Devoluciones
	WHERE	idMateria = pidMateria;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_devolucion`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_devolucion`(
    pidMateria SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED,
    pfortalezas TEXT,
    pdebilidades TEXT,
    palumnos TEXT,
    pdocentes TEXT,
    pmejoras TEXT)
BEGIN
    DECLARE id INT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pfortalezas,'') = '' AND COALESCE(pdebilidades,'') = '' AND
       COALESCE(pdebilidades,'') = '' AND COALESCE(palumnos,'') = '' AND
       COALESCE(pdocentes,'') = '' AND COALESCE(pmejoras,'') = '' THEN
        SET mensaje = 'Debe escribir en al menos un campo.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(  SELECT  idEncuesta FROM Encuestas 
                        WHERE   idEncuesta = pidEncuesta AND idFormulario = pidFormulario LIMIT 1) THEN
            SET mensaje = 'No se encontró la Encuesta correspondiente.';
            ROLLBACK;
        ELSE
            SET id = (  
                SELECT COALESCE(MAX(idDevolucion),0)+1 
                FROM    Devoluciones
                WHERE   idMateria = pidMateria AND
                        idEncuesta = pidEncuesta AND
                        idFormulario = pidFormulario);
            INSERT INTO Devoluciones
                (idDevolucion, idMateria, idEncuesta, idFormulario, fecha, 
                fortalezas, debilidades, alumnos, docentes, mejoras)
            VALUES (id, pidMateria, pidEncuesta, pidFormulario, NOW(), 
                pfortalezas, pdebilidades, palumnos, pdocentes, pmejoras);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = id;
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_registrar_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_registrar_clave`(
    pidClave INT UNSIGNED,
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;
    UPDATE  Claves
    SET     utilizada = NOW()
    WHERE   idClave = pidClave AND
            idMateria = pidMateria AND
            idCarrera = pidCarrera AND
            idEncuesta = pidEncuesta AND
            idFormulario = pidFormulario AND            
            utilizada IS NULL;
    IF err THEN
        SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
        ROLLBACK;
    ELSE
        SET mensaje = 'ok';
        COMMIT;
    END IF;
    SELECT mensaje;    
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_respuesta`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_respuesta`(
    pidPregunta INT UNSIGNED,
    pidClave INT UNSIGNED,
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED,
    pidDocente INT UNSIGNED,
    popcion TINYINT UNSIGNED,
    ptexto TEXT)
BEGIN
    DECLARE id INT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
	IF NOT EXISTS (	SELECT idClave FROM Claves WHERE idClave = pidClave AND idMateria = pidMateria AND 
				idCarrera = pidCarrera AND idEncuesta = pidEncuesta AND idFormulario = pidFormulario AND utilizada IS NULL LIMIT 1) THEN
		SET mensaje = 'La clave de acceso utilizada no existe o ya fue usada.';
		ROLLBACK;
	ELSE
		SET id = (  
			SELECT COALESCE(MAX(idRespuesta),0)+1 
			FROM    Respuestas
			WHERE   idPregunta = pidPregunta AND
					idClave = pidClave AND 
					idMateria = pidMateria AND 
					idCarrera = pidCarrera AND 
					idEncuesta = pidEncuesta AND                 
					idFormulario = pidFormulario);
		INSERT INTO Respuestas 
			(idRespuesta, idPregunta, idClave, idMateria, idCarrera, idEncuesta, 
			idFormulario, idDocente, opcion, texto)
		VALUES (id, pidPregunta, pidClave, pidMateria, pidCarrera, pidEncuesta, 
			pidFormulario, pidDocente, popcion, ptexto); 
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET mensaje = id;
			COMMIT;
		END IF;
	END IF;
	SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_materias_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_materias_carrera`(
	pidCarrera SMALLINT UNSIGNED)
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Materias_Carreras
	WHERE	idCarrera = pidCarrera;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_materias_carrera`(
	pidCarrera SMALLINT UNSIGNED,
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
	SET @qry = '
    SELECT  M.idMateria, M.nombre, M.codigo, M.alumnos
    FROM    Materias M INNER JOIN Materias_Carreras MC ON M.idMateria = MC.idMateria
	WHERE	MC.idCarrera = ?
    ORDER BY M.nombre
	LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pidCarrera;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_materias`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_materias`()
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Materias;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_materias`(
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idMateria, nombre, codigo, alumnos
    FROM    Materias
    ORDER BY nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_usuarios`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_usuarios`(
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  id, apellido, nombre, username, password, email, active, last_login
    FROM    Usuarios
    ORDER BY apellido, nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_usuarios_grupo`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_usuarios_grupo`(
	pidGrupo INT UNSIGNED,
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  U.id, apellido, nombre, username, password, email, active, last_login
    FROM    Usuarios U INNER JOIN Usuarios_Grupos G ON U.id = G.id_usuario
	WHERE	G.id_grupo = ?
    ORDER BY apellido, nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pidGrupo;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_usuarios`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_usuarios`()
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Usuarios;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_usuarios_grupo`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_usuarios_grupo`(
	pidGrupo INT UNSIGNED)
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Usuarios_Grupos
	WHERE	id_grupo = pidGrupo;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_departamentos`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_departamentos`(
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idDepartamento, idJefeDepartamento, nombre
    FROM Departamentos
    ORDER BY nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_departamentos`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_departamentos`()
BEGIN
    SELECT COUNT(*) AS cantidad
    FROM Departamentos;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_usuario`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_usuario`(
    pid INT UNSIGNED)
BEGIN
    SELECT id, username, NULL as 'password', email, last_login, active, nombre, apellido
    FROM Usuarios
    WHERE id = pid;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_docente_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_docente_materia`(
    pid INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED)
BEGIN
    SELECT tipoAcceso, ordenFormulario, cargo
    FROM Docentes_Materias
    WHERE idDocente = pid AND idMateria = pidMateria;
END $$

DELIMITER ;


-- DROP PROCEDURE IF EXISTS `esp_listar_carreras_departamento`;


-- DELIMITER $$

-- CREATE PROCEDURE `esp_listar_carreras_departamento`(
--     pidDepartamento SMALLINT UNSIGNED)
-- BEGIN
--     SELECT  idDepartamento, idCarrera, idDirectorCarrera, nombre, plan
--     FROM    Carreras
--     WHERE   idDepartamento = pidDepartamento
--     ORDER BY nombre, plan DESC;
-- END $$

-- DELIMITER ;


-- DROP PROCEDURE IF EXISTS `esp_cantidad_carreras_departamento`;


-- DELIMITER $$


-- CREATE PROCEDURE `esp_cantidad_carreras_departamento`(
--     pidDepartamento SMALLINT UNSIGNED)
-- BEGIN
--     SELECT  COUNT(*) AS cantidad
--     FROM    Carreras
--     WHERE   idDepartamento = pidDepartamento;
-- END $$

-- DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_departamento`;


DELIMITER $$


CREATE PROCEDURE `esp_dame_departamento`(
    pidDepartamento SMALLINT UNSIGNED)
BEGIN
    SELECT idDepartamento, idJefeDepartamento, nombre
    FROM Departamentos
    WHERE idDepartamento = pidDepartamento;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_pregunta`;


DELIMITER $$


CREATE PROCEDURE `esp_dame_pregunta`(
    pidPregunta INT UNSIGNED)
BEGIN
    SELECT	idPregunta, idCarrera, texto, descripcion, creacion, tipo,
			obligatoria, ordenInverso, limiteInferior, limiteSuperior,
			paso, unidad
    FROM Preguntas
    WHERE idPregunta = pidPregunta;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_clave`;


DELIMITER $$


CREATE PROCEDURE `esp_buscar_clave`(
    pclave CHAR(16))
BEGIN
    -- busca una clave en las encuestas que no finalizaron
    SELECT  idClave, idMateria, idCarrera, C.idEncuesta, C.idFormulario,
            clave, tipo, generada, utilizada
    FROM    Claves C INNER JOIN Encuestas E ON C.idEncuesta = E.idEncuesta AND C.idFormulario = E.idFormulario
    WHERE   clave = UPPER(pclave) AND fechaFin IS NULL
    LIMIT   1;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_clave`(
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED,
    ptipo CHAR(1))
BEGIN
    DECLARE nid INT UNSIGNED;
    DECLARE clave CHAR(12);
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF NOT ptipo IN ('E','R','O') THEN
        SET mensaje = 'El tipo de clave es incorrecto.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(  SELECT  idEncuesta FROM Encuestas 
                        WHERE   idEncuesta = pidEncuesta AND 
                                idFormulario = idFormulario AND fechaFin IS NULL LIMIT 1) THEN
            SET mensaje = 'No se encontró la encuesta correspondiente o la misma ya concluyó.';
            ROLLBACK;
        ELSE
            SET nid = (  
                SELECT COALESCE(MAX(idClave),0)+1 
                FROM    Claves
                WHERE   idMateria = pidMateria AND
                        idCarrera = pidCarrera AND
                        idEncuesta = pidEncuesta AND
                        idFormulario = pidFormulario);            
            SET clave = SUBSTRING(MD5(CONCAT(
                nid,pidMateria,pidCarrera,pidEncuesta,pidFormulario,ptipo,NOW())),1,12);
            INSERT INTO Claves 
                (idClave, idMateria, idCarrera, idEncuesta, idFormulario, clave, tipo, generada, utilizada)
            VALUES (nid, pidMateria, pidCarrera, pidEncuesta, pidFormulario, UPPER(clave), ptipo, NOW(), NULL);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;            
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_secciones_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_secciones_carrera`(
    pidFormulario INT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED)
BEGIN
    SELECT  idSeccion, idFormulario, idCarrera, texto, descripcion, tipo
    FROM    Secciones
    WHERE   idFormulario = pidFormulario AND (idCarrera IS NULL OR idCarrera = pidCarrera)
    ORDER BY idSeccion;
    -- se ordena por ID, es decir por orden de creacion (por lo tanto las de la carrera van al final)    
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_secciones`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_secciones`(
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  idSeccion, idFormulario, idCarrera, texto, descripcion, tipo
    FROM    Secciones
    WHERE   idFormulario = pidFormulario AND (idCarrera IS NULL)
    ORDER BY idSeccion;
    -- se ordena por ID, es decir por orden de creacion
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_items_seccion_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_items_seccion_carrera`(
    pidSeccion INT UNSIGNED,
    pidFormulario INT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED)
BEGIN
    START TRANSACTION;
    -- las columnas posicion y tamaño los toma de items_secciones, pero si son nulos, los toma de items
    SELECT  P.idPregunta, P.idCarrera, P.texto, P.descripcion, P.creacion, P.tipo, 
            obligatoria, ordenInverso, limiteInferior, limiteSuperior, paso, unidad,
            COALESCE(IC.posicion, I.posicion) AS posicion
    FROM    Items I INNER JOIN Preguntas P ON I.idPregunta = P.idPregunta 
            LEFT JOIN Items_Carreras IC ON I.idSeccion = IC.idSeccion AND 
                I.idFormulario = IC.idFormulario AND I.idPregunta = IC.idPregunta AND
                I.idCarrera = IC.idCarrera
    WHERE   I.idSeccion = pidSeccion AND I.idFormulario = pidFormulario AND (I.idCarrera IS NULL OR I.idCarrera = pidCarrera)
    ORDER BY posicion;
    COMMIT;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_items_seccion`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_items_seccion`(
    pidSeccion INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    -- las columnas posicion y tamaño los toma de items_secciones, pero si son nulos, los toma de items
    SELECT  P.idPregunta, P.idCarrera, P.texto, P.descripcion, P.creacion, P.tipo, 
            obligatoria, ordenInverso, limiteInferior, limiteSuperior, paso, unidad,
            COALESCE(IC.posicion, I.posicion) AS posicion
    FROM    Items I INNER JOIN Preguntas P ON I.idPregunta = P.idPregunta 
            LEFT JOIN Items_Carreras IC ON I.idSeccion = IC.idSeccion AND 
                I.idFormulario = IC.idFormulario AND I.idPregunta = IC.idPregunta AND
                I.idCarrera = IC.idCarrera
    WHERE   I.idSeccion = pidSeccion AND I.idFormulario = pidFormulario AND I.idCarrera IS NULL
    ORDER BY posicion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_opciones`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_opciones`(
    pidPregunta INT UNSIGNED)
BEGIN
    SELECT  idOpcion, texto
    FROM    Opciones
    WHERE   idPregunta = pidPregunta
    ORDER BY idOpcion;
    -- ordenados por orden de creacion
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_materia`(
    pidMateria SMALLINT UNSIGNED)
BEGIN
    SELECT idMateria, nombre, codigo, alumnos
    FROM Materias
    WHERE idMateria = pidMateria;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_carrera`(
    pidCarrera SMALLINT UNSIGNED)
BEGIN
    SELECT idCarrera, idDepartamento, idDirectorCarrera, nombre, plan
    FROM Carreras
    WHERE idCarrera = pidCarrera;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_departamento`(
	pidJefeDepartamento INT UNSIGNED,
    pnombre VARCHAR(60))
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre del departamento no puede estar vacío.';
    ELSE
        START TRANSACTION;
        IF EXISTS( SELECT nombre FROM Departamentos WHERE nombre = pnombre LIMIT 1) THEN
            SET mensaje = CONCAT('Ya existe un departamento que se llama ',pnombre,'.');
            ROLLBACK;
        ELSEIF pidJefeDepartamento IS NOT NULL AND NOT EXISTS( SELECT id FROM Usuarios WHERE id = pidJefeDepartamento LIMIT 1) THEN
            SET mensaje = CONCAT('No existe el jefe de departamento con ID=',pidJefeDepartamento,'.');
            ROLLBACK;
		ELSE    
            SET nid = (  
                SELECT COALESCE(MAX(idDepartamento),0)+1 
                FROM    Departamentos);
            INSERT INTO Departamentos 
                (idDepartamento, idJefeDepartamento, nombre)
            VALUES (nid, pidJefeDepartamento, pnombre);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;
        END IF;            
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_departamento`(
    pidDepartamento SMALLINT UNSIGNED)
BEGIN
    DECLARE nid INT  UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idDepartamento FROM Carreras WHERE idDepartamento = pidDepartamento LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe una carrera asociada al departamento.';
        ROLLBACK;
    ELSE
        DELETE FROM Departamentos
        WHERE idDepartamento = pidDepartamento;
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_modificar_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_modificar_departamento`(
    pidDepartamento SMALLINT UNSIGNED,
	pidJefeDepartamento INT UNSIGNED,
    pnombre VARCHAR(60))
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre del departamento no puede estar vacío.';
	ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT idDepartamento FROM Departamentos WHERE idDepartamento = pidDepartamento LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un departamento con ID=',pidDepartamento,'.');
            ROLLBACK;
		ELSEIF pidJefeDepartamento IS NOT NULL AND NOT EXISTS( SELECT id FROM Usuarios WHERE id = pidJefeDepartamento LIMIT 1) THEN
			SET mensaje = CONCAT('No existe el jefe de departamento con ID=',pidJefeDepartamento,'.');
			ROLLBACK;
        ELSEIF EXISTS( SELECT nombre FROM Departamentos WHERE nombre = pnombre AND idDepartamento != pidDepartamento LIMIT 1) THEN
            SET mensaje = CONCAT('Ya existe un departamento que se llama ',pnombre,'.');
            ROLLBACK;
        ELSE    
            UPDATE Departamentos 
            SET nombre = pnombre, idJefeDepartamento = pidJefeDepartamento
            WHERE idDepartamento = pidDepartamento;
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;            
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_carrera`(
    pidDepartamento SMALLINT UNSIGNED,
	pidDirectorCarrera INT UNSIGNED,
    pnombre VARCHAR(60),
    pplan SMALLINT UNSIGNED)
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(120);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre de la carrera no puede estar vacío.';
    ELSEIF pplan < 1900 OR pplan > 2100 THEN
        SET mensaje = 'El plan debe ser un número entre 1900 y 2100.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT idDepartamento FROM Departamentos WHERE idDepartamento = pidDepartamento LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un departamento con ID=', pidDepartamento,'.');
            ROLLBACK;
        ELSEIF pidDirectorCarrera IS NOT NULL AND NOT EXISTS(SELECT id FROM Usuarios WHERE id = pidDirectorCarrera LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un director con ID=', pidDirectorCarrera,'.');
            ROLLBACK;
        ELSEIF EXISTS(  SELECT nombre FROM Carreras 
                        WHERE nombre = pnombre AND plan=pplan LIMIT 1) THEN
            SET mensaje = CONCAT('Ya existe una carrera del plan ', pplan,' que se llama ', pnombre, '.');
            ROLLBACK;
        ELSE    
            SET nid = (  
                SELECT COALESCE(MAX(idCarrera),0)+1 
                FROM    Carreras);
            INSERT INTO Carreras 
                (idCarrera, idDepartamento, idDirectorCarrera, nombre, plan)
            VALUES (nid, pidDepartamento, pidDirectorCarrera, pnombre, pplan);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;
        END IF;
    END IF;        
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_carrera`(
    pidCarrera SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idCarrera FROM Materias_Carreras WHERE idCarrera = pidCarrera LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe una materia asociada a la carrera.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idCarrera FROM Secciones WHERE idCarrera = pidCarrera LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, la carrera tiene secciones de formularios asociadas.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idCarrera FROM Preguntas WHERE idCarrera = pidCarrera LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, la carrera tiene preguntas asociadas.';
        ROLLBACK;
    ELSE
        DELETE FROM Items_Carreras
        WHERE idCarrera = pidCarrera;
        DELETE FROM Carreras
        WHERE idCarrera = pidCarrera;
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_modificar_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_modificar_carrera`(
    pidCarrera SMALLINT UNSIGNED,
    pidDepartamento SMALLINT UNSIGNED,
	pidDirectorCarrera INT UNSIGNED,
    pnombre VARCHAR(60),
    pplan SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(120);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre de la carrera no puede estar vacío.';
    ELSEIF pplan < 1900 OR pplan > 2100 THEN
        SET mensaje = 'El plan debe ser un número entre 1900 y 2100.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT idCarrera FROM Carreras WHERE idCarrera = pidCarrera LIMIT 1) THEN
            SET mensaje = CONCAT('No existe la carrera con ID=',pidCarrera,'.');
            ROLLBACK;
        ELSEIF NOT EXISTS( SELECT idDepartamento FROM Departamentos WHERE idDepartamento = pidDepartamento LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un departamento con ID=',pidDepartamento,'.');
            ROLLBACK;
        ELSEIF pidDirectorCarrera IS NOT NULL AND NOT EXISTS( SELECT id FROM Usuarios WHERE id = pidDirectorCarrera LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un director con ID=',pidDirectorCarrera,'.');
            ROLLBACK;
        ELSE    
            UPDATE Carreras 
            SET idDepartamento=pidDepartamento, idDirectorCarrera=pidDirectorCarrera, nombre = pnombre, plan = pplan
            WHERE idCarrera = pidCarrera;
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;
    END IF;        
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_carreras`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_carreras`(
    pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idCarrera, idDepartamento, idDirectorCarrera, nombre, plan
    FROM    Carreras
    ORDER BY nombre, plan DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_carreras`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_carreras`()
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Carreras;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_formulario`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_formulario`(
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  idFormulario, nombre, titulo, descripcion, 
            creacion, preguntasAdicionales
    FROM    Formularios
    WHERE   idFormulario = pidFormulario;
END $$

DELIMITER ;



-- DROP PROCEDURE IF EXISTS `esp_respuestas_clave`;


-- DELIMITER $$

-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
-- DELIMITER $$

-- CREATE PROCEDURE `esp_respuestas_clave`(
--     pidClave INT UNSIGNED,
--     pidMateria SMALLINT UNSIGNED,
--     pidCarrera SMALLINT UNSIGNED,
--     pidEncuesta INT UNSIGNED,
--     pidFormulario INT UNSIGNED)
-- BEGIN
--     SELECT  IC.idSeccion, R.idPregunta, P.tipo, R.idDocente, R.opcion, R.texto,
-- 			COUNT(idOpcion) as opciones, IC.importancia
--     FROM    Respuestas R INNER JOIN Preguntas P ON R.idPregunta = P.idPregunta
--             LEFT JOIN Items I ON I.idFormulario = R.idFormulario AND I.idPregunta = R.idPregunta
--             LEFT JOIN Items_Carreras IC ON IC.idCarrera = R.idCarrera AND IC.idFormulario = R.idFormulario AND IC.idPregunta = R.idPregunta
--             LEFT JOIN Docentes_Materias DM ON DM.idDocente = R.idDocente AND DM.idMateria = R.idMateria
-- 			LEFT JOIN opciones O On O.idPregunta = R.idPregunta
--     WHERE   R.idClave = pidClave AND R.idMateria = pidMateria AND R.idCarrera = pidCarrera AND 
--             R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
-- 	GROUP BY R.idPregunta, R.idDocente
--     ORDER BY IC.idSeccion, COALESCE(DM.ordenFormulario,255), COALESCE(IC.posicion, I.posicion);
-- END $$

-- DELIMITER ;



DROP PROCEDURE IF EXISTS `esp_respuestas_pregunta_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_respuestas_pregunta_materia`(
    pidPregunta INT UNSIGNED,
	pidDocente INT UNSIGNED,
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT	R.opcion, IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),O.texto) AS texto, COUNT(idRespuesta) AS cantidad
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.idPregunta = R.idPregunta
			LEFT JOIN opciones O ON O.idPregunta = R.idPregunta AND O.idOpcion = R.opcion
	WHERE   R.idPregunta = pidPregunta AND R.idMateria = pidMateria AND 
			R.idCarrera = pidCarrera AND R.idEncuesta = pidEncuesta AND
			R.idFormulario = pidFormulario AND COALESCE(R.idDocente,0) = COALESCE(pidDocente,0)
	GROUP BY R.opcion
	ORDER BY R.opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_respuesta_pregunta_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_respuesta_pregunta_clave`(
    pidPregunta INT UNSIGNED,
	pidDocente INT UNSIGNED,
	pidClave INT UNSIGNED,
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT	R.opcion, IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),O.texto) AS texto
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.idPregunta = R.idPregunta
			LEFT JOIN opciones O ON O.idPregunta = R.idPregunta AND O.idOpcion = R.opcion
	WHERE   R.idPregunta = pidPregunta AND R.idClave = pidClave AND
			R.idMateria = pidMateria AND R.idCarrera = pidCarrera AND 
			R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario AND 
			COALESCE(R.idDocente,0) = COALESCE(pidDocente,0)
	ORDER BY R.opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_respuestas_pregunta_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_respuestas_pregunta_carrera`(
    pidPregunta INT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT	R.idDocente, R.opcion, IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),O.texto) AS texto, COUNT(idRespuesta) AS cantidad
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.idPregunta = R.idPregunta
			LEFT JOIN opciones O ON O.idPregunta = R.idPregunta AND O.idOpcion = R.opcion
	WHERE   R.idPregunta = pidPregunta AND R.idCarrera = pidCarrera AND 
			R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	GROUP BY R.opcion
	ORDER BY R.opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_respuestas_pregunta_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_respuestas_pregunta_departamento`(
    pidPregunta INT UNSIGNED,
    pidDepartamento SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT	R.idDocente, R.opcion, IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),O.texto) AS texto, COUNT(idRespuesta) AS cantidad
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.idPregunta = R.idPregunta
			INNER JOIN Carreras C ON C.idCarrera = R.idCarrera
			LEFT JOIN opciones O ON O.idPregunta = R.idPregunta AND O.idOpcion = R.opcion
	WHERE   R.idPregunta = pidPregunta AND C.idDepartamento = pidDepartamento AND 
			R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	GROUP BY R.opcion
	ORDER BY R.opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_respuestas_pregunta_facultad`;


DELIMITER $$

CREATE PROCEDURE `esp_respuestas_pregunta_facultad`(
    pidPregunta INT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT	R.idDocente, R.opcion, IF(P.tipo='N',P.limiteInferior+P.paso*(R.opcion-1),O.texto) AS texto, COUNT(idRespuesta) AS cantidad
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.idPregunta = R.idPregunta
			LEFT JOIN opciones O ON O.idPregunta = R.idPregunta AND O.idOpcion = R.opcion
	WHERE   R.idPregunta = pidPregunta AND R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	GROUP BY R.opcion
	ORDER BY R.opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_encuesta`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_encuesta`(
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  idEncuesta, idFormulario, año, cuatrimestre, fechaInicio, fechaFin
    FROM    Encuestas
    WHERE   idEncuesta = pidEncuesta AND idFormulario = pidFormulario;            
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_dame_clave`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  idClave, idMateria, idCarrera, idEncuesta, idFormulario, 
			clave, tipo, generada, utilizada
    FROM    Claves
    WHERE   idClave = pidClave AND idMateria = pidMateria AND
			idCarrera = pidCarrera AND idEncuesta = pidEncuesta AND 
			idFormulario = pidFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_textos_pregunta_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_textos_pregunta_materia`(
    pidPregunta INT UNSIGNED,
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
	SELECT  texto
	FROM    Respuestas R
	WHERE   R.idPregunta = pidPregunta AND R.idMateria = pidMateria AND 
			R.idCarrera = pidCarrera AND R.idEncuesta = pidEncuesta AND
			R.idFormulario = pidFormulario AND texto IS NOT NULL;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_Claves_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_Claves_carrera`(
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  Count(idClave) AS generadas, Count(utilizada) AS utilizadas, 
			MIN(utilizada) AS primerAcceso, MAX(utilizada) AS ultimoAcceso
    FROM    Claves
    WHERE   idCarrera = pidCarrera AND idEncuesta = pidEncuesta AND idFormulario = pidFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_Claves_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_Claves_departamento`(
    pidDepartamento SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  Count(idClave) AS generadas, Count(utilizada) AS utilizadas, 
			MIN(utilizada) AS primerAcceso, MAX(utilizada) AS ultimoAcceso
    FROM    Claves C INNER JOIN Carreras CA ON CA.idCarrera = C.idCarrera
    WHERE   CA.idDepartamento = pidDepartamento AND idEncuesta = pidEncuesta AND idFormulario = pidFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_Claves_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_Claves_materia`(
    pidMateria SMALLINT UNSIGNED,
    pidCarrera SMALLINT UNSIGNED,
    pidEncuesta INT UNSIGNED,
    pidFormulario INT UNSIGNED)
BEGIN
    SELECT  Count(idClave) AS generadas, Count(utilizada) AS utilizadas, 
			MIN(utilizada) AS primerAcceso, MAX(utilizada) AS ultimoAcceso
    FROM    Claves
    WHERE   idMateria = pidMateria AND idCarrera = pidCarrera AND 
            idEncuesta = pidEncuesta AND idFormulario = pidFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_usuario`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_usuario`(
    papellido VARCHAR(40),
    pnombre VARCHAR(40))
BEGIN
    DECLARE nid INT  UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(papellido,'') = '' THEN
        SET mensaje = 'El apellido no puede ser vacío.';
    ELSE
        START TRANSACTION;
		SET nid = (  
			SELECT COALESCE(MAX(id),0)+1 
			FROM    Usuarios);
		INSERT INTO Usuarios
			(nid, apellido, nombre)
		VALUES (nid, papellido, pnombre);
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET mensaje = nid;
			COMMIT;
		END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_usuarios`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_usuarios` (
	pnombre VARCHAR(40))
BEGIN
	IF COALESCE(pnombre,'') != '' THEN
		SELECT	id, apellido, nombre
		FROM	Usuarios
		WHERE	CONCAT(apellido,' ',nombre,' ',apellido) like CONCAT('%',pnombre,'%');
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_carreras`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_carreras`(
	pnombre VARCHAR(60))
BEGIN
	IF COALESCE(pnombre,'') != '' THEN
		SELECT	idCarrera, idDepartamento, idDirectorCarrera, nombre, plan 
		FROM	Carreras
		WHERE	nombre like CONCAT('%',pnombre,'%')
		ORDER BY nombre, plan DESC;
	END IF;
END $$


DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_materias`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_materias`(
	pnombre VARCHAR(60))
BEGIN
	IF COALESCE(pnombre,'') != '' THEN
		SELECT	idMateria, nombre, codigo, alumnos
		FROM	Materias
		WHERE	nombre like CONCAT('%',pnombre,'%');
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_asociar_materia_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_asociar_materia_carrera`(
    pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF NOT EXISTS(SELECT idMateria FROM Materias WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se encuestra una materia con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(SELECT idCarrera FROM Carreras WHERE idCarrera = pidCarrera LIMIT 1) THEN
        SET mensaje = 'No se encuestra una carrera con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(  SELECT idCarrera FROM Materias_Carreras 
                        WHERE  idCarrera = pidCarrera AND idMateria = pidMateria LIMIT 1) THEN
        INSERT INTO Materias_Carreras
            (idMateria, idCarrera)
        VALUES (pidMateria, pidCarrera);
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    ELSE
        SET mensaje = 'ok';
        COMMIT;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_docentes_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_docentes_materia`(
	pidMateria SMALLINT UNSIGNED)
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Docentes_Materias
	WHERE	idMateria = pidMateria;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_docentes_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_docentes_materia`(
	pidMateria SMALLINT UNSIGNED,
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  U.id, U.nombre, U.apellido, U.active, U.last_login, DM.tipoAcceso, DM.ordenFormulario, DM.cargo
    FROM    Usuarios U INNER JOIN Docentes_Materias DM ON U.id = DM.idDocente
	WHERE	DM.idMateria = ?
    ORDER BY DM.ordenFormulario, U.apellido, U.nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pidMateria;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_asociar_docente_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_asociar_docente_materia`(
    pidDocente INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pordenFormulario TINYINT UNSIGNED,
	pcargo VARCHAR(40))
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF NOT EXISTS(SELECT idMateria FROM Materias WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se encuestra una materia con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(SELECT id FROM Usuarios WHERE id = pidDocente LIMIT 1) THEN
        SET mensaje = 'No se encuestra una docente con ID dado.';
        ROLLBACK;
    ELSEIF EXISTS(	SELECT idDocente FROM Docentes_Materias
					WHERE  idDocente = pidDocente AND idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'Ya existe una asociación entre el docente y la materia dados.';
        ROLLBACK;
	ELSE
        INSERT INTO Docentes_Materias
            (idDocente, idMateria, ordenFormulario, cargo)
        VALUES (pidDocente, pidMateria, pordenFormulario, pcargo);
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_desasociar_docente_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_desasociar_docente_materia`(
    pidDocente INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
	DELETE FROM Docentes_Materias
	WHERE idDocente = pidDocente AND idMateria = pidMateria;
	IF err THEN
		SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
		ROLLBACK;
	ELSE 
		SET mensaje = 'ok';
		COMMIT;
	END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_materia`;


DELIMITER $$


CREATE PROCEDURE `esp_alta_materia`(
    pnombre VARCHAR(60),
    pcodigo CHAR(5),
	palumnos SMALLINT UNSIGNED)
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre de la materia no puede estar vacío.';
	ELSEIF COALESCE(pcodigo,'')='' THEN
        SET mensaje = 'El código no puede ser nulo.';
	ELSE
        START TRANSACTION;
        IF EXISTS( SELECT codigo FROM Materias WHERE codigo = pcodigo LIMIT 1) THEN
            SET mensaje = CONCAT('Ya existe una materia con código ',pcodigo,'.');
            ROLLBACK;
        ELSE
            SET nid = (  
                SELECT COALESCE(MAX(idMateria),0)+1 
                FROM    Materias );
            INSERT INTO Materias 
                (idMateria, nombre, codigo, alumnos)
            VALUES (nid, pnombre, pcodigo, palumnos);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;



DROP PROCEDURE IF EXISTS `esp_modificar_materia`;


DELIMITER $$


CREATE PROCEDURE `esp_modificar_materia`(
	pidMateria SMALLINT UNSIGNED,
    pnombre VARCHAR(60),
    pcodigo CHAR(5),
	palumnos SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'')='' THEN
        SET mensaje = 'El nombre de la materia no puede estar vacío.';
    ELSEIF COALESCE(pcodigo,'')='' THEN
        SET mensaje = 'El código no puede ser nulo.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT idMateria FROM Materias WHERE idMateria = pidMateria LIMIT 1) THEN
            SET mensaje = CONCAT('No existe la materia con ID=',pidMateria,'.');
            ROLLBACK;
        ELSEIF EXISTS( SELECT codigo FROM Materias WHERE codigo = pcodigo AND idMateria != pidMateria LIMIT 1) THEN
            SET mensaje = CONCAT('Ya existe una materia con código ',pcodigo,'.');
            ROLLBACK;
        ELSE
            UPDATE Materias 
			SET nombre = pnombre, codigo = pcodigo, alumnos = palumnos
			WHERE idMateria = pidMateria;
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_materia`(
    pidMateria SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idMateria FROM Materias_Carreras WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar la materia ya que esta relacionada con una carrera.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idMateria FROM Devoluciones WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe al menos una devolucion asociada a la materia.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idMateria FROM alumnos_Materias WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe un alumno asociado a la materia.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idMateria FROM Docentes_Materias WHERE idMateria = pidMateria LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe un docente asociado a la materia.';
        ROLLBACK;
    ELSE
        DELETE FROM Materias
        WHERE idMateria = pidMateria;
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_modificar_usuario`;


DELIMITER $$

CREATE PROCEDURE `esp_modificar_usuario`(
	pid INT UNSIGNED,
    papellido VARCHAR(40),
    pnombre VARCHAR(40))
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(papellido,'') = '' THEN
        SET mensaje = 'El apellido no puede ser vacío.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT id FROM Usuarios WHERE id = pid LIMIT 1) THEN
            SET mensaje = CONCAT('No existe registro del usuario con ID=',pid,'.');
            ROLLBACK;
        ELSE
            UPDATE Usuarios
			SET apellido = papellido, nombre = pnombre
			WHERE id = pid;
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_estado_usuario`;


DELIMITER $$

CREATE PROCEDURE `esp_estado_usuario`(
	pid INT UNSIGNED,
	pEstado CHAR(1))
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF NOT pEstado IN ('A','I') THEN
        SET mensaje = 'El estado es inválido.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT id FROM Usuarios WHERE id = pid LIMIT 1) THEN
            SET mensaje = CONCAT('No existe registro del usuario con ID=',pid,'.');
            ROLLBACK;
        ELSE
            UPDATE Usuarios
			SET Estado = pEstado
			WHERE id = pid;
			IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_asignar_cantidad_alumnos`;


DELIMITER $$

CREATE PROCEDURE `esp_asignar_cantidad_alumnos`(
    pidMateria SMALLINT UNSIGNED,
	palumnos SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
	START TRANSACTION;
	IF NOT EXISTS(SELECT idMateria FROM Materias WHERE idMateria = pidMateria LIMIT 1) THEN
		SET mensaje = CONCAT('No existe registro de la  materia con ID=',pidMateria,'.');
		ROLLBACK;
	ELSE
		UPDATE Materias
		SET alumnos = palumnos
		WHERE idMateria = pidMateria;
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET mensaje = 'ok';
			COMMIT;
		END IF;
	END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_encuestas`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_encuestas`(
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idEncuesta, idFormulario, año, cuatrimestre, fechaInicio, fechaFin
    FROM    Encuestas
    ORDER BY año DESC, cuatrimestre DESC, fechaInicio DESC, fechaFin DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$


DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_encuestas`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_encuestas`()
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Encuestas;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_encuesta`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_encuesta`(
    pidFormulario INT UNSIGNED,
    paño SMALLINT UNSIGNED, 
    pcuatrimestre TINYINT UNSIGNED)
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF paño < 1900 OR paño > 2100 THEN
        SET mensaje = 'El año ingresado es incorrecto.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(  SELECT idFormulario FROM Formularios    
                        WHERE idFormulario = pidFormulario LIMIT 1) THEN
            SET mensaje = CONCAT('No se encontró el formulario con ID=',pidFormulario,'.');
            ROLLBACK;
        ELSE
            SET nid = (  
                SELECT COALESCE(MAX(idEncuesta),0)+1 
                FROM    Encuestas
                WHERE   idFormulario = pidFormulario);
            INSERT INTO Encuestas 
                (idEncuesta, idFormulario, año, cuatrimestre, fechaInicio, fechaFin)
            VALUES (nid, pidFormulario, paño, pcuatrimestre, NOW(), NULL);
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_Claves_encuesta_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_Claves_encuesta_materia`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
    SET @qry = '
    SELECT  idClave, idMateria, idCarrera, idEncuesta, idFormulario, 
			clave, tipo, generada, utilizada
    FROM    Claves
	WHERE	idMateria = ? AND idCarrera = ? AND idEncuesta = ? AND idFormulario = ?
    ORDER BY generada DESC, utilizada DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pidMateria;
	SET @d = pidCarrera;
	SET @e = pidEncuesta;
	SET @f = pidFormulario;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @d, @e, @f, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_formularios`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_formularios`(
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
	SET @qry = '
    SELECT  idFormulario, nombre, titulo, descripcion, creacion, 
            preguntasAdicionales
    FROM    Formularios
    ORDER BY nombre
	LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$


DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_formularios`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_formularios`()
BEGIN
    SELECT  COUNT(*) AS cantidad
    FROM    Formularios;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_finalizar_encuesta`;


DELIMITER $$

CREATE PROCEDURE `esp_finalizar_encuesta` (
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;   
	START TRANSACTION;
	IF NOT EXISTS(SELECT idEncuesta FROM Encuestas WHERE idEncuesta = pidEncuesta AND idFormulario = pidFormulario AND fechaFin IS NULL LIMIT 1) THEN
		SET mensaje = CONCAT('No se encontró la encuesta con ID dado, o la misma ya esta finalizada.');
		ROLLBACK;
	ELSE
		UPDATE	Encuestas
		SET		fechaFin = NOW()
		WHERE	idEncuesta = pidEncuesta AND 
				idFormulario = pidFormulario;
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET mensaje = 'ok';
			COMMIT;
		END IF;
	END IF;
	SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_global`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	OUT indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*importancia) AS RI, SUM(Alternativa*importancia) AS AI, SUM(importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.ordenInverso='S', IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) - opcion + 1, opcion) AS Respuesta, 
					IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) AS Alternativa, 
					COALESCE(IC.importancia,1) AS importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.idPregunta = R.idPregunta
					INNER JOIN Items I ON
						I.idPregunta = P.idPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.idCarrera = R.idCarrera AND IC.idSeccion = I.idSeccion AND
						IC.idFormulario = I.idFormulario AND IC.idPregunta = I.idPregunta
					LEFT JOIN opciones O ON
						O.idPregunta = P.idPregunta
			WHERE	R.idClave = pidClave AND R.idMateria = pidMateria AND 
					R.idCarrera = pidCarrera AND R.idEncuesta = pidEncuesta AND 
					R.idFormulario = pidFormulario AND R.opcion IS NOT NULL
			GROUP BY O.idPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_seccion`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED,
	OUT indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*importancia) AS RI, SUM(Alternativa*importancia) AS AI, SUM(importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.ordenInverso='S', IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) - opcion + 1, opcion) AS Respuesta, 
					IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) AS Alternativa, 
					COALESCE(IC.importancia,1) AS importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.idPregunta = R.idPregunta
					INNER JOIN Items I ON
						I.idPregunta = P.idPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.idCarrera = R.idCarrera AND IC.idSeccion = I.idSeccion AND
						IC.idFormulario = I.idFormulario AND IC.idPregunta = I.idPregunta
					LEFT JOIN opciones O ON
						O.idPregunta = P.idPregunta
			WHERE	R.idClave = pidClave AND R.idMateria = pidMateria AND 
					R.idCarrera = pidCarrera AND R.idEncuesta = pidEncuesta AND 
					R.idFormulario = pidFormulario AND IC.idSeccion = pidSeccion AND 
					R.opcion IS NOT NULL
			GROUP BY O.idPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_docente`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED,
	pidDocente INT UNSIGNED,
	OUT indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*importancia) AS RI, SUM(Alternativa*importancia) AS AI, SUM(importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.ordenInverso='S', IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) - opcion + 1, opcion) AS Respuesta, 
					IF(P.tipo='N',(limiteSuperior-limiteInferior)/paso+1,COUNT(idOpcion)) AS Alternativa, 
					COALESCE(IC.importancia,1) AS importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.idPregunta = R.idPregunta
					INNER JOIN Items I ON
						I.idPregunta = P.idPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.idCarrera = R.idCarrera AND IC.idSeccion = I.idSeccion AND
						IC.idFormulario = I.idFormulario AND IC.idPregunta = I.idPregunta
					LEFT JOIN opciones O ON
						O.idPregunta = P.idPregunta
			WHERE	R.idClave = pidClave AND R.idMateria = pidMateria AND 
					R.idCarrera = pidCarrera AND R.idEncuesta = pidEncuesta AND 
					R.idFormulario = pidFormulario AND IC.idSeccion = pidSeccion AND 
					R.opcion IS NOT NULL AND R.idDocente = pidDocente
			GROUP BY O.idPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_docente_clave`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED,
	pidDocente INT UNSIGNED)
BEGIN
	DECLARE indice FLOAT;
	CALL esp_indice_docente(pidClave, pidMateria, pidCarrera, 
							pidEncuesta, pidFormulario, pidSeccion,
							pidDocente, indice);
	SELECT ROUND(indice,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_docente_materia`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED,
	pidDocente INT UNSIGNED)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pidClave INT  UNSIGNED;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT idClave 
		FROM Claves
		WHERE	idMateria = pidMateria AND idCarrera = pidCarrera AND
				idEncuesta = pidEncuesta AND idFormulario = pidFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pidClave;
		IF NOT done THEN
			CALL esp_indice_docente(pidClave, pidMateria, pidCarrera, 
									pidEncuesta, pidFormulario, pidSeccion, 
									pidDocente, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_global_carrera`(
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pidClave INT  UNSIGNED;
	DECLARE pidMateria INT  UNSIGNED;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT	idClave, idMateria
		FROM	Claves
		WHERE	idCarrera = pidCarrera AND
				idEncuesta = pidEncuesta AND idFormulario = pidFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de cada materia tomar el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pidClave, pidMateria;
		IF NOT done THEN
			CALL esp_indice_global(pidClave, pidMateria, pidCarrera, pidEncuesta, pidFormulario, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_global_materia`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pidClave INT  UNSIGNED;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT idClave 
		FROM Claves
		WHERE	idMateria = pidMateria AND idCarrera = pidCarrera AND
				idEncuesta = pidEncuesta AND idFormulario = pidFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pidClave;
		IF NOT done THEN
			CALL esp_indice_global(pidClave, pidMateria, pidCarrera, pidEncuesta, pidFormulario, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_global_clave`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	DECLARE indice FLOAT;
	CALL esp_indice_global(	pidClave, pidMateria, pidCarrera, 
							pidEncuesta, pidFormulario, indice);
	SELECT ROUND(indice,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_seccion_carrera`(
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pidClave INT  UNSIGNED;
	DECLARE pidMateria SMALLINT UNSIGNED;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT idClave, idMateria
		FROM Claves
		WHERE	idCarrera = pidCarrera AND
				idEncuesta = pidEncuesta AND idFormulario = pidFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pidClave, pidMateria;
		IF NOT done THEN
			CALL esp_indice_seccion(pidClave, pidMateria, pidCarrera, 
									pidEncuesta, pidFormulario, pidSeccion, 
									indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_seccion_clave`(
	pidClave INT UNSIGNED,
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED)
BEGIN
	DECLARE indice FLOAT;
	CALL esp_indice_seccion(pidClave, pidMateria, pidCarrera, 
							pidEncuesta, pidFormulario, pidSeccion,
							indice);
	SELECT ROUND(indice,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_materia`;


DELIMITER $$

CREATE PROCEDURE `esp_indice_seccion_materia`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidSeccion INT UNSIGNED)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pidClave INT  UNSIGNED;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT idClave 
		FROM Claves
		WHERE	idMateria = pidMateria AND idCarrera = pidCarrera AND
				idEncuesta = pidEncuesta AND idFormulario = pidFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pidClave;
		IF NOT done THEN
			CALL esp_indice_seccion(pidClave, pidMateria, pidCarrera, 
									pidEncuesta, pidFormulario, pidSeccion, 
									indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_docentes_encuesta`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_docentes_encuesta`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	SELECT DISTINCT P.id, P.apellido, P.nombre
	FROM 	Respuestas R 
			INNER JOIN Usuarios P ON P.id = R.idDocente
			LEFT JOIN Docentes_Materias DM ON DM.idDocente = R.idDocente AND DM.idMateria = R.idMateria
	WHERE   R.idMateria = pidMateria AND R.idCarrera = pidCarrera AND 
			R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	ORDER BY DM.ordenFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_docentes_clave`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_docentes_clave`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
	pidEncuesta INT UNSIGNED,
	pidFormulario INT UNSIGNED)
BEGIN
	SELECT DISTINCT P.id, P.apellido, P.nombre
	FROM 	Respuestas R 
			INNER JOIN Usuarios P ON P.id = R.idDocente
			LEFT JOIN Docentes_Materias DM ON DM.idDocente = R.idDocente AND DM.idMateria = R.idMateria
	WHERE   R.idMateria = pidMateria AND R.idCarrera = pidCarrera AND 
			R.idEncuesta = pidEncuesta AND R.idFormulario = pidFormulario
	ORDER BY DM.ordenFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_materias_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_materias_carrera`(
	pidCarrera SMALLINT UNSIGNED,
	pnombre VARCHAR(60))
BEGIN
	SELECT	M.idMateria, M.nombre, M.codigo, M.alumnos
	FROM	Materias M INNER JOIN Materias_Carreras MC ON 
			M.idMateria = MC.idMateria
	WHERE	MC.idCarrera = pidCarrera AND M.nombre like CONCAT('%',pnombre,'%');
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_departamentos`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_departamentos`(
	pnombre VARCHAR(60))
BEGIN
	IF COALESCE(pnombre,'') != '' THEN
		SELECT	idDepartamento, idJefeDepartamento, nombre
		FROM	Departamentos
		WHERE	nombre like CONCAT('%',pnombre,'%');
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_usuario`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_usuario`(
    pid INT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idDocente FROM Respuestas WHERE idDocente = pid LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar el docente porque hay respuestas de alumnos referidas a el.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idDocente FROM Docentes_Materias WHERE idDocente = pid LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar porque el docente esta asociado con al menos una materia.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idJefeDepartamento FROM Departamentos WHERE idJefeDepartamento = pid LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe un departamento que lo tiene como Jefe de Departamento.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idDirectorCarrera FROM Carreras WHERE idDirectorCarrera = pid LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existe una carrera que lo tiene como Director de Carrera.';
        ROLLBACK;
    ELSE
        DELETE FROM Usuarios_Grupos
        WHERE id_usuario = pid;
        DELETE FROM Usuarios
        WHERE id = pid;
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_formularios`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_formularios`(
	pnombre VARCHAR(60))
BEGIN
	IF COALESCE(pnombre,'') != '' THEN
		SELECT  idFormulario, nombre, titulo, descripcion, creacion, 
				preguntasAdicionales
		FROM    Formularios
		WHERE	nombre like CONCAT('%',pnombre,'%')
		ORDER BY nombre;
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_preguntas`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_preguntas`(
	ptexto VARCHAR(200))
BEGIN
	IF COALESCE(ptexto,'') != '' THEN
		SELECT  idPregunta, idCarrera, texto, descripcion, creacion,
				tipo, obligatoria, ordenInverso, limiteInferior, limiteSuperior,
				paso, unidad
		FROM    Preguntas
		WHERE	texto like CONCAT('%',ptexto,'%')
		ORDER BY texto;
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_formulario`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_formulario`(
    pidFormulario INT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idEncuesta FROM Encuestas WHERE idFormulario = pidFormulario LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, este formulario se usó en una encuesta.';
        ROLLBACK;
    ELSE
		DELETE FROM Items_Carreras
		WHERE idFormulario = pidFormulario;
		DELETE FROM Items
		WHERE idFormulario = pidFormulario;
		DELETE FROM Secciones
		WHERE idFormulario = pidFormulario;
		DELETE FROM Formularios
		WHERE idFormulario = pidFormulario;
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE
			SET mensaje = 'ok';
			COMMIT;
		END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_formulario`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_formulario`(
    pnombre VARCHAR(60),
    ptitulo VARCHAR(200),
    pdescripcion VARCHAR(200),
    ppreguntasAdicionales TINYINT UNSIGNED)
BEGIN
    DECLARE nid INT  UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pnombre,'') = '' THEN
        SET mensaje = 'El nombre del formulacio no puede ser nulo';
    ELSE
        START TRANSACTION;    
        SET nid = (
            SELECT COALESCE(MAX(idFormulario),0)+1 
            FROM    Formularios);
        INSERT INTO Formularios 
            (idFormulario, nombre, titulo, descripcion, creacion, preguntasAdicionales)
        VALUES (nid, pnombre, ptitulo, pdescripcion, NOW(), ppreguntasAdicionales);
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = nid;
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_seccion`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_seccion`(
    pidFormulario INT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
    ptexto VARCHAR(200),
    pdescripcion VARCHAR(200),
    ptipo CHAR(1))
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(ptexto,'') = '' THEN
        SET mensaje = 'El texto de la sección no puede ser vacío.';
    ELSEIF NOT ptipo IN ('N', 'D') THEN
        SET mensaje = 'El tipo de sección es incorrecto.';
    ELSE
        START TRANSACTION;    
        IF NOT EXISTS(SELECT idFormulario FROM Formularios WHERE idFormulario = pidFormulario LIMIT 1) THEN
            SET mensaje = CONCAT('No existe un formulario con el ID=',pidFormulario,'.');
            ROLLBACK;
        ELSEIF pidCarrera IS NOT NULL AND NOT EXISTS(SELECT idCarrera FROM Carreras WHERE idCarrera = pidCarrera LIMIT 1) THEN
            SET mensaje = CONCAT('No existe la carrera con ID=',pidCarrera,'.');
            ROLLBACK;
        ELSE
            SET nid = (  
                SELECT COALESCE(MAX(idSeccion),0)+1 
                FROM    Secciones
                WHERE   idFormulario = pidFormulario);
            INSERT INTO Secciones 
                (idSeccion, idFormulario, idCarrera, texto, descripcion, tipo)
            VALUES (nid, pidFormulario, pidCarrera, ptexto, pdescripcion, ptipo);    
            IF err THEN
                SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET mensaje = nid;
                COMMIT;
            END IF;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_item`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_item`(
    pidSeccion INT UNSIGNED,
	pidFormulario INT UNSIGNED,
	pidPregunta INT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED,
    pposicion TINYINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
	START TRANSACTION;    
	IF NOT EXISTS(SELECT idSeccion FROM Secciones WHERE idSeccion = pidSeccion AND idFormulario = pidFormulario LIMIT 1) THEN
		SET mensaje = CONCAT('No existe una sección con ID=',pidSeccion,'.');
		ROLLBACK;
	ELSEIF NOT EXISTS(SELECT idPregunta FROM Preguntas WHERE idPregunta = pidPregunta LIMIT 1) THEN
		SET mensaje = CONCAT('No existe la pregunta con ID=',pidPregunta,'.');
		ROLLBACK;
	ELSEIF pidCarrera IS NOT NULL AND NOT EXISTS(SELECT idCarrera FROM Carreras WHERE idCarrera = pidCarrera LIMIT 1) THEN
		SET mensaje = CONCAT('No existe la carrera con ID=',pidCarrera,'.');
		ROLLBACK;
	ELSE
		INSERT INTO Items
			(idSeccion, idFormulario, idPregunta, idCarrera, posicion)
		VALUES (pidSeccion, pidFormulario, pidPregunta, pidCarrera, pposicion);
		IF err THEN
			SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET mensaje = 'ok';
			COMMIT;
		END IF;
	END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_preguntas`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_preguntas`(
	pPagInicio INT UNSIGNED,
    pPagLongitud INT UNSIGNED)
BEGIN
	SET @qry = '
    SELECT  idPregunta, idCarrera, texto, descripcion, creacion, tipo,
			obligatoria, ordenInverso, limiteInferior, limiteSuperior,
			paso, unidad
    FROM    Preguntas
    ORDER BY creacion DESC
	LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_preguntas`;


DELIMITER $$

CREATE PROCEDURE `esp_cantidad_preguntas`()
BEGIN
    SELECT COUNT(*) AS cantidad
    FROM Preguntas;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_pregunta`;


DELIMITER $$

CREATE PROCEDURE `esp_baja_pregunta`(
    pidPregunta INT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT idPregunta FROM Items WHERE idPregunta = pidPregunta LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, la pregunta esta asociada a un formulario.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT idRespuesta FROM Respuestas WHERE idPregunta = pidPregunta LIMIT 1) THEN
        SET mensaje = 'No se puede eliminar, existen respuestas asociadas a la pregunta.';
        ROLLBACK;
    ELSE
        DELETE FROM opciones
        WHERE idPregunta = pidPregunta;
		DELETE FROM Preguntas
		WHERE idPregunta = pidPregunta;
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
			SET mensaje = 'ok';
			COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_pregunta`;


DELIMITER $$

CREATE PROCEDURE `esp_alta_pregunta`(
    pidCarrera SMALLINT UNSIGNED,
    ptexto VARCHAR(200),
    pdescripcion VARCHAR(200),
    ptipo CHAR(1),
    pobligatoria CHAR(1),
    pordenInverso CHAR(1),
    plimiteInferior DECIMAL(7,2),
    plimiteSuperior DECIMAL(7,2),
    ppaso DECIMAL(7,2),
    punidad VARCHAR(10))
BEGIN
    DECLARE nid INT  UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(ptexto,'') = '' THEN
        SET mensaje = 'El texto de la pregunta no puede ser vacío';
    ELSEIF NOT ptipo IN ('S', 'M', 'N', 'T', 'X') THEN
        SET mensaje = 'El tipo de pregunta es incorrecto.';
    ELSEIF NOT pobligatoria IN ('S', 'N') THEN
        SET mensaje = 'El campo obligatoria es incorrecto.';
    ELSEIF NOT pordenInverso IN ('S', 'N') THEN
        SET mensaje = 'El campo orden inverso es incorrecto.';
    ELSE    
        START TRANSACTION;    
        SET nid = (  
            SELECT COALESCE(MAX(idPregunta),0)+1 
            FROM    Preguntas);
        INSERT INTO Preguntas 
            (idPregunta, idCarrera, texto, descripcion,
            creacion, tipo, obligatoria, ordenInverso, 
            limiteInferior, limiteSuperior, paso, unidad)
        VALUES (nid, pidCarrera, ptexto, pdescripcion,
            NOW(), ptipo, pobligatoria, pordenInverso, 
            plimiteInferior, plimiteSuperior, ppaso, punidad);
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = nid;
            COMMIT;
        END IF;
    END IF;        
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_opcion`;


DELIMITER $$


CREATE PROCEDURE `esp_alta_opcion`(
    pidPregunta INT UNSIGNED,
    ptexto VARCHAR(40))
BEGIN
    DECLARE nid SMALLINT UNSIGNED;
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(ptexto,'') = '' THEN
        SET mensaje = 'La etiqueta de la opción no puede ser vacía.';
    ELSE        
        START TRANSACTION;
        SET nid = (  
            SELECT COALESCE(MAX(idOpcion),0)+1 
            FROM    opciones
            WHERE   idPregunta = pidPregunta);
        INSERT INTO opciones 
            (idOpcion, idPregunta, texto)
        VALUES (nid, pidPregunta, ptexto);
        IF err THEN
            SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET mensaje = nid;
            COMMIT;
        END IF;
    END IF;
    SELECT mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_desasociar_materia_carrera`;


DELIMITER $$

CREATE PROCEDURE `esp_desasociar_materia_carrera`(
	pidMateria SMALLINT UNSIGNED,
	pidCarrera SMALLINT UNSIGNED)
BEGIN
    DECLARE mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
	DELETE FROM Materias_Carreras
	WHERE idMateria = pidMateria AND idCarrera = pidCarrera;
	IF err THEN
		SET mensaje = 'Error inesperado al intentar acceder a la base de datos.';
		ROLLBACK;
	ELSE 
		SET mensaje = 'ok';
		COMMIT;
	END IF;
    SELECT mensaje;
END $$

DELIMITER ;
















DROP PROCEDURE IF EXISTS `esp_listar_materias_docente`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_materias_docente`(
	pid INT UNSIGNED)
BEGIN
    SELECT  M.idMateria, M.nombre, M.codigo, M.alumnos
    FROM    Materias M 
			INNER JOIN Docentes_Materias DM ON M.idMateria = DM.idMateria
	WHERE	DM.idDocente = pid
    ORDER BY M.nombre;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias_director`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_materias_director`(
	pid INT UNSIGNED)
BEGIN
    SELECT  DISTINCT M.idMateria, M.nombre, M.codigo, M.alumnos
    FROM    Materias M 
			INNER JOIN Materias_Carreras MC ON MC.idMateria = MC.idMateria
			INNER JOIN Carreras C ON C.idCarrera = MC.idCarrera
	WHERE	C.idDirectorCarrera = pid
    ORDER BY M.nombre;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias_jefe_departamento`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_materias_jefe_departamento`(
	pid INT UNSIGNED)
BEGIN
    SELECT  DISTINCT M.idMateria, M.nombre, M.codigo, M.alumnos
    FROM    Materias M 
			INNER JOIN Materias_Carreras MC ON MC.idMateria = MC.idMateria
			INNER JOIN Carreras C ON C.idCarrera = MC.idCarrera
			INNER JOIN Departamentos D ON D.idDepartamento = C.idDepartamento
	WHERE	D.idJefeDepartamento = pid
    ORDER BY M.nombre;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_carreras_docente`;


DELIMITER $$

CREATE PROCEDURE `esp_listar_carreras_docente`(
	pid INT UNSIGNED)
BEGIN
    SELECT  DISTINCT C.idCarrera, C.idDepartamento, C.idDirectorCarrera, C.nombre, C.plan
    FROM    Carreras C 
			INNER JOIN Materias_Carreras MC ON MC.idCarrera = C.idCarrera
			INNER JOIN Docentes_Materias DM ON MC.idMateria = DM.idMateria
	WHERE	DM.idDocente = pid
    ORDER BY C.nombre;
END $$















DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_encuestas`;


DELIMITER $$

CREATE PROCEDURE `esp_buscar_encuestas`(
	paño SMALLINT UNSIGNED)
BEGIN
    SELECT  idEncuesta, idFormulario, año, cuatrimestre, fechaInicio, fechaFin
    FROM    Encuestas
	WHERE	año = paño
    ORDER BY cuatrimestre DESC, fechaInicio DESC, fechaFin DESC;
END $$

DELIMITER ;