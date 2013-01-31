DROP SCHEMA `sistema_encuestas`;

CREATE SCHEMA `sistema_encuestas` DEFAULT CHARACTER SET utf8;

USE `sistema_encuestas`;
--

-- ER/Studio 7.1 SQL Code Generation

-- Project :      Sistema Encuestas.dm1

--

-- Date Created : Wednesday, January 30, 2013 03:53:39

-- Target DBMS : MySQL 5.x

--



-- 

-- TABLE: Alumnos 

--



CREATE TABLE Alumnos(

    cx        CHAR(7)        NOT NULL,

    nombre    VARCHAR(80)    NOT NULL,

    PRIMARY KEY (cx)

)ENGINE=INNODB

;







-- 

-- TABLE: Alumnos_Materias 

--



CREATE TABLE Alumnos_Materias(

    cx                 CHAR(7)     NOT NULL,

    idMateria          SMALLINT UNSIGNED  NOT NULL,

    fechaIncripcion    DATE        NOT NULL,

    completada         CHAR(1)     DEFAULT 'N' NOT NULL,

    PRIMARY KEY (cx, idMateria)

)ENGINE=INNODB

;







-- 

-- TABLE: Carreras 

--



CREATE TABLE Carreras(

    IdCarrera            SMALLINT UNSIGNED  NOT NULL,

    idDepartamento       SMALLINT UNSIGNED  NOT NULL,

    idDirectorCarrera    INT UNSIGNED,

    nombre               VARCHAR(60)    NOT NULL,

    plan                 SMALLINT UNSIGNED  NOT NULL,

    PRIMARY KEY (IdCarrera)

)ENGINE=INNODB

;







-- 

-- TABLE: Claves 

--



