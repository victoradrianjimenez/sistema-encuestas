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