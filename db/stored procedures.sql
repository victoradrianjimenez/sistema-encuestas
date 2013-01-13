DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_departamentos`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_departamentos`(
    pPagNumero INT,
    pPagLongitud INT)
BEGIN
    SET @qry = '
    SELECT  IdDepartamento, IdJefeDepartamento, Nombre
    FROM Departamentos
    ORDER BY Nombre
    LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagNumero * pPagLongitud;
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
    WHERE Usuario = pUsuario AND Contraseña = pContraseña
    LIMIT 1;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_carreras_departamento`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_carreras_departamento`(
    pIdDepartamento SMALLINT,
    pPagNumero INT,
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
    SET @a = pPagNumero * pPagLongitud;
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
    SET @qry = '
    SELECT  COUNT(*) AS Cantidad
    FROM    Carreras
    WHERE   IdDepartamento = ?';
    PREPARE stmt FROM  @qry;
    SET @a = pIdDepartamento;
    EXECUTE stmt USING @a;
    DEALLOCATE PREPARE stmt;
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
                SET Mensaje = clave;
                COMMIT;
            END IF;            
        END IF;
    END IF;
    SELECT Mensaje;
END $$

DELIMITER ;



