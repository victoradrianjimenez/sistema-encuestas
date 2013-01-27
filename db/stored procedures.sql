-- CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_docentes_materia`(
-- 	pIdMateria SMALLINT)
-- BEGIN
--     SELECT  P.IdPersona, P.Apellido, P.Nombre, DM.TipoAcceso, DM.OrdenFormulario, DM.Cargo
--     FROM    Personas P INNER JOIN Docentes_Materias DM ON P.IdPersona = DM.IdDocente
-- 	WHERE	DM.IdMateria = pIdMateria
--     ORDER BY DM.OrdenFormulario, P.Apellido, P.Nombre;
-- END


UPDATE Respuestas
SET Opcion = NULL
WHERE Opcion = 0 AND IdRespuesta >= 0;


UPDATE Items_Carreras 
SET 
    Tamaño = 2
WHERE
    IdCarrera >= 0 AND IdFormulario = 1
        AND IdPregunta = 39;


UPDATE Items 
SET 
    Tamaño = 2
WHERE
    IdCarrera >= 0 AND IdFormulario = 1
        AND IdPregunta = 39;

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    `description` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varbinary(16) NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(80) NOT NULL,
    `salt` varchar(40) DEFAULT NULL,
    `email` varchar(100) NOT NULL,
    `activation_code` varchar(40) DEFAULT NULL,
    `forgotten_password_code` varchar(40) DEFAULT NULL,
    `forgotten_password_time` int(11) unsigned DEFAULT NULL,
    `remember_code` varchar(40) DEFAULT NULL,
    `created_on` int(11) unsigned NOT NULL,
    `last_login` int(11) unsigned DEFAULT NULL,
    `active` tinyint(1) unsigned DEFAULT NULL,
    `first_name` varchar(50) DEFAULT NULL,
    `last_name` varchar(50) DEFAULT NULL,
    `company` varchar(100) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` mediumint(8) unsigned NOT NULL,
    `group_id` mediumint(8) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varbinary(16) NOT NULL,
    `login` varchar(100) NOT NULL,
    `time` int(11) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`)
);

DELETE FROM groups WHERE id >= 0;

INSERT INTO groups VALUES
('1', 'admin', 'Administradores'),
('2', 'decanos', 'Decanos'),
('3', 'jefes_departamentos', 'Jefes de departamentos'),
('4', 'directores', 'Directores de carrera'),
('5', 'jefes_catedras', 'Jefes de cátedra'),
('6', 'docentes', 'Docentes'),
('7', 'organizadores', 'Organizadores'),
('8', 'alumnos', 'Alumnos');

DELETE FROM users WHERE id >= 0;

INSERT INTO users VALUES
('1', 0x7f000001, 'admin', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'admin@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('2', 0x7f000001, 'mabdala', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mabdala@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('6', 0x7f000001, 'rascoeta', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rascoeta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('7', 0x7f000001, 'mbazzano', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mbazzano@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('8', 0x7f000001, 'rbecker', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rbecker@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('9', 0x7f000001, 'ibedascarrasbure', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'ibedascarrasbure@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('10', 0x7f000001, 'jbilbao', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jbilbao@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('11', 0x7f000001, 'jbriones', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jbriones@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('12', 0x7f000001, 'sbueso', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'sbueso@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('13', 0x7f000001, 'acampos', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'acampos@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('14', 0x7f000001, 'mcardozo', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcardozo@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('15', 0x7f000001, 'acarlino', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'acarlino@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('16', 0x7f000001, 'mcarlorossi', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcarlorossi@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('17', 0x7f000001, 'jcasal', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jcasal@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('18', 0x7f000001, 'dcohen', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'dcohen@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('19', 0x7f000001, 'mcouteret', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcouteret@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('20', 0x7f000001, 'ldelazerda', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'ldelazerda@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('21', 0x7f000001, 'rdiaz', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rdiaz@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('22', 0x7f000001, 'mestevez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mestevez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('23', 0x7f000001, 'rfadel', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rfadel@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('24', 0x7f000001, 'rfanjul', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rfanjul@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('25', 0x7f000001, 'pfernandez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'pfernandez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('26', 0x7f000001, 'hferrao', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'hferrao@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('27', 0x7f000001, 'cformigli', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'cformigli@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('28', 0x7f000001, 'sgallo', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'sgallo@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('29', 0x7f000001, 'egarat', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'egarat@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('30', 0x7f000001, 'dgil', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'dgil@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('31', 0x7f000001, 'sgomez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'sgomez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('32', 0x7f000001, 'gomez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'gomez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('33', 0x7f000001, 'mgomez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mgomez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('34', 0x7f000001, 'rgonzalez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rgonzalez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('35', 0x7f000001, 'mgoñi', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mgoñi@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('36', 0x7f000001, 'mguzmán', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mguzmán@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('37', 0x7f000001, 'chamakers', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'chamakers@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('38', 0x7f000001, 'rhurtado', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rhurtado@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('39', 0x7f000001, 'jise', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jise@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('40', 0x7f000001, 'civan', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'civan@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('41', 0x7f000001, 'ajuarez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'ajuarez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('42', 0x7f000001, 'gjuarez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'gjuarez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('43', 0x7f000001, 'flutz', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'flutz@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('44', 0x7f000001, 'npadilla', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'npadilla@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('45', 0x7f000001, 'rmartinez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rmartinez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('46', 0x7f000001, 'mmitre', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mmitre@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('47', 0x7f000001, 'jmolina', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jmolina@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('48', 0x7f000001, 'fnader', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'fnader@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('49', 0x7f000001, 'lnieto', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'lnieto@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('50', 0x7f000001, 'wnovotny', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'wnovotny@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('51', 0x7f000001, 'modstrcil', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'modstrcil@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('52', 0x7f000001, 'aolmos', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'aolmos@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('53', 0x7f000001, 'mormachea', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mormachea@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('54', 0x7f000001, 'rpando', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rpando@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('55', 0x7f000001, 'jperez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jperez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('56', 0x7f000001, 'crivas', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'crivas@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('57', 0x7f000001, 'rrivero', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rrivero@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('58', 0x7f000001, 'brizzotti', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'brizzotti@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('59', 0x7f000001, 'jrusso', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jrusso@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('60', 0x7f000001, 'rsaade', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rsaade@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('61', 0x7f000001, 'ssaade', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'ssaade@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('62', 0x7f000001, 'msanchez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'msanchez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('63', 0x7f000001, 'gsavino', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'gsavino@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('64', 0x7f000001, 'hschwab', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'hschwab@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('65', 0x7f000001, 'jsteifensand', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jsteifensand@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('66', 0x7f000001, 'gvanetta', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'gvanetta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('67', 0x7f000001, 'jvilte grande', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jvilte grande@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('68', 0x7f000001, 'rvilte', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rvilte@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('69', 0x7f000001, 'evolentini', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'evolentini@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('70', 0x7f000001, 'wweyerstall', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'wweyerstall@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('71', 0x7f000001, 'jyelamos', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jyelamos@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('72', 0x7f000001, 'jyounes', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jyounes@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('73', 0x7f000001, 'jgiori', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jgiori@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('74', 0x7f000001, 'mjuarez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mjuarez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('75', 0x7f000001, 'fmenendez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'fmenendez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('76', 0x7f000001, 'srochio', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'srochio@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('77', 0x7f000001, 'gluccioni', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'gluccioni@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('78', 0x7f000001, 'pmoreta', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'pmoreta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('79', 0x7f000001, 'fpacheco', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'fpacheco@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('80', 0x7f000001, 'jbilbao', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jbilbao@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('81', 0x7f000001, 'rnahas', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rnahas@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('82', 0x7f000001, 'awill', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'awill@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('83', 0x7f000001, 'eazaretzky', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'eazaretzky@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('84', 0x7f000001, 'araska', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'araska@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('85', 0x7f000001, 'mcabrera', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcabrera@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('86', 0x7f000001, 'aandrada barone', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'aandrada barone@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('87', 0x7f000001, 'lteodovich', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'lteodovich@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('88', 0x7f000001, 'fdelgado', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'fdelgado@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('89', 0x7f000001, 'atorres bugeau', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'atorres bugeau@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('90', 0x7f000001, 'lcuello', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'lcuello@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('91', 0x7f000001, 'jgonzalez', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jgonzalez@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('92', 0x7f000001, 'faiquel', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'faiquel@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('93', 0x7f000001, 'mavellaneda', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mavellaneda@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('94', 0x7f000001, 'lassaf', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'lassaf@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('95', 0x7f000001, 'mcecilia', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcecilia@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('96', 0x7f000001, 'godstrcil', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'godstrcil@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('97', 0x7f000001, 'csueldo', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'csueldo@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('98', 0x7f000001, 'rbarbera', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rbarbera@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('99', 0x7f000001, 'mcga', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'mcga@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('100', 0x7f000001, 'jcangemi', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'jcangemi@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('101', 0x7f000001, 'cgoy', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'cgoy@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('102', 0x7f000001, 'rlabastida', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'rlabastida@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO users VALUES
('500', 0x7f000001, 'decano', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'encuesta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('501', 0x7f000001, 'jefedepartamento', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'encuesta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('502', 0x7f000001, 'director', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'encuesta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('503', 0x7f000001, 'jefecatedra', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'encuesta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('504', 0x7f000001, 'docente', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'encuesta@herrera.unt.edu.ar', '', NULL, '1268889823', '1268889823', '1', NULL, NULL, NULL, NULL, NULL, NULL);

UPDATE users 
SET 
    active = 1
where
    id >= 0;

DELETE FROM users_groups WHERE id >= 0;

INSERT INTO users_groups VALUES
('1', '1', '1'),
('2', '1', '6'),
('3', '2', '6'),
('4', '92', '6'),
('5', '86', '6'),
('6', '6', '6'),
('7', '94', '6'),
('8', '93', '6'),
('9', '83', '6'),
('10', '98', '6'),
('11', '7', '6'),
('12', '8', '6'),
('13', '9', '6'),
('14', '80', '6'),
('15', '10', '6'),
('16', '11', '6'),
('17', '12', '6'),
('18', '99', '6'),
('19', '85', '6'),
('20', '13', '6'),
('21', '100', '6'),
('22', '14', '6'),
('23', '15', '6'),
('24', '16', '6'),
('25', '17', '6'),
('26', '95', '6'),
('27', '18', '6'),
('28', '19', '6'),
('29', '90', '6'),
('30', '20', '6'),
('31', '88', '6'),
('32', '21', '6'),
('33', '22', '6'),
('34', '23', '6'),
('35', '24', '6'),
('36', '25', '6'),
('37', '26', '6'),
('38', '27', '6'),
('39', '28', '6'),
('40', '29', '6'),
('41', '30', '6'),
('42', '73', '6'),
('43', '31', '6'),
('44', '32', '6'),
('45', '33', '6'),
('46', '91', '6'),
('47', '34', '6'),
('48', '35', '6'),
('49', '101', '6'),
('50', '36', '6'),
('51', '37', '6'),
('52', '38', '6'),
('53', '39', '6'),
('54', '40', '6'),
('55', '41', '6'),
('56', '42', '6'),
('57', '74', '6'),
('58', '102', '6'),
('59', '77', '6'),
('60', '43', '6'),
('61', '44', '6'),
('62', '45', '6'),
('63', '75', '6'),
('64', '46', '6'),
('65', '47', '6'),
('66', '78', '6'),
('67', '48', '6'),
('68', '81', '6'),
('69', '49', '6'),
('70', '50', '6'),
('71', '96', '6'),
('72', '51', '6'),
('73', '52', '6'),
('74', '53', '6'),
('75', '79', '6'),
('76', '54', '6'),
('77', '55', '6'),
('78', '84', '6'),
('79', '56', '6'),
('80', '57', '6'),
('81', '58', '6'),
('82', '76', '6'),
('83', '59', '6'),
('84', '60', '6'),
('85', '61', '6'),
('86', '62', '6'),
('87', '63', '6'),
('88', '64', '6'),
('89', '65', '6'),
('90', '97', '6'),
('91', '87', '6'),
('92', '89', '6'),
('93', '66', '6'),
('94', '67', '6'),
('95', '68', '6'),
('96', '69', '6'),
('97', '70', '6'),
('98', '82', '6'),
('99', '71', '6'),
('100', '72', '6');

INSERT INTO users_groups VALUES
('101', '500', '2'),
('102', '501', '2'),
('103', '502', '2'),
('104', '503', '2'),
('105', '504', '2');

update personas 
set 
    idusuario = idpersona
where
    idpersona >= 0;

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
    SELECT  IdPersona, Apellido, Nombre
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
    SELECT IdPersona, Apellido, Nombre
    FROM Personas
    WHERE IdPersona = pIdPersona;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_validar_usuario`;

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
	pIdJefeDepartamento INT,
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
        ELSEIF pIdJefeDepartamento IS NOT NULL AND NOT EXISTS( SELECT IdPersona FROM Personas WHERE IdPersona = pIdJefeDepartamento LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe el jefe de departamento con ID=',pIdJefeDepartamento,'.');
            ROLLBACK;
		ELSE    
            SET id = (  
                SELECT COALESCE(MAX(IdDepartamento),0)+1 
                FROM    Departamentos);
            INSERT INTO Departamentos 
                (IdDepartamento, IdJefeDepartamento, Nombre)
            VALUES (id, pIdJefeDepartamento, pNombre);
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
	pIdJefeDepartamento INT,
    pNombre VARCHAR(60))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pNombre,'')='' THEN
        SET Mensaje = 'El nombre del departamento no puede estar vacío.';
	ELSEIF pIdJefeDepartamento IS NOT NULL AND NOT EXISTS( SELECT IdPersona FROM Personas WHERE IdPersona = pIdJefeDepartamento LIMIT 1) THEN
		SET Mensaje = CONCAT('No existe el jefe de departamento con ID=',pIdJefeDepartamento,'.');
		ROLLBACK;
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
            SET Nombre = pNombre, IdJefeDepartamento = pIdJefeDepartamento
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
	pIdDocente INT,
    pIdMateria SMALLINT,
    pIdCarrera SMALLINT,
    pIdEncuesta INT,
    pIdFormulario INT)