CREATE TABLE Claves(

    idClave         INT UNSIGNED  NOT NULL,

    idMateria       SMALLINT UNSIGNED  NOT NULL,

    IdCarrera       SMALLINT UNSIGNED  NOT NULL,

    idEncuesta      INT UNSIGNED  NOT NULL,

    idFormulario    INT UNSIGNED  NOT NULL,

    clave           CHAR(16)    NOT NULL,

    tipo            CHAR(1)     NOT NULL,

    generada        DATETIME    NOT NULL,

    utilizada       DATETIME,

    PRIMARY KEY (idClave, idMateria, IdCarrera, idEncuesta, idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Departamentos 

--



CREATE TABLE Departamentos(

    idDepartamento        SMALLINT UNSIGNED  NOT NULL,

    idJefeDepartamento    INT UNSIGNED,

    nombre                VARCHAR(60)    NOT NULL,

    PRIMARY KEY (idDepartamento)

)ENGINE=INNODB

;







-- 

-- TABLE: Devoluciones 

--



CREATE TABLE Devoluciones(

    idDevolucion    INT UNSIGNED  NOT NULL,

    idMateria       SMALLINT UNSIGNED  NOT NULL,

    idEncuesta      INT UNSIGNED  NOT NULL,

    idFormulario    INT UNSIGNED  NOT NULL,

    fecha           DATETIME    NOT NULL,

    fortalezas      TEXT,

    debilidades     TEXT,

    alumnos         TEXT,

    docentes        TEXT,

    mejoras         TEXT,

    PRIMARY KEY (idDevolucion, idMateria, idEncuesta, idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Docentes_Materias 

--



CREATE TABLE Docentes_Materias(

    idDocente          INT UNSIGNED   NOT NULL,

    idMateria          SMALLINT UNSIGNED  NOT NULL,

    ordenFormulario    TINYINT UNSIGNED  NOT NULL,

    cargo              VARCHAR(40),

    PRIMARY KEY (idDocente, idMateria)

)ENGINE=INNODB

;







-- 

-- TABLE: Encuestas 

--



CREATE TABLE Encuestas(

    idEncuesta      INT UNSIGNED  NOT NULL,

    idFormulario    INT UNSIGNED  NOT NULL,

    año             SMALLINT UNSIGNED  NOT NULL,

    cuatrimestre    TINYINT UNSIGNED  NOT NULL,

    fechaInicio     DATETIME    NOT NULL,

    fechaFin        DATETIME,

    PRIMARY KEY (idEncuesta, idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Formularios 

--



CREATE TABLE Formularios(

    idFormulario            INT UNSIGNED    NOT NULL,

    nombre                  VARCHAR(60)     NOT NULL,

    titulo                  VARCHAR(200)    NOT NULL,

    descripcion             VARCHAR(200),

    creacion                DATETIME        NOT NULL,

    preguntasAdicionales    TINYINT UNSIGNED  NOT NULL,

    PRIMARY KEY (idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Grupos 

--



CREATE TABLE Grupos(

    id             INT UNSIGNED    AUTO_INCREMENT,

    name           VARCHAR(20)     NOT NULL,

    description    VARCHAR(100)    NOT NULL,

    PRIMARY KEY (id)

)ENGINE=INNODB

;







-- 

-- TABLE: Items 

--



CREATE TABLE Items(

    idSeccion       INT UNSIGNED  NOT NULL,

    idFormulario    INT UNSIGNED  NOT NULL,

    idPregunta      INT UNSIGNED  NOT NULL,

    IdCarrera       SMALLINT UNSIGNED,

    posicion        TINYINT UNSIGNED  NOT NULL,

    PRIMARY KEY (idSeccion, idFormulario, idPregunta)

)ENGINE=INNODB

;







-- 

-- TABLE: Items_Carreras 

--



CREATE TABLE Items_Carreras(

    IdCarrera       SMALLINT UNSIGNED  NOT NULL,

    idSeccion       INT UNSIGNED     NOT NULL,

    idFormulario    INT UNSIGNED     NOT NULL,

    idPregunta      INT UNSIGNED     NOT NULL,

    posicion        TINYINT UNSIGNED NOT NULL,

    importancia     DECIMAL(3, 2) UNSIGNED  DEFAULT 1 NOT NULL,

    PRIMARY KEY (IdCarrera, idSeccion, idFormulario, idPregunta)

)ENGINE=INNODB

;







-- 

-- TABLE: Materias 

--



CREATE TABLE Materias(

    idMateria    SMALLINT UNSIGNED  NOT NULL,

    nombre       VARCHAR(60)    NOT NULL,

    codigo       CHAR(5)        NOT NULL,

    alumnos      SMALLINT UNSIGNED  DEFAULT 0 NOT NULL,

    PRIMARY KEY (idMateria)

)ENGINE=INNODB

;







-- 

-- TABLE: Materias_Carreras 

--



CREATE TABLE Materias_Carreras(

    idMateria    SMALLINT UNSIGNED  NOT NULL,

    IdCarrera    SMALLINT UNSIGNED  NOT NULL,

    PRIMARY KEY (idMateria, IdCarrera)

)ENGINE=INNODB

;







-- 

-- TABLE: Opciones 

--



CREATE TABLE Opciones(

    idOpcion      SMALLINT UNSIGNED  NOT NULL,

    idPregunta    INT UNSIGNED   NOT NULL,

    texto         VARCHAR(40)    NOT NULL,

    PRIMARY KEY (idOpcion, idPregunta)

)ENGINE=INNODB

;







-- 

-- TABLE: Preguntas 

--



CREATE TABLE Preguntas(

    idPregunta        INT UNSIGNED     NOT NULL,

    IdCarrera         SMALLINT UNSIGNED,

    texto             VARCHAR(200)     NOT NULL,

    descripcion       VARCHAR(200),

    creacion          DATETIME         NOT NULL,

    tipo              CHAR(1)          NOT NULL,

    obligatoria       CHAR(1)          NOT NULL,

    ordenInverso      CHAR(1)          NOT NULL,

    limiteInferior    DECIMAL(7, 2),

    limiteSuperior    DECIMAL(7, 2),

    paso              DECIMAL(7, 2) UNSIGNED,

    unidad            VARCHAR(10),

    PRIMARY KEY (idPregunta)

)ENGINE=INNODB

;







-- 

-- TABLE: Respuestas 

--



CREATE TABLE Respuestas(

    idRespuesta     INT UNSIGNED  NOT NULL,

    idPregunta      INT UNSIGNED  NOT NULL,

    idClave         INT UNSIGNED  NOT NULL,

    idMateria       SMALLINT UNSIGNED  NOT NULL,

    IdCarrera       SMALLINT UNSIGNED  NOT NULL,

    idEncuesta      INT UNSIGNED  NOT NULL,

    idFormulario    INT UNSIGNED  NOT NULL,

    idDocente       INT UNSIGNED,

    opcion          TINYINT UNSIGNED,

    texto           TEXT,

    PRIMARY KEY (idRespuesta, idPregunta, idClave, idMateria, IdCarrera, idEncuesta, idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Secciones 

--



CREATE TABLE Secciones(

    idSeccion       INT UNSIGNED    NOT NULL,

    idFormulario    INT UNSIGNED    NOT NULL,

    IdCarrera       SMALLINT UNSIGNED,

    texto           VARCHAR(200)    NOT NULL,

    descripcion     VARCHAR(200),

    tipo            CHAR(1)         DEFAULT 'N' NOT NULL,

    PRIMARY KEY (idSeccion, idFormulario)

)ENGINE=INNODB

;







-- 

-- TABLE: Usuarios 

--



CREATE TABLE Usuarios(

    id                         INT UNSIGNED     AUTO_INCREMENT,

    ip_address                 VARBINARY(10)    NOT NULL,

    username                   VARCHAR(100)     NOT NULL,

    password                   VARCHAR(80)      NOT NULL,

    salt                       VARCHAR(40),

    email                      VARCHAR(100)     NOT NULL,

    activation_code            VARCHAR(40),

    forgotten_password_code    VARCHAR(40),

    forgotten_password_time    INT UNSIGNED,

    remember_code              VARCHAR(40),

    created_on                 INT UNSIGNED     NOT NULL,

    last_login                 INT UNSIGNED,

    active                     TINYINT UNSIGNED,

    nombre                     VARCHAR(40),

    apellido                   VARCHAR(40)      NOT NULL,

    PRIMARY KEY (id)

)ENGINE=INNODB

;







-- 

-- TABLE: Usuarios_Grupos 

--



CREATE TABLE Usuarios_Grupos(

    id            INT UNSIGNED  AUTO_INCREMENT,

    id_usuario    INT UNSIGNED  NOT NULL,

    id_grupo      INT UNSIGNED  NOT NULL,

    PRIMARY KEY (id)

)ENGINE=INNODB

;







-- 

-- TABLE: Alumnos_Materias 

--



ALTER TABLE Alumnos_Materias ADD CONSTRAINT RefAlumnos48 

    FOREIGN KEY (cx)

    REFERENCES Alumnos(cx)

;



ALTER TABLE Alumnos_Materias ADD CONSTRAINT RefMaterias84 

    FOREIGN KEY (idMateria)

    REFERENCES Materias(idMateria)

;





-- 

-- TABLE: Carreras 

--



ALTER TABLE Carreras ADD CONSTRAINT RefDepartamentos65 

    FOREIGN KEY (idDepartamento)

    REFERENCES Departamentos(idDepartamento)

;



ALTER TABLE Carreras ADD CONSTRAINT RefUsuarios81 

    FOREIGN KEY (idDirectorCarrera)

    REFERENCES Usuarios(id)

;





-- 

-- TABLE: Claves 

--



ALTER TABLE Claves ADD CONSTRAINT RefMaterias_Carreras70 

    FOREIGN KEY (idMateria, IdCarrera)

    REFERENCES Materias_Carreras(idMateria, IdCarrera)

;



ALTER TABLE Claves ADD CONSTRAINT RefEncuestas45 

    FOREIGN KEY (idEncuesta, idFormulario)

    REFERENCES Encuestas(idEncuesta, idFormulario)

;





-- 

-- TABLE: Departamentos 

--



ALTER TABLE Departamentos ADD CONSTRAINT RefUsuarios80 

    FOREIGN KEY (idJefeDepartamento)

    REFERENCES Usuarios(id)

;





-- 

-- TABLE: Devoluciones 

--



ALTER TABLE Devoluciones ADD CONSTRAINT RefEncuestas49 

    FOREIGN KEY (idEncuesta, idFormulario)

    REFERENCES Encuestas(idEncuesta, idFormulario)

;



ALTER TABLE Devoluciones ADD CONSTRAINT RefMaterias71 

    FOREIGN KEY (idMateria)

    REFERENCES Materias(idMateria)

;





-- 

-- TABLE: Docentes_Materias 

--



ALTER TABLE Docentes_Materias ADD CONSTRAINT RefUsuarios82 

    FOREIGN KEY (idDocente)

    REFERENCES Usuarios(id)

;



ALTER TABLE Docentes_Materias ADD CONSTRAINT RefMaterias39 

    FOREIGN KEY (idMateria)

    REFERENCES Materias(idMateria)

;





-- 

-- TABLE: Encuestas 

--



ALTER TABLE Encuestas ADD CONSTRAINT RefFormularios43 

    FOREIGN KEY (idFormulario)

    REFERENCES Formularios(idFormulario)

;





-- 

-- TABLE: Items 

--



ALTER TABLE Items ADD CONSTRAINT RefSecciones58 

    FOREIGN KEY (idSeccion, idFormulario)

    REFERENCES Secciones(idSeccion, idFormulario)

;



ALTER TABLE Items ADD CONSTRAINT RefPreguntas59 

    FOREIGN KEY (idPregunta)

    REFERENCES Preguntas(idPregunta)

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

    FOREIGN KEY (idSeccion, idFormulario, idPregunta)

    REFERENCES Items(idSeccion, idFormulario, idPregunta)

;





-- 

-- TABLE: Materias_Carreras 

--



ALTER TABLE Materias_Carreras ADD CONSTRAINT RefCarreras36 

    FOREIGN KEY (IdCarrera)

    REFERENCES Carreras(IdCarrera)

;



ALTER TABLE Materias_Carreras ADD CONSTRAINT RefMaterias37 

    FOREIGN KEY (idMateria)

    REFERENCES Materias(idMateria)

;





-- 

-- TABLE: Opciones 

--



ALTER TABLE Opciones ADD CONSTRAINT RefPreguntas55 

    FOREIGN KEY (idPregunta)

    REFERENCES Preguntas(idPregunta)

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



ALTER TABLE Respuestas ADD CONSTRAINT RefClaves51 

    FOREIGN KEY (idClave, idMateria, IdCarrera, idEncuesta, idFormulario)

    REFERENCES Claves(idClave, idMateria, IdCarrera, idEncuesta, idFormulario)

;



ALTER TABLE Respuestas ADD CONSTRAINT RefPreguntas57 

    FOREIGN KEY (idPregunta)

    REFERENCES Preguntas(idPregunta)

;



ALTER TABLE Respuestas ADD CONSTRAINT RefUsuarios83 

    FOREIGN KEY (idDocente)

    REFERENCES Usuarios(id)

;





-- 

-- TABLE: Secciones 

--



ALTER TABLE Secciones ADD CONSTRAINT RefFormularios52 

    FOREIGN KEY (idFormulario)

    REFERENCES Formularios(idFormulario)

;



ALTER TABLE Secciones ADD CONSTRAINT RefCarreras73 

    FOREIGN KEY (IdCarrera)

    REFERENCES Carreras(IdCarrera)

;





-- 

-- TABLE: Usuarios_Grupos 

--



ALTER TABLE Usuarios_Grupos ADD CONSTRAINT RefGrupos772 

    FOREIGN KEY (id_grupo)

    REFERENCES Grupos(id)

;



ALTER TABLE Usuarios_Grupos ADD CONSTRAINT RefUsuarios782 

    FOREIGN KEY (id_usuario)

    REFERENCES Usuarios(id)

;





