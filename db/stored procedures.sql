
UPDATE Items_Carreras
SET Tamaño = 2
WHERE IdCarrera >= 0 AND IdFormulario = 1 AND IdPregunta = 39;


UPDATE Items
SET Tamaño = 2
WHERE IdCarrera >= 0 AND IdFormulario = 1 AND IdPregunta = 39;

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_materias_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_materias_carrera`(
	pIdCarrera SMALLINT)
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Materias_Carreras
	WHERE	IdCarrera = pIdCarrera;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_materias_carrera`(
	pIdCarrera SMALLINT,
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  M.IdMateria, M.Nombre, M.Codigo, M.Alumnos
    FROM    Materias M INNER JOIN Materias_Carreras MC ON M.IdMateria = MC.IdMateria
	WHERE	MC.IdCarrera = ?
    ORDER BY M.Nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pIdCarrera;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_materias`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_materias`()
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Materias;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_materias`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_materias`(
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdMateria, Nombre, Codigo, Alumnos
    FROM    Materias
    ORDER BY Nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_personas`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_personas`(
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdPersona, Apellido, Nombre, Usuario, Email,
			Contraseña, UltimoAcceso, Estado
    FROM    Personas
    ORDER BY Apellido, Nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_personas`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_personas`()
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Personas;
END $$

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

-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_respuestas_clave`(
    pIdClave INT,
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
    SELECT  IC.IdSeccion, R.IdPregunta, P.Tipo, R.IdDocente, R.Opcion, R.Texto,
			COUNT(IdOpcion) as Opciones, IC.Importancia
    FROM    Respuestas R INNER JOIN Preguntas P ON R.IdPregunta = P.IdPregunta
            LEFT JOIN Items I ON I.IdFormulario = R.IdFormulario AND I.IdPregunta = R.IdPregunta
            LEFT JOIN Items_Carreras IC ON IC.IdCarrera = R.IdCarrera AND IC.IdFormulario = R.IdFormulario AND IC.IdPregunta = R.IdPregunta
            LEFT JOIN Docentes_Materias DM ON DM.IdDocente = R.IdDocente AND DM.IdMateria = R.IdMateria
			LEFT JOIN Opciones O On O.IdPregunta = R.IdPregunta
    WHERE   R.IdClave = pIdClave AND R.IdMateria = pIdMateria AND R.IdCarrera = pIdCarrera AND 
            R.IdEncuesta = pIdEncuesta AND R.IdFormulario = pIdFormulario
	GROUP BY R.IdPregunta, R.IdDocente
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
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_persona`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_persona`(
    pApellido VARCHAR(40),
    pNombre VARCHAR(40),
    pUsuario VARCHAR(40),
    pEmail VARCHAR(200),
    pContraseña CHAR(64))
BEGIN
    DECLARE id INT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pApellido,'') = '' THEN
        SET Mensaje = 'El apellido no puede ser vacío.';
    ELSEIF COALESCE(pUsuario,'') = '' THEN
        SET Mensaje = 'El nombre de usuario no puede ser vacío.';
    ELSEIF COALESCE(pEmail,'') = '' THEN
        SET Mensaje = 'La dirección de email no puede ser vacía.';
    ELSE
        START TRANSACTION;
        IF EXISTS(SELECT Usuario FROM Personas WHERE Usuario = pUsuario LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe una persona con nombre de usuario ',pUsuario,'.');
            ROLLBACK;
        ELSEIF EXISTS(SELECT Email FROM Personas WHERE Email = pEmail LIMIT 1) THEN
            SET Mensaje = 'Ya existe un usuario con el email ingresado.';
            ROLLBACK;
        ELSE
            SET id = (  
                SELECT COALESCE(MAX(IdPersona),0)+1 
                FROM    Personas);
            INSERT INTO Personas
                (IdPersona, Apellido, Nombre, Usuario, Email, Contraseña, UltimoAcceso, Estado)
            VALUES (id, pApellido, pNombre, pUsuario, pEmail, 
                pContraseña, NOW(), 'A');
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


DROP PROCEDURE IF EXISTS `esp_buscar_personas`;


DELIMITER $$

CREATE PROCEDURE `sistema_encuestas`.`esp_buscar_personas` (
	pNombre VARCHAR(40))
BEGIN
	IF COALESCE(pNombre,'') != '' THEN
		SELECT	IdPersona, Apellido, Nombre, Usuario, Email, 
				Contraseña, UltimoAcceso, Estado
		FROM	Personas
		WHERE	Apellido like CONCAT('%',pNombre,'%') OR Nombre like CONCAT('%',pNombre,'%');
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_carreras`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_buscar_carreras`(
	pNombre VARCHAR(60))
BEGIN
	IF COALESCE(pNombre,'') != '' THEN
		SELECT	IdCarrera, IdDepartamento, Nombre, Plan 
		FROM	Carreras
		WHERE	Nombre like CONCAT('%',pNombre,'%');
	END IF;
END $$


DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_materias`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_buscar_materias`(
	pNombre VARCHAR(60))
BEGIN
	IF COALESCE(pNombre,'') != '' THEN
		SELECT	IdMateria, Nombre, Codigo, Alumnos
		FROM	Materias
		WHERE	Nombre like CONCAT('%',pNombre,'%');
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_asociar_materia_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_asociar_materia_carrera`(
    pIdMateria SMALLINT,
	pIdCarrera SMALLINT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF NOT EXISTS(SELECT IdMateria FROM Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se encuestra una materia con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(SELECT IdCarrera FROM Carreras WHERE IdCarrera = pIdCarrera LIMIT 1) THEN
        SET Mensaje = 'No se encuestra una carrera con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(  SELECT IdCarrera FROM Materias_Carreras 
                        WHERE  IdCarrera = pIdCarrera AND IdMateria = pIdMateria LIMIT 1) THEN
        INSERT INTO Materias_Carreras
            (IdMateria, IdCarrera)
        VALUES (pIdMateria, pIdCarrera);
        IF err THEN
            SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
            ROLLBACK;
        ELSE 
            SET Mensaje = 'ok';
            COMMIT;
        END IF;
    ELSE
        SET Mensaje = 'ok';
        COMMIT;
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_cantidad_docentes_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_docentes_materia`(
	pIdMateria SMALLINT)
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Docentes_Materias
	WHERE	IdMateria = pIdMateria;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_docentes_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_docentes_materia`(
	pIdMateria SMALLINT,
    pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  P.IdPersona, P.Apellido, P.Nombre, P.Usuario, P.Email, P.UltimoAcceso, P.Estado, 
			DM.TipoAcceso, DM.OrdenFormulario, DM.Cargo
    FROM    Personas P INNER JOIN Docentes_Materias DM ON P.IdPersona = DM.IdDocente
	WHERE	DM.IdMateria = ?
    ORDER BY P.Apellido, P.Nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pIdMateria;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_asociar_docente_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_asociar_docente_materia`(
    pIdDocente INT,
	pIdMateria SMALLINT,
	pTipoAcceso CHAR(1),
	pOrdenFormulario TINYINT,
	pCargo VARCHAR(40))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF NOT EXISTS(SELECT IdMateria FROM Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se encuestra una materia con ID dado.';
        ROLLBACK;
    ELSEIF NOT EXISTS(SELECT IdPersona FROM Personas WHERE IdPersona = pIdDocente LIMIT 1) THEN
        SET Mensaje = 'No se encuestra una docente con ID dado.';
        ROLLBACK;
    ELSEIF EXISTS(	SELECT IdDocente FROM Docentes_Materias
					WHERE  IdDocente = pIdDocente AND IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'Ya existe una asociación entre el docente y la materia dados.';
        ROLLBACK;
	ELSE
        INSERT INTO Docentes_Materias
            (IdDocente, IdMateria, TipoAcceso, OrdenFormulario, Cargo)
        VALUES (pIdDocente, pIdMateria, pTipoAcceso, pOrdenFormulario, pCargo);
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


DROP PROCEDURE IF EXISTS `esp_desasociar_docente_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_desasociar_docente_materia`(
    pIdDocente INT,
	pIdMateria SMALLINT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
	DELETE FROM Docentes_Materias
	WHERE IdDocente = pIdDocente AND IdMateria = pIdMateria;
	IF err THEN
		SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
		ROLLBACK;
	ELSE 
		SET Mensaje = 'ok';
		COMMIT;
	END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_materia`;


DELIMITER $$


CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_materia`(
    pNombre VARCHAR(60),
    pCodigo CHAR(5))
BEGIN
    DECLARE id SMALLINT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre de la materia no puede estar vacío.';
	ELSEIF COALESCE(pCodigo,'')='' THEN
        SET Mensaje = 'El código no puede ser nulo.';
	ELSE
        START TRANSACTION;
        IF EXISTS( SELECT Codigo FROM Materias WHERE Codigo = pCodigo LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe una materia con código ',pCodigo,'.');
            ROLLBACK;
        ELSE
            SET id = (  
                SELECT COALESCE(MAX(IdMateria),0)+1 
                FROM    Materias );
            INSERT INTO Materias 
                (IdMateria, Nombre, Codigo, Alumnos)
            VALUES (id, pNombre, pCodigo, 0);
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



DROP PROCEDURE IF EXISTS `esp_modificar_materia`;


DELIMITER $$


CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_modificar_materia`(
	pIdMateria SMALLINT,
    pNombre VARCHAR(60),
    pCodigo CHAR(5))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre de la materia no puede estar vacío.';
    ELSEIF COALESCE(pCodigo,'')='' THEN
        SET Mensaje = 'El código no puede ser nulo.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS( SELECT IdMateria FROM Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe la materia con ID=',pIdMateria,'.');
            ROLLBACK;
        ELSEIF EXISTS( SELECT Codigo FROM Materias WHERE Codigo = pCodigo AND IdMateria != pIdMateria LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe una materia con código ',pCodigo,'.');
            ROLLBACK;
        ELSE
            UPDATE Materias 
			SET Nombre = pNombre, Codigo = pCodigo
			WHERE IdMateria = pIdMateria;
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


DROP PROCEDURE IF EXISTS `esp_baja_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_baja_materia`(
    pIdMateria SMALLINT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    START TRANSACTION;
    IF EXISTS(SELECT IdMateria FROM Materias_Carreras WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar la materia ya que esta relacionada con una carrera.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT IdMateria FROM Devoluciones WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, existe al menos una devolucion asociada a la materia.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT IdMateria FROM Alumnos_Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, existe un alumno asociado a la materia.';
        ROLLBACK;
    ELSEIF EXISTS(SELECT IdMateria FROM Docentes_Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
        SET Mensaje = 'No se puede eliminar, existe un docente asociado a la materia.';
        ROLLBACK;
    ELSE
        DELETE FROM Materias
        WHERE IdMateria = pIdMateria;
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


DROP PROCEDURE IF EXISTS `esp_modificar_persona`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_modificar_persona`(
	pIdPersona INT,
    pApellido VARCHAR(40),
    pNombre VARCHAR(40),
    pUsuario VARCHAR(40),
    pEmail VARCHAR(200),
    pContraseñaAnterior CHAR(64),
	pContraseña CHAR(64))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pApellido,'') = '' THEN
        SET Mensaje = 'El apellido no puede ser vacío.';
    ELSEIF COALESCE(pUsuario,'') = '' THEN
        SET Mensaje = 'El nombre de usuario no puede ser vacío.';
    ELSEIF COALESCE(pEmail,'') = '' THEN
        SET Mensaje = 'La dirección de email no puede ser vacía.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT IdPersona FROM Personas WHERE IdPersona = pIdPersona LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe registro de la  persona con ID=',pIdPersona,'.');
            ROLLBACK;
        ELSEIF pContraseñaAnterior != (SELECT Contraseña FROM Personas WHERE IdPersona = pIdPersona LIMIT 1) THEN
            SET Mensaje = CONCAT('La contraseña ingresada en incorrecta.');
            ROLLBACK;
        ELSEIF EXISTS(SELECT Usuario FROM Personas WHERE Usuario = pUsuario AND IdPersona != pIdPersona LIMIT 1) THEN
            SET Mensaje = CONCAT('Ya existe una persona con nombre de usuario ',pUsuario,'.');
            ROLLBACK;
        ELSEIF EXISTS(SELECT Email FROM Personas WHERE Email = pEmail AND IdPersona != pIdPersona LIMIT 1) THEN
            SET Mensaje = 'Ya existe un usuario con el email ingresado.';
            ROLLBACK;
        ELSE
            UPDATE Personas
			SET Apellido = pApellido, Nombre = pNombre, Usuario = pUsuario,
				Email = pEmail, Contraseña = pContraseña
			WHERE IdPersona = pIdPersona;
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


DROP PROCEDURE IF EXISTS `esp_estado_persona`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_estado_persona`(
	pIdPersona INT,
	pEstado CHAR(1))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF NOT pEstado IN ('A','I') THEN
        SET Mensaje = 'El estado es inválido.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT IdPersona FROM Personas WHERE IdPersona = pIdPersona LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe registro de la  persona con ID=',pIdPersona,'.');
            ROLLBACK;
        ELSE
            UPDATE Personas
			SET Estado = pEstado
			WHERE IdPersona = pIdPersona;
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


DROP PROCEDURE IF EXISTS `esp_asignar_cantidad_alumnos`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_asignar_cantidad_alumnos`(
    pIdMateria SMALLINT,
	pAlumnos SMALLINT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
	START TRANSACTION;
	IF NOT EXISTS(SELECT IdMateria FROM Materias WHERE IdMateria = pIdMateria LIMIT 1) THEN
		SET Mensaje = CONCAT('No existe registro de la  materia con ID=',pIdMateria,'.');
		ROLLBACK;
	ELSE
		UPDATE Materias
		SET Alumnos = pAlumnos
		WHERE IdMateria = pIdMateria;
		IF err THEN
			SET Mensaje = 'Error inesperado al intentar acceder a la base de datos.';
			ROLLBACK;
		ELSE 
			SET Mensaje = id;
			COMMIT;
		END IF;
	END IF;
    SELECT Mensaje;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_encuestas`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_encuestas`(
	pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdEncuesta, IdFormulario, Año, Cuatrimestre, FechaInicio, FechaFin
    FROM    Encuestas
    ORDER BY Año DESC, Cuatrimestre DESC, FechaInicio DESC, FechaFin DESC
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_cantidad_encuestas`()
BEGIN
    SELECT  COUNT(*) AS Cantidad
    FROM    Encuestas;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_alta_encuesta`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_alta_encuesta`(
    pIdFormulario INT,
    pAño SMALLINT, 
    pCuantrimestre TINYINT)
BEGIN
    DECLARE id SMALLINT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF pAño < 1900 OR pAño > 2100 THEN
        SET Mensaje = 'El año ingresado es incorrecto.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(  SELECT IdFormulario FROM Formularios    
                        WHERE IdFormulario = pIdFormulario LIMIT 1) THEN
            SET Mensaje = CONCAT('No se encontró el formulario con ID=',pIdFormulario,'.');
            ROLLBACK;
        ELSE
            SET id = (  
                SELECT COALESCE(MAX(IdEncuesta),0)+1 
                FROM    Encuestas
                WHERE   IdFormulario = pIdFormulario);
            INSERT INTO Encuestas 
                (IdEncuesta, IdFormulario, Año, Cuantrimestre, FechaInicio, FechaFin)
            VALUES (id, pIdFormulario, pAño, pCuantrimestre, NOW(), NULL);
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


DROP PROCEDURE IF EXISTS `esp_listar_claves_encuesta_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_claves_encuesta_materia`(
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pPagInicio INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario, 
			Clave, Tipo, Generada, Utilizada
    FROM    Claves
	WHERE	IdMateria = ? AND IdCarrera = ? AND IdEncuesta = ? AND IdFormulario = ?
    ORDER BY Generada DESC, Utilizada DESC
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
	SET @c = pIdMateria;
	SET @d = pIdCarrera;
	SET @e = pIdEncuesta;
	SET @f = pIdFormulario;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @c, @d, @e, @f, @a, @b;
    DEALLOCATE PREPARE stmt;
END