BEGIN
	SELECT	R.IdDocente, R.Opcion, IF(P.Tipo='N',P.LimiteInferior+P.Paso*(R.Opcion-1),O.Texto) AS Texto, COUNT(IdRespuesta) AS Cantidad
	FROM 	Respuestas R 
			INNER JOIN Preguntas P ON P.IdPregunta = R.IdPregunta
			LEFT JOIN Opciones O ON O.IdPregunta = R.IdPregunta AND O.IdOpcion = R.Opcion
	WHERE   R.IdPregunta = pIdPregunta AND R.IdMateria = pIdMateria AND 
			R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND
			R.IdFormulario = pIdFormulario AND COALESCE(R.IdDocente,0) = pIdDocente
	GROUP BY R.Opcion
	ORDER BY R.Opcion;
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
			R.IdFormulario = pIdFormulario AND Texto IS NOT NULL;
END $$

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
    pNombre VARCHAR(40))
BEGIN
    DECLARE id INT;
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pApellido,'') = '' THEN
        SET Mensaje = 'El apellido no puede ser vacío.';
    ELSE
        START TRANSACTION;
		SET id = (  
			SELECT COALESCE(MAX(IdPersona),0)+1 
			FROM    Personas);
		INSERT INTO Personas
			(IdPersona, Apellido, Nombre)
		VALUES (id, pApellido, pNombre);
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


