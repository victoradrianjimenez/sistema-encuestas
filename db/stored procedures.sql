
UPDATE Items_Carreras
SET Tamaño = 2
WHERE IdCarrera >= 0 AND IdFormulario = 1 AND IdPregunta = 39;


UPDATE Items
SET Tamaño = 2
WHERE IdCarrera >= 0 AND IdFormulario = 1 AND IdPregunta = 39;



DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_departamentos`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_departamentos`(
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdDepartamento, IdJefeDepartamento, Nombre
    FROM Departamentos
    ORDER BY Nombre
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_departamentos`()
BEGIN
    SELECT COUNT(*) AS Cantidad
    FROM Departamentos;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_persona`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_persona`(
    pIdPersona INT)
BEGIN
    SELECT IdPersona, Apellido, Nombre, Usuario, Email, Contraseña, UltimoAcceso, Estado
    FROM Personas
    WHERE IdPersona = pIdPersona;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_validar_usuario`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_validar_usuario`(
    pUsuario VARCHAR(40),
    pContraseña CHAR(64))
BEGIN
    SELECT IdPersona, Apellido, Nombre, Usuario, Email, Contraseña, UltimoAcceso, Estado
    FROM Personas
    WHERE Usuario = pUsuario AND Contraseña = pContraseña AND Estado != 'I'
    LIMIT 1;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_carreras_departamento`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_carreras_departamento`(
    pIdDepartamento SMALLINT,
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdDepartamento, IdCarrera, Nombre, Plan
    FROM    Carreras
    WHERE   IdDepartamento = ?
    ORDER BY Nombre, Plan DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @c = pIdDepartamento;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_carreras_departamento`;


DELIMITER $$


CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_carreras_departamento`(
    pIdDepartamento SMALLINT)
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Carreras
    WHERE   IdDepartamento = pIdDepartamento;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_departamento`;


DELIMITER $$


CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_departamento`(
    pIdDepartamento SMALLINT)
BEGIN
    SELECT IdDepartamento, IdJefeDepartamento, Nombre
    FROM Departamentos
    WHERE IdDepartamento = pIdDepartamento;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_clave`;


DELIMITER $$


CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_buscar_clave`(
    pClave CHAR(16))
BEGIN
    -- busca una clave en las encuestas que no finalizaron
    SELECT  IdClave, IdMateria, IdClave, C.IdEncuesta, C.IdFormulario,
            Clave, Tipo, Generada, Utilizada
    FROM    Claves C INNER JOIN Encuestas E ON C.IdEncuesta = E.IdEncuesta AND C.IdFormulario = E.IdFormulario
    WHERE   Clave = pClave AND FechaFin IS NOT NULL
    LIMIT   1;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_clave`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_clave`(
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT,
    pTipo CHAR(1))
BEGIN
    DECLARE id INT;
    DECLARE clave CHAR(12);
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF NOT pTipo IN ('E','R','O') THEN
        SET Mensaje = 'El tipo de clave es incorrecto.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(  SELECT  IdEncuesta FROM Encuestas 
                        WHERE   IdEncuesta = pIdEncuesta AND 
                                IdFormulario = IdFormulario AND FechaFin IS NOT NULL LIMIT 1) THEN
            SET Mensaje = 'No se encontró la Encuesta correspondiente y la misma ya concluyó.';
            ROLLBACK;
        ELSE
            SET id = (  
                SELECT COALESCE(MAX(IdClave),0)+1 
                FROM    Claves
                WHERE   IdMateria = pIdMateria AND
                        IdCarrera = pIdCarrera AND
                        IdEncuesta = pIdEncuesta AND
                        IdFormulario = pIdFormulario);            
            SET clave = SUBSTRING(MD5(CONCAT(
                id,pIdMateria,pIdCarrera,pIdEncuesta,pIdFormulario,pTipo,NOW())),1,12);
            INSERT INTO Claves 
                (IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario, Clave, Tipo, Generada, Utilizada)
            VALUES (id, pIdMateria, pIdCarrera, pIdEncuesta, pIdFormulario, UPPER(clave), pTipo, NOW(), NULL);
            IF err THEN
                SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET Mensaje = UPPER(clave);
                COMMIT;
            END IF;            
        END IF;
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_secciones_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_secciones_carrera`(
    pIdFormulario INT,
    pIdCarrera SMALLINT)
BEGIN
    SELECT  IdSeccion, IdFormulario, IdCarrera, Texto, Descripcion, Tipo
    FROM    Secciones
    WHERE   IdFormulario = pIdFormulario AND (IdCarrera IS NULL OR IdCarrera = pIdCarrera)
    ORDER BY IdSeccion;
    -- se ordena por ID, es decir por orden de creacion (por lo tanto las de la carrera van al final)    
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_items_seccion`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_items_seccion`(
    pIdSeccion INT,
    pIdFormulario INT)
BEGIN
    DECLARE pIdCarrera INT;
    START TRANSACTION;
    SET pIdCarrera = (SELECT IdCarrera FROM Secciones WHERE IdSeccion = pIdSeccion AND IdFormulario = pIdFormulario);
    -- las columnas posicion y tamaño los toma de items_secciones, pero si son nulos, los toma de items
    SELECT  P.IdPregunta, P.IdCarrera, P.Texto, P.Descripcion, P.Creacion, P.Tipo, 
            Obligatoria, OrdenInverso, LimiteInferior, LimiteSuperior, Paso, Unidad,
            COALESCE(IC.Posicion, I.Posicion) AS Posicion, 
            COALESCE(IC.Tamaño, I.Tamaño) AS Tamaño
    FROM    Items I INNER JOIN Preguntas P ON I.IdPregunta = P.IdPregunta 
            LEFT JOIN Items_Carreras IC ON I.IdSeccion = IC.IdSeccion AND 
                I.IdFormulario = IC.IdFormulario AND I.IdPregunta = IC.IdPregunta AND
                I.IdCarrera = IC.IdCarrera
    WHERE   I.IdSeccion = pIdSeccion AND I.IdFormulario = pIdFormulario AND (I.IdCarrera IS NULL OR I.IdCarrera = pIdCarrera)
    ORDER BY Posicion;
    COMMIT;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_opciones`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_opciones`(
    pIdPregunta INT)
BEGIN
    SELECT  IdOpcion, Texto
    FROM    Opciones
    WHERE   IdPregunta = pIdPregunta
    ORDER BY IdOpcion;
    -- ordenados por orden de creacion
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_materia`(
    pIdMateria SMALLINT)
BEGIN
    SELECT IdMateria, Nombre, Codigo, Alumnos
    FROM Materias
    WHERE IdMateria = pIdMateria;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_carrera`(
    pIdCarrera SMALLINT)
BEGIN
    SELECT IdCarrera, IdDepartamento, Nombre, Plan
    FROM Carreras
    WHERE IdCarrera = pIdCarrera;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_departamento`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_departamento`(
    pNombre VARCHAR(60))
BEGIN
    DECLARE id SMALLINT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre del departamento no puede estar vacío.';
    ELSE
        START TRANSACTION;
        IF EXISTS( SELECT Nombre FROM Departamentos WHERE Nombre = pNombre LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe un departamento que se llama ',pNombre,'.');
            ROLLBACK;
        ELSE    
            SET id = (  
                SELECT COALESCE(MAX(IdDepartamento),0)+1 
                FROM    Departamentos);
            INSERT INTO Departamentos 
                (IdDepartamento, IdJefeDepartamento, Nombre)
            VALUES (id, NULL, pNombre);
            IF err THEN
                SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET Mensaje = id;
                COMMIT;
            END IF;
        END IF;            
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_departamento`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_baja_departamento`(
    pIdDepartamento SMALLINT)
BEGIN
    DECLARE id INT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT IdDepartamento FROM Carreras WHERE IdDepartamento = pIdDepartamento LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, existe una carrera asociada al departamento.';
        ROLLBACK;
    ELSE
        DELETE FROM Departamentos
        WHERE IdDepartamento = pIdDepartamento;
        IF err THEN
            SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET Mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_modificar_departamento`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_modificar_departamento`(
    pIdDepartamento SMALLINT,
    pNombre VARCHAR(60))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre del departamento no puede estar vacío.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT IdDepartamento FROM Departamentos WHERE IdDepartamento = pIdDepartamento LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe un departamento con ID=',pIdDepartamento,'.');
            ROLLBACK;
        ELSEIF EXISTS( SELECT Nombre FROM Departamentos WHERE Nombre = pNombre AND IdDepartamento != pIdDepartamento LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe un departamento que se llama ',pNombre,'.');
            ROLLBACK;
        ELSE    
            UPDATE Departamentos 
            SET Nombre = pNombre
            WHERE IdDepartamento = pIdDepartamento;
            IF err THEN
                SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET Mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;            
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_carrera`(
    pIdDepartamento SMALLINT,
    pNombre VARCHAR(60),
    pPlan SMALLINT)
BEGIN
    DECLARE id SMALLINT;
    DECLARE Mensaje VARCHAR(120);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre de la carrera no puede estar vacío.';
    ELSEIF pPlan < 1900 OR pPlan > 2100 THEN
        SET Mensaje = 'El plan debe ser un número entre 1900 y 2100.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT IdDepartamento FROM Departamentos WHERE IdDepartamento = pIdDepartamento LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe un departamento con ID=', pIdDepartamento,'.');
            ROLLBACK;
        ELSEIF EXISTS(  SELECT Nombre FROM Carreras 
                        WHERE Nombre = pNombre AND Plan=pPlan LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe una carrera del plan ', pPlan,' que se llama ', pNombre, '.');
            ROLLBACK;
        ELSE    
            SET id = (  
                SELECT COALESCE(MAX(IdCarrera),0)+1 
                FROM    Carreras);
            INSERT INTO Carreras 
                (IdCarrera, IdDepartamento, Nombre, Plan)
            VALUES (id, pIdDepartamento, pNombre, pPlan);
            IF err THEN
                SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET Mensaje = id;
                COMMIT;
            END IF;
        END IF;
    END IF;        
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_baja_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_baja_carrera`(
    pIdCarrera SMALLINT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT IdCarrera FROM Materias_Carreras WHERE IdCarrera = pIdCarrera LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, existe una materia asociada a la carrera.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT IdCarrera FROM Secciones WHERE IdCarrera = pIdCarrera LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, la carrera tiene secciones de formularios asociadas.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT IdCarrera FROM Preguntas WHERE IdCarrera = pIdCarrera LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, la carrera tiene preguntas asociadas.';
        ROLLBACK;
    ELSE
        DELETE FROM Accesos_Carreras
        WHERE IdCarrera = pIdCarrera;
        DELETE FROM Items_Carreras
        WHERE IdCarrera = pIdCarrera;
        DELETE FROM Carreras
        WHERE IdCarrera = pIdCarrera;
        IF err THEN
            SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET Mensaje = 'ok';
            COMMIT;
        END IF;
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_modificar_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_modificar_carrera`(
    pIdCarrera SMALLINT,
    pIdDepartamento SMALLINT,
    pNombre VARCHAR(60),
    pPlan SMALLINT)
BEGIN
    DECLARE id SMALLINT;
    DECLARE Mensaje VARCHAR(120);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre de la carrera no puede estar vacío.';
    ELSEIF pPlan < 1900 OR pPlan > 2100 THEN
        SET Mensaje = 'El plan debe ser un número entre 1900 y 2100.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT IdCarrera FROM Carreras WHERE IdCarrera = pIdCarrera LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe la carrera con ID=',pIdCarrera,'.');
            ROLLBACK;
        ELSEIF NOT EXISTS( SELECT IdDepartamento FROM Departamentos WHERE IdDepartamento = pIdDepartamento LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe un departamento con ID=',pIdDepartamento,'.');
            ROLLBACK;
        ELSE    
            UPDATE Carreras 
            SET IdDepartamento=pIdDepartamento, Nombre = pNombre, Plan = pPlan
            WHERE IdCarrera = pIdCarrera;
            IF err THEN
                SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
                ROLLBACK;
            ELSE 
                SET Mensaje = 'ok';
                COMMIT;
            END IF;
        END IF;
    END IF;        
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_carreras`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_carreras`(
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdDepartamento, IdCarrera, Nombre, Plan
    FROM    Carreras
    ORDER BY Nombre, Plan DESC
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_carreras`()
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Carreras;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_formulario`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_formulario`(
    pIdFormulario INT)
BEGIN
    SELECT  IdFormulario, Nombre, Titulo, Descripcion, 
            Creacion, PreguntasAdicionales
    FROM    Formularios
    WHERE   IdFormulario = pIdFormulario;
END $$

DELIMITER ;



DROP PROCEDURE IF EXISTS `esp_respuestas_clave`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_respuestas_clave`(
    pIdClave INT,
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
    SELECT  IC.IdSeccion, R.IdPregunta, R.IdDocente, R.Opcion, R.Texto
    FROM    Respuestas R 
            LEFT JOIN Items I ON I.IdFormulario = R.IdFormulario AND I.IdPregunta = R.IdPregunta
            LEFT JOIN Items_Carreras IC ON IC.IdCarrera = R.IdCarrera AND IC.IdFormulario = R.IdFormulario AND IC.IdPregunta = R.IdPregunta
            LEFT JOIN Docentes_Materias DM ON DM.IdDocente = R.IdDocente AND DM.IdMateria = R.IdMateria
    WHERE   R.IdClave = pIdClave AND R.IdMateria = pIdMateria AND R.IdCarrera = pIdCarrera AND 
            R.IdEncuesta = pIdEncuesta AND R.IdFormulario = pIdFormulario
    ORDER BY IC.IdSeccion, COALESCE(DM.OrdenFormulario,255), COALESCE(IC.Posicion, I.Posicion);
END $$

DELIMITER ;



DROP PROCEDURE IF EXISTS `esp_respuestas_pregunta_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_respuestas_pregunta_materia`(
    pIdPregunta INT,
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
	SELECT  Opcion, COUNT(IdRespuesta) AS Cantidad
	FROM    Respuestas R
	WHERE   R.IdPregunta = pIdPregunta AND R.IdMateria = pIdMateria AND 
			R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND
			R.IdFormulario = pIdFormulario
	GROUP BY Opcion;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_dame_encuesta`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_dame_encuesta`(
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
    SELECT  IdEncuesta, IdFormulario, Año, Cuatrimestre, FechaInicio, FechaFin
    FROM    Encuestas
    WHERE   IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;            
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_textos_pregunta_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_textos_pregunta_materia`(
    pIdPregunta INT,
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
	SELECT  Texto
	FROM    Respuestas R
	WHERE   R.IdPregunta = pIdPregunta AND R.IdMateria = pIdMateria AND 
			R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND
			R.IdFormulario = pIdFormulario AND Texto IS NOT NULL;;
END

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_claves_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_claves_materia`(
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
    SELECT  Count(IdClave) AS Generadas, Count(Utilizada) AS Utilizadas, 
			MIN(Utilizada) AS PrimerAcceso, MAX(Utilizada) AS UltimoAcceso
    FROM    Claves
    WHERE   IdMateria = pIdMateria AND IdCarrera = pIdCarrera AND 
            IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;
END