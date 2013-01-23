-- INSTRUCCIONES!!!!!!!!!
-- Crear manualmente una base de datos que se llame "sistema_encuestas"
-- Elegir el charset "UTF-8 - default collation"
-- Ejecutar este script

--
-- ER/Studio 7.1 SQL Code Generation
-- Project :      Sistema Encuestas.dm1
--
-- Date Created : Thursday, January 10, 2013 23:32:23
-- Target DBMS : MySQL 5.x
--

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




-- 
-- TABLE: Alumnos 
--

CREATE TABLE Alumnos(
    CX        CHAR(7)        NOT NULL,
    Nombre    VARCHAR(80)    NOT NULL,
    PRIMARY KEY (CX)
)ENGINE=INNODB
;



-- 
-- TABLE: Alumnos_Materias 
--

CREATE TABLE Alumnos_Materias(
    CX                 CHAR(7)     NOT NULL,
    IdMateria          SMALLINT    NOT NULL,
    FechaIncripcion    DATE        NOT NULL,
    Completada         CHAR(1)     DEFAULT 'N' NOT NULL,
    PRIMARY KEY (CX, IdMateria)
)ENGINE=INNODB
;



-- 
-- TABLE: Carreras 
--

CREATE TABLE Carreras(
    IdCarrera         SMALLINT       NOT NULL,
    IdDepartamento    SMALLINT       NOT NULL,
	IdDirectorCarrera	INT       NULL,
    Nombre            VARCHAR(60)    NOT NULL,
    Plan              SMALLINT       NOT NULL,
    PRIMARY KEY (IdCarrera)
)ENGINE=INNODB
;



-- 
-- TABLE: Claves 
--

CREATE TABLE Claves(
    IdClave         INT         NOT NULL,
    IdMateria       SMALLINT    NOT NULL,
    IdCarrera       SMALLINT    NOT NULL,
    IdEncuesta      INT         NOT NULL,
    IdFormulario    INT         NOT NULL,
    Clave           CHAR(16)    NOT NULL,
    Tipo            CHAR(1)     NOT NULL,
    Generada        DATETIME    NOT NULL,
    Utilizada       DATETIME,
    PRIMARY KEY (IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Departamentos 
--

CREATE TABLE Departamentos(
    IdDepartamento        SMALLINT       NOT NULL,
    IdJefeDepartamento    INT,
    Nombre                VARCHAR(60)    NOT NULL,
    PRIMARY KEY (IdDepartamento)
)ENGINE=INNODB
;



-- 
-- TABLE: Devoluciones 
--

CREATE TABLE Devoluciones(
    IdDevolucion    INT         NOT NULL,
    IdMateria       SMALLINT    NOT NULL,
    IdEncuesta      INT         NOT NULL,
    IdFormulario    INT         NOT NULL,
    Fecha           DATETIME    NOT NULL,
    Fortalezas      TEXT,
    Debilidades     TEXT,
    Alumnos         TEXT,
    Docentes        TEXT,
    Mejoras         TEXT,
    PRIMARY KEY (IdDevolucion, IdMateria, IdEncuesta, IdFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Docentes_Materias 
--

CREATE TABLE Docentes_Materias(
    IdDocente          INT            NOT NULL,
    IdMateria          SMALLINT       NOT NULL,
    TipoAcceso         CHAR(1)        DEFAULT 'D' NOT NULL,
    OrdenFormulario    TINYINT        NOT NULL,
    Cargo              VARCHAR(40),
    PRIMARY KEY (IdDocente, IdMateria)
)ENGINE=INNODB
;



-- 
-- TABLE: Encuestas 
--

CREATE TABLE Encuestas(
    IdEncuesta      INT         NOT NULL,
    IdFormulario    INT         NOT NULL,
    Año             SMALLINT    NOT NULL,
    Cuatrimestre    TINYINT     NOT NULL,
    FechaInicio     DATETIME    NOT NULL,
    FechaFin        DATETIME,
    PRIMARY KEY (IdEncuesta, IdFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Formularios 
--

CREATE TABLE Formularios(
    IdFormulario            INT             NOT NULL,
    Nombre                  VARCHAR(60)     NOT NULL,
    Titulo                  VARCHAR(200)    NOT NULL,
    Descripcion             VARCHAR(200),
    Creacion                DATETIME        NOT NULL,
    PreguntasAdicionales    TINYINT         NOT NULL,
    PRIMARY KEY (IdFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Items 
--

CREATE TABLE Items(
    IdSeccion       INT         NOT NULL,
    IdFormulario    INT         NOT NULL,
    IdPregunta      INT         NOT NULL,
    IdCarrera       SMALLINT,
    Posicion        TINYINT     NOT NULL,
    Tamaño          TINYINT     NOT NULL,
    PRIMARY KEY (IdSeccion, IdFormulario, IdPregunta)
)ENGINE=INNODB
;



-- 
-- TABLE: Items_Carreras 
--

CREATE TABLE Items_Carreras(
    IdCarrera       SMALLINT         NOT NULL,
    IdSeccion       INT              NOT NULL,
    IdFormulario    INT              NOT NULL,
    IdPregunta      INT              NOT NULL,
    Posicion        TINYINT          NOT NULL,
    Tamaño          TINYINT          NOT NULL,
    Importancia     DECIMAL(3, 2)    DEFAULT 1 NOT NULL,
    PRIMARY KEY (IdCarrera, IdSeccion, IdFormulario, IdPregunta)
)ENGINE=INNODB
;



-- 
-- TABLE: Materias 
--

CREATE TABLE Materias(
    IdMateria    SMALLINT       NOT NULL,
    Nombre       VARCHAR(60)    NOT NULL,
    Codigo       CHAR(5)        NOT NULL,
    Alumnos      SMALLINT       DEFAULT 0 NOT NULL,
    PRIMARY KEY (IdMateria)
)ENGINE=INNODB
;



-- 
-- TABLE: Materias_Carreras 
--

CREATE TABLE Materias_Carreras(
    IdMateria    SMALLINT    NOT NULL,
    IdCarrera    SMALLINT    NOT NULL,
    PRIMARY KEY (IdMateria, IdCarrera)
)ENGINE=INNODB
;



-- 
-- TABLE: Opciones 
--

CREATE TABLE Opciones(
    IdOpcion      SMALLINT       NOT NULL,
    IdPregunta    INT            NOT NULL,
    Texto         VARCHAR(40)    NOT NULL,
    PRIMARY KEY (IdOpcion, IdPregunta)
)ENGINE=INNODB
;



-- 
-- TABLE: Personas 
--

CREATE TABLE Personas(
    IdPersona       INT             NOT NULL,
	IdUsuario		mediumint(8) unsigned NULL,
    Apellido        VARCHAR(40)     NOT NULL,
    Nombre          VARCHAR(40),
    Usuario         VARCHAR(40)     NOT NULL,
    Email           VARCHAR(200)    NOT NULL,
    Contraseña      CHAR(64)        NOT NULL,
    UltimoAcceso    DATETIME        NOT NULL,
    Estado          CHAR(1)         DEFAULT 'A' NOT NULL,
    PRIMARY KEY (IdPersona)
)ENGINE=INNODB
;



-- 
-- TABLE: Preguntas 
--

CREATE TABLE Preguntas(
    IdPregunta        INT              NOT NULL,
    IdCarrera         SMALLINT,
    Texto             VARCHAR(200)     NOT NULL,
    Descripcion       VARCHAR(200),
    Creacion          DATETIME         NOT NULL,
    Tipo              CHAR(1)          NOT NULL,
    Obligatoria       CHAR(1)          NOT NULL,
    OrdenInverso      CHAR(1)          NOT NULL,
    LimiteInferior    DECIMAL(7, 2),
    LimiteSuperior    DECIMAL(7, 2),
    Paso              DECIMAL(7, 2),
    Unidad            VARCHAR(10),
    PRIMARY KEY (IdPregunta)
)ENGINE=INNODB
;



-- 
-- TABLE: Respuestas 
--

CREATE TABLE Respuestas(
    IdRespuesta     INT         NOT NULL,
    IdPregunta      INT         NOT NULL,
    IdClave         INT         NOT NULL,
    IdMateria       SMALLINT    NOT NULL,
    IdCarrera       SMALLINT    NOT NULL,
    IdEncuesta      INT         NOT NULL,
    IdFormulario    INT         NOT NULL,
    IdDocente       INT,
    Opcion          TINYINT,
    Texto           TEXT,
    PRIMARY KEY (IdRespuesta, IdPregunta, IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Secciones 
--

CREATE TABLE Secciones(
    IdSeccion       INT             NOT NULL,
    IdFormulario    INT             NOT NULL,
    IdCarrera       SMALLINT,
    Texto           VARCHAR(200)    NOT NULL,
    Descripcion     VARCHAR(200),
    Tipo            CHAR(1)         DEFAULT 'N' NOT NULL,
    PRIMARY KEY (IdSeccion, IdFormulario)
)ENGINE=INNODB
;



-- 
-- INDEX: AK_Nombre_Plan_Carreras 
--

CREATE UNIQUE INDEX AK_Nombre_Plan_Carreras ON Carreras(Nombre, Plan)
;
-- 
-- INDEX: AK_Nombre_Departamentos 
--

CREATE UNIQUE INDEX AK_Nombre_Departamentos ON Departamentos(Nombre)
;
-- 
-- INDEX: AK_Codigo_Materias 
--

CREATE UNIQUE INDEX AK_Codigo_Materias ON Materias(Codigo)
;
-- 
-- INDEX: AK_Email_Personas 
--

CREATE UNIQUE INDEX AK_Email_Personas ON Personas(Email)
;
-- 
-- INDEX: AK_Usuario_Personas 
--

CREATE UNIQUE INDEX AK_Usuario_Personas ON Personas(Usuario)
;

-- 
-- TABLE: Alumnos_Materias 
--

ALTER TABLE Alumnos_Materias ADD CONSTRAINT RefMaterias46 
    FOREIGN KEY (IdMateria)
    REFERENCES Materias(IdMateria)
;

ALTER TABLE Alumnos_Materias ADD CONSTRAINT RefAlumnos48 
    FOREIGN KEY (CX)
    REFERENCES Alumnos(CX)
;


-- 
-- TABLE: Carreras 
--

ALTER TABLE Carreras ADD CONSTRAINT RefDepartamentos65 
    FOREIGN KEY (IdDepartamento)
    REFERENCES Departamentos(IdDepartamento)
;


-- 
-- TABLE: Claves 
--

ALTER TABLE Claves ADD CONSTRAINT RefEncuestas45 
    FOREIGN KEY (IdEncuesta, IdFormulario)
    REFERENCES Encuestas(IdEncuesta, IdFormulario)
;

ALTER TABLE Claves ADD CONSTRAINT RefMaterias_Carreras70 
    FOREIGN KEY (IdMateria, IdCarrera)
    REFERENCES Materias_Carreras(IdMateria, IdCarrera)
;


-- 
-- TABLE: Departamentos 
--

ALTER TABLE Departamentos ADD CONSTRAINT RefPersonas67 
    FOREIGN KEY (IdJefeDepartamento)
    REFERENCES Personas(IdPersona)
;


-- 
-- TABLE: Devoluciones 
--

ALTER TABLE Devoluciones ADD CONSTRAINT RefEncuestas49 
    FOREIGN KEY (IdEncuesta, IdFormulario)
    REFERENCES Encuestas(IdEncuesta, IdFormulario)
;

ALTER TABLE Devoluciones ADD CONSTRAINT RefMaterias71 
    FOREIGN KEY (IdMateria)
    REFERENCES Materias(IdMateria)
;


-- 
-- TABLE: Docentes_Materias 
--

ALTER TABLE Docentes_Materias ADD CONSTRAINT RefPersonas38 
    FOREIGN KEY (IdDocente)
    REFERENCES Personas(IdPersona)
;

ALTER TABLE Docentes_Materias ADD CONSTRAINT RefMaterias39 
    FOREIGN KEY (IdMateria)
    REFERENCES Materias(IdMateria)
;


-- 
-- TABLE: Encuestas 
--

ALTER TABLE Encuestas ADD CONSTRAINT RefFormularios43 
    FOREIGN KEY (IdFormulario)
    REFERENCES Formularios(IdFormulario)
;


-- 
-- TABLE: Items 
--

ALTER TABLE Items ADD CONSTRAINT RefSecciones58 
    FOREIGN KEY (IdSeccion, IdFormulario)
    REFERENCES Secciones(IdSeccion, IdFormulario)
;

ALTER TABLE Items ADD CONSTRAINT RefPreguntas59 
    FOREIGN KEY (IdPregunta)
    REFERENCES Preguntas(IdPregunta)
;

ALTER TABLE Items ADD CONSTRAINT RefCarreras72 
    FOREIGN KEY (IdCarrera)
    REFERENCES Carreras(IdCarrera)
;


-- 
-- TABLE: Items_Carreras 
--

ALTER TABLE Items_Carreras ADD CONSTRAINT RefCarreras68 
    FOREIGN KEY (IdCarrera)
    REFERENCES Carreras(IdCarrera)
;

ALTER TABLE Items_Carreras ADD CONSTRAINT RefItems69 
    FOREIGN KEY (IdSeccion, IdFormulario, IdPregunta)
    REFERENCES Items(IdSeccion, IdFormulario, IdPregunta)
;


-- 
-- TABLE: Materias_Carreras 
--

ALTER TABLE Materias_Carreras ADD CONSTRAINT RefCarreras36 
    FOREIGN KEY (IdCarrera)
    REFERENCES Carreras(IdCarrera)
;

ALTER TABLE Materias_Carreras ADD CONSTRAINT RefMaterias37 
    FOREIGN KEY (IdMateria)
    REFERENCES Materias(IdMateria)
;


-- 
-- TABLE: Opciones 
--

ALTER TABLE Opciones ADD CONSTRAINT RefPreguntas55 
    FOREIGN KEY (IdPregunta)
    REFERENCES Preguntas(IdPregunta)
;


-- 
-- TABLE: Preguntas 
--

ALTER TABLE Preguntas ADD CONSTRAINT RefCarreras56 
    FOREIGN KEY (IdCarrera)
    REFERENCES Carreras(IdCarrera)
;


-- 
-- TABLE: Respuestas 
--

ALTER TABLE Respuestas ADD CONSTRAINT RefPersonas50 
    FOREIGN KEY (IdDocente)
    REFERENCES Personas(IdPersona)
;

ALTER TABLE Respuestas ADD CONSTRAINT RefClaves51 
    FOREIGN KEY (IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario)
    REFERENCES Claves(IdClave, IdMateria, IdCarrera, IdEncuesta, IdFormulario)
;

ALTER TABLE Respuestas ADD CONSTRAINT RefPreguntas57 
    FOREIGN KEY (IdPregunta)
    REFERENCES Preguntas(IdPregunta)
;


-- 
-- TABLE: Secciones 
--

ALTER TABLE Secciones ADD CONSTRAINT RefFormularios52 
    FOREIGN KEY (IdFormulario)
    REFERENCES Formularios(IdFormulario)
;

ALTER TABLE Secciones ADD CONSTRAINT RefCarreras74 
    FOREIGN KEY (IdCarrera)
    REFERENCES Carreras(IdCarrera)
;