DROP PROCEDURE IF EXISTS `esp_buscar_personas`;


DELIMITER $$

CREATE PROCEDURE `sistema_encuestas`.`esp_buscar_personas` (
	pNombre VARCHAR(40))
BEGIN
	IF COALESCE(pNombre,'') != '' THEN
		SELECT	IdPersona, Apellido, Nombre
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
	pIdMateria SMALLINT)
BEGIN
    SELECT  P.IdPersona, P.Apellido, P.Nombre
    FROM    Personas P INNER JOIN Docentes_Materias DM ON P.IdPersona = DM.IdDocente
	WHERE	DM.IdMateria = pIdMateria
    ORDER BY DM.OrdenFormulario, P.Apellido, P.Nombre;
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
    pNombre VARCHAR(40))
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;       
    
    IF COALESCE(pApellido,'') = '' THEN
        SET Mensaje = 'El apellido no puede ser vacío.';
    ELSE
        START TRANSACTION;
        IF NOT EXISTS(SELECT IdPersona FROM Personas WHERE IdPersona = pIdPersona LIMIT 1) THEN
            SET Mensaje = CONCAT('No existe registro de la  persona con ID=',pIdPersona,'.');
            ROLLBACK;
        ELSE
            UPDATE Personas
			SET Apellido = pApellido, Nombre = pNombre
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
    pCuatrimestre TINYINT)
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
                (IdEncuesta, IdFormulario, Año, Cuatrimestre, FechaInicio, FechaFin)
            VALUES (id, pIdFormulario, pAño, pCuatrimestre, NOW(), NULL);
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
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_formularios`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_formularios`(
	pPagInicio INT,
    pPagLongitud INT)
BEGIN
	SET @qry = '
    SELECT  IdFormulario, Nombre, Titulo, Descripcion, Creacion, 
            PreguntasAdicionales
    FROM    Formularios
    ORDER BY Nombre
	LIMIT ?,?';
    PREPARE stmt FROM  @qry;
    SET @a = pPagInicio;
    SET @b = pPagLongitud;
    EXECUTE stmt USING @a, @b;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_finalizar_encuesta`;


DELIMITER $$

CREATE PROCEDURE `sistema_encuestas`.`esp_finalizar_encuesta` (
	pIdEncuesta INT,
	pIdFormulario INT)
BEGIN
    DECLARE Mensaje VARCHAR(100);
    DECLARE err BOOLEAN DEFAULT FALSE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err=TRUE;   
	START TRANSACTION;
	IF NOT EXISTS(SELECT IdEncuesta FROM Encuestas WHERE IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario LIMIT 1) THEN
		SET Mensaje = CONCAT('No se encontró la encuesta con ID dado.');
		ROLLBACK;
	ELSE
		UPDATE	Encuestas
		SET		FechaFin = NOW()
		WHERE	IdEncuesta = pIdEncuesta AND 
				IdFormulario = pIdFormulario;
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


DROP PROCEDURE IF EXISTS `esp_indice_global`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_global`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	OUT Indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET Indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*Importancia) AS RI, SUM(Alternativa*Importancia) AS AI, SUM(Importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.OrdenInverso='S', IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) - Opcion + 1, Opcion) AS Respuesta, 
					IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) AS Alternativa, 
					COALESCE(IC.Importancia,1) AS Importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.IdPregunta = R.IdPregunta
					INNER JOIN Items I ON
						I.IdPregunta = P.IdPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.IdCarrera = R.IdCarrera AND IC.IdSeccion = I.IdSeccion AND
						IC.IdFormulario = I.IdFormulario AND IC.IdPregunta = I.IdPregunta
					LEFT JOIN Opciones O ON
						O.IdPregunta = P.IdPregunta
			WHERE	R.IdClave = pIdClave AND R.IdMateria = pIdMateria AND 
					R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND 
					R.IdFormulario = pIdFormulario AND R.Opcion IS NOT NULL
			GROUP BY O.IdPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_seccion`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT,
	OUT Indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET Indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*Importancia) AS RI, SUM(Alternativa*Importancia) AS AI, SUM(Importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.OrdenInverso='S', IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) - Opcion + 1, Opcion) AS Respuesta, 
					IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) AS Alternativa, 
					COALESCE(IC.Importancia,1) AS Importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.IdPregunta = R.IdPregunta
					INNER JOIN Items I ON
						I.IdPregunta = P.IdPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.IdCarrera = R.IdCarrera AND IC.IdSeccion = I.IdSeccion AND
						IC.IdFormulario = I.IdFormulario AND IC.IdPregunta = I.IdPregunta
					LEFT JOIN Opciones O ON
						O.IdPregunta = P.IdPregunta
			WHERE	R.IdClave = pIdClave AND R.IdMateria = pIdMateria AND 
					R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND 
					R.IdFormulario = pIdFormulario AND IC.IdSeccion = pIdSeccion AND 
					R.Opcion IS NOT NULL
			GROUP BY O.IdPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_docente`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT,
	pIdDocente INT,
	OUT Indice FLOAT)
BEGIN
	-- calculo el indice con las sumas realizadas
	SET Indice = (
	SELECT COALESCE(10/C * (C*RI/T - 1) / (AI/T - 1), 0)
	FROM(
		-- realizo las sumatorias necesarias
		SELECT SUM(Respuesta*Importancia) AS RI, SUM(Alternativa*Importancia) AS AI, SUM(Importancia) AS T, COUNT(Respuesta) AS C
		FROM(
			-- obtener datos de respuestas y preguntas(importancias, cantidad de opciones, etc)
			SELECT	IF(P.OrdenInverso='S', IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) - Opcion + 1, Opcion) AS Respuesta, 
					IF(P.Tipo='N',(LimiteSuperior-LimiteInferior)/Paso+1,COUNT(IdOpcion)) AS Alternativa, 
					COALESCE(IC.Importancia,1) AS Importancia
			FROM	Respuestas R
					INNER JOIN Preguntas P ON
						P.IdPregunta = R.IdPregunta
					INNER JOIN Items I ON
						I.IdPregunta = P.IdPregunta
					LEFT JOIN Items_Carreras IC ON
						IC.IdCarrera = R.IdCarrera AND IC.IdSeccion = I.IdSeccion AND
						IC.IdFormulario = I.IdFormulario AND IC.IdPregunta = I.IdPregunta
					LEFT JOIN Opciones O ON
						O.IdPregunta = P.IdPregunta
			WHERE	R.IdClave = pIdClave AND R.IdMateria = pIdMateria AND 
					R.IdCarrera = pIdCarrera AND R.IdEncuesta = pIdEncuesta AND 
					R.IdFormulario = pIdFormulario AND IC.IdSeccion = pIdSeccion AND 
					R.Opcion IS NOT NULL AND R.IdDocente = pIdDocente
			GROUP BY O.IdPregunta
		)Datos
	)Sumas
	);
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente_clave`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_docente_clave`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT,
	pIdDocente INT)
BEGIN
	DECLARE Indice FLOAT;
	CALL esp_indice_docente(pIdClave, pIdMateria, pIdCarrera, 
							pIdEncuesta, pIdFormulario, pIdSeccion,
							pIdDocente, Indice);
	SELECT ROUND(Indice,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_docente_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_docente_materia`(
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT,
	pIdDocente INT)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pIdClave INT;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT IdClave 
		FROM Claves
		WHERE	IdMateria = pIdMateria AND IdCarrera = pIdCarrera AND
				IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pIdClave;
		IF NOT done THEN
			CALL esp_indice_docente(pIdClave, pIdMateria, pIdCarrera, 
									pIdEncuesta, pIdFormulario, pIdSeccion, 
									pIdDocente, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_global_carrera`(
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pIdClave INT;
	DECLARE pIdMateria INT;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT	IdClave, IdMateria
		FROM	Claves
		WHERE	IdCarrera = pIdCarrera AND
				IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de cada materia tomar el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pIdClave, pIdMateria;
		IF NOT done THEN
			CALL esp_indice_global(pIdClave, pIdMateria, pIdCarrera, pIdEncuesta, pIdFormulario, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_global_materia`(
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pIdClave INT;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT IdClave 
		FROM Claves
		WHERE	IdMateria = pIdMateria AND IdCarrera = pIdCarrera AND
				IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pIdClave;
		IF NOT done THEN
			CALL esp_indice_global(pIdClave, pIdMateria, pIdCarrera, pIdEncuesta, pIdFormulario, indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_global_clave`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_global_clave`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT)
BEGIN
	DECLARE Indice FLOAT;
	CALL esp_indice_global(	pIdClave, pIdMateria, pIdCarrera, 
							pIdEncuesta, pIdFormulario, Indice);
	SELECT ROUND(Indice,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_seccion_carrera`(
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pIdClave INT;
	DECLARE pIdMateria SMALLINT;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT IdClave, IdMateria
		FROM Claves
		WHERE	IdCarrera = pIdCarrera AND
				IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pIdClave, pIdMateria;
		IF NOT done THEN
			CALL esp_indice_seccion(pIdClave, pIdMateria, pIdCarrera, 
									pIdEncuesta, pIdFormulario, pIdSeccion, 
									indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_clave`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_seccion_clave`(
	pIdClave INT,
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT)
BEGIN
	DECLARE Indice FLOAT;
	CALL esp_indice_seccion(pIdClave, pIdMateria, pIdCarrera, 
							pIdEncuesta, pIdFormulario, pIdSeccion,
							Indice);
	SELECT ROUND(Indice,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_indice_seccion_materia`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_indice_seccion_materia`(
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT,
	pIdSeccion INT)
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE indice FLOAT;
	DECLARE pIdClave INT;
	DECLARE s FLOAT DEFAULT 0;
	DECLARE n INT DEFAULT 0;
	DECLARE cur CURSOR FOR 
		SELECT IdClave 
		FROM Claves
		WHERE	IdMateria = pIdMateria AND IdCarrera = pIdCarrera AND
				IdEncuesta = pIdEncuesta AND IdFormulario = pIdFormulario;  
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
	-- por cada clave de la encuesta, calcular el indice para calcular el indice promedio
	OPEN cur;
	REPEAT
		FETCH cur INTO pIdClave;
		IF NOT done THEN
			CALL esp_indice_seccion(pIdClave, pIdMateria, pIdCarrera, 
									pIdEncuesta, pIdFormulario, pIdSeccion, 
									indice);
			SET s = s + indice;
			SET n = n + 1;
		END IF;
	UNTIL done END REPEAT;
	CLOSE cur;
	-- devolver el indice promedio
	SELECT ROUND(s/n,2) AS Indice;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_listar_docentes_encuesta`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_listar_docentes_encuesta`(
	pIdMateria SMALLINT,
	pIdCarrera SMALLINT,
	pIdEncuesta INT,
	pIdFormulario INT)
BEGIN
	SELECT DISTINCT P.IdPersona, P.IdUsuario, P.Apellido, P.Nombre
	FROM 	Respuestas R 
			INNER JOIN Personas P ON P.IdPersona = R.IdDocente
			LEFT JOIN Docentes_Materias DM ON DM.IdDocente = R.IdDocente AND DM.IdMateria = R.IdMateria
	WHERE   R.IdMateria = pIdMateria AND R.IdCarrera = pIdCarrera AND 
			R.IdEncuesta = pIdEncuesta AND R.IdFormulario = pIdFormulario
	ORDER BY DM.OrdenFormulario;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_materias_carrera`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_buscar_materias_carrera`(
	pIdCarrera SMALLINT,
	pNombre VARCHAR(60))
BEGIN
	SELECT	M.IdMateria, M.Nombre, M.Codigo, M.Alumnos
	FROM	Materias M INNER JOIN Materias_Carreras MC ON 
			M.IdMateria = MC.IdMateria
	WHERE	MC.IdCarrera = pIdCarrera AND M.Nombre like CONCAT('%',pNombre,'%');
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS `esp_buscar_departamentos`;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `esp_buscar_departamentos`(
	pNombre VARCHAR(60))
BEGIN
	IF COALESCE(pNombre,'') != '' THEN
		SELECT	IdDepartamento, IdJefeDepartamento, Nombre
		FROM	Departamentos
		WHERE	Nombre like CONCAT('%',pNombre,'%');
	END IF;
END $$