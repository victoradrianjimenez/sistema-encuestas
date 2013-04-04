--
-- ER/Studio 7.1 SQL Code Generation
-- Project :      Sistema Encuestas.dm1
--
-- Date Created : Thursday, April 04, 2013 00:50:50
-- Target DBMS : MySQL 5.x
--

-- 
-- TABLE: captcha 
--

CREATE TABLE captcha(
    captcha_id      BIGINT UNSIGNED  AUTO_INCREMENT,
    captcha_time    INT UNSIGNED   NOT NULL,
    ip_address      VARCHAR(16)    DEFAULT '0' NOT NULL,
    word            VARCHAR(20)    NOT NULL,
    PRIMARY KEY (captcha_id)
)ENGINE=INNODB
;



-- 
-- TABLE: Carreras 
--

CREATE TABLE Carreras(
    idCarrera             SMALLINT UNSIGNED  NOT NULL,
    idDepartamento        SMALLINT UNSIGNED  NOT NULL,
    idDirectorCarrera     INT UNSIGNED,
    idOrganizador         INT UNSIGNED,
    nombre                VARCHAR(60)    NOT NULL,
    plan                  SMALLINT UNSIGNED  NOT NULL,
    publicarInformes      CHAR(1)        DEFAULT 'N' NOT NULL,
    publicarHistoricos    CHAR(1)        DEFAULT 'N' NOT NULL,
    PRIMARY KEY (idCarrera)
)ENGINE=INNODB
;



-- 
-- TABLE: Claves 
--

CREATE TABLE Claves(
    idClave         INT UNSIGNED  NOT NULL,
    idMateria       SMALLINT UNSIGNED  NOT NULL,
    idCarrera       SMALLINT UNSIGNED  NOT NULL,
    idEncuesta      INT UNSIGNED  NOT NULL,
    idFormulario    INT UNSIGNED  NOT NULL,
    clave           CHAR(16)    NOT NULL,
    generada        DATETIME    NOT NULL,
    utilizada       DATETIME,
    PRIMARY KEY (idClave, idMateria, idCarrera, idEncuesta, idFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Departamentos 
--

CREATE TABLE Departamentos(
    idDepartamento        SMALLINT UNSIGNED  NOT NULL,
    idJefeDepartamento    INT UNSIGNED,
    nombre                VARCHAR(60)    NOT NULL,
    publicarInformes      CHAR(1)        DEFAULT 'N' NOT NULL,
    publicarHistoricos    CHAR(1)        DEFAULT 'N' NOT NULL,
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
    tipoAcceso         CHAR(1)        DEFAULT 'D' NOT NULL,
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
    tipo            CHAR(1)     DEFAULT 'A' NOT NULL,
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
    preguntasAdicionales    TINYINT         NOT NULL,
    PRIMARY KEY (idFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Grupos 
--

CREATE TABLE Grupos(
    id             INT UNSIGNED    NOT NULL,
    name           VARCHAR(20)     NOT NULL,
    description    VARCHAR(100)    NOT NULL,
    PRIMARY KEY (id)
)ENGINE=INNODB
;



-- 
-- TABLE: Imagenes 
--

CREATE TABLE Imagenes(
    idImagen    INT UNSIGNED NOT NULL,
    imagen      LONGBLOB     NOT NULL,
    tipo        CHAR(200)    NOT NULL,
    PRIMARY KEY (idImagen)
)ENGINE=INNODB
;



-- 
-- TABLE: Items 
--

CREATE TABLE Items(
    idItem          INT UNSIGNED  NOT NULL,
    idSeccion       INT UNSIGNED  NOT NULL,
    idFormulario    INT UNSIGNED  NOT NULL,
    idPregunta      INT UNSIGNED  NOT NULL,
    idCarrera       SMALLINT UNSIGNED,
    posicion        TINYINT UNSIGNED  NOT NULL,
    PRIMARY KEY (idSeccion, idFormulario, idItem)
)ENGINE=INNODB
;



-- 
-- TABLE: Items_Carreras 
--

CREATE TABLE Items_Carreras(
    idCarrera       SMALLINT UNSIGNED  NOT NULL,
    idItem          INT UNSIGNED     NOT NULL,
    idSeccion       INT UNSIGNED     NOT NULL,
    idFormulario    INT UNSIGNED     NOT NULL,
    importancia     DECIMAL(3, 2) UNSIGNED  DEFAULT 1 NOT NULL,
    PRIMARY KEY (idCarrera, idSeccion, idFormulario, idItem)
)ENGINE=INNODB
;



-- 
-- TABLE: Materias 
--

CREATE TABLE Materias(
    idMateria               SMALLINT UNSIGNED  NOT NULL,
    nombre                  VARCHAR(60)    NOT NULL,
    codigo                  CHAR(5)        NOT NULL,
    publicarInformes        CHAR(1)        DEFAULT 'N' NOT NULL,
    publicarHistoricos      CHAR(1)        DEFAULT 'N' NOT NULL,
    publicarDevoluciones    CHAR(1)        DEFAULT 'N' NOT NULL,
    PRIMARY KEY (idMateria)
)ENGINE=INNODB
;



-- 
-- TABLE: Materias_Carreras 
--

CREATE TABLE Materias_Carreras(
    idMateria    SMALLINT UNSIGNED  NOT NULL,
    idCarrera    SMALLINT UNSIGNED  NOT NULL,
    alumnos      SMALLINT UNSIGNED  DEFAULT '0' NOT NULL,
    PRIMARY KEY (idMateria, idCarrera)
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
    tipo              CHAR(1)          NOT NULL,
    texto             VARCHAR(250)     NOT NULL,
    descripcion       VARCHAR(250),
    creacion          DATETIME         NOT NULL,
    modoIndice        CHAR(1)          NOT NULL,
    limiteInferior    DECIMAL(7, 2),
    limiteSuperior    DECIMAL(7, 2),
    paso              DECIMAL(7, 2),
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
    idCarrera       SMALLINT UNSIGNED  NOT NULL,
    idEncuesta      INT UNSIGNED  NOT NULL,
    idFormulario    INT UNSIGNED  NOT NULL,
    idDocente       INT UNSIGNED,
    opcion          TINYINT UNSIGNED,
    texto           TEXT,
    PRIMARY KEY (idRespuesta, idPregunta, idClave, idMateria, idCarrera, idEncuesta, idFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Secciones 
--

CREATE TABLE Secciones(
    idSeccion       INT UNSIGNED    NOT NULL,
    idFormulario    INT UNSIGNED    NOT NULL,
    idCarrera       SMALLINT UNSIGNED,
    texto           VARCHAR(200)    NOT NULL,
    descripcion     VARCHAR(200),
    tipo            CHAR(1)         DEFAULT 'N' NOT NULL,
    PRIMARY KEY (idSeccion, idFormulario)
)ENGINE=INNODB
;



-- 
-- TABLE: Tipo_Pregunta 
--

CREATE TABLE Tipo_Pregunta(
    idTipo    CHAR(1)     NOT NULL,
    tipo      CHAR(20)    NOT NULL,
    PRIMARY KEY (idTipo)
)ENGINE=INNODB
;



-- 
-- TABLE: Usuarios 
--

CREATE TABLE Usuarios(
    id                         INT UNSIGNED     AUTO_INCREMENT,
    idImagen                   INT UNSIGNED,
    apellido                   VARCHAR(40)      NOT NULL,
    nombre                     VARCHAR(40),
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
-- INDEX: AK_word_captcha 
--

CREATE UNIQUE INDEX AK_word_captcha ON captcha(word)
;
-- 
-- INDEX: AK_Nombre_Plan_Carreras 
--

CREATE UNIQUE INDEX AK_Nombre_Plan_Carreras ON Carreras(nombre, plan)
;
-- 
-- INDEX: AK_claves 
--

CREATE UNIQUE INDEX AK_claves ON Claves(idEncuesta, idFormulario, clave)
;
-- 
-- INDEX: AK_Nombre_Departamentos 
--

CREATE UNIQUE INDEX AK_Nombre_Departamentos ON Departamentos(nombre)
;
-- 
-- INDEX: AK_nombre_formulario 
--

CREATE UNIQUE INDEX AK_nombre_formulario ON Formularios(nombre)
;
-- 
-- INDEX: AK_Codigo_Materias 
--

CREATE UNIQUE INDEX AK_Codigo_Materias ON Materias(codigo)
;
-- 
-- INDEX: AK_email 
--

CREATE UNIQUE INDEX AK_email ON Usuarios(email)
;
-- 
-- INDEX: AK_username 
--

CREATE UNIQUE INDEX AK_username ON Usuarios(username)
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

ALTER TABLE Carreras ADD CONSTRAINT RefUsuarios88 
    FOREIGN KEY (idOrganizador)
    REFERENCES Usuarios(id)
;


-- 
-- TABLE: Claves 
--

ALTER TABLE Claves ADD CONSTRAINT RefEncuestas45 
    FOREIGN KEY (idEncuesta, idFormulario)
    REFERENCES Encuestas(idEncuesta, idFormulario)
;

ALTER TABLE Claves ADD CONSTRAINT RefMaterias_Carreras70 
    FOREIGN KEY (idMateria, idCarrera)
    REFERENCES Materias_Carreras(idMateria, idCarrera)
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

ALTER TABLE Docentes_Materias ADD CONSTRAINT RefMaterias39 
    FOREIGN KEY (idMateria)
    REFERENCES Materias(idMateria)
;

ALTER TABLE Docentes_Materias ADD CONSTRAINT RefUsuarios82 
    FOREIGN KEY (idDocente)
    REFERENCES Usuarios(id)
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
    FOREIGN KEY (idCarrera)
    REFERENCES Carreras(idCarrera)
;


-- 
-- TABLE: Items_Carreras 
--

ALTER TABLE Items_Carreras ADD CONSTRAINT RefCarreras68 
    FOREIGN KEY (idCarrera)
    REFERENCES Carreras(idCarrera)
;

ALTER TABLE Items_Carreras ADD CONSTRAINT RefItems69 
    FOREIGN KEY (idItem, idSeccion, idFormulario)
    REFERENCES Items(idItem, idSeccion, idFormulario)
;


-- 
-- TABLE: Materias_Carreras 
--

ALTER TABLE Materias_Carreras ADD CONSTRAINT RefCarreras36 
    FOREIGN KEY (idCarrera)
    REFERENCES Carreras(idCarrera)
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

ALTER TABLE Preguntas ADD CONSTRAINT RefTipo_Pregunta86 
    FOREIGN KEY (tipo)
    REFERENCES Tipo_Pregunta(idTipo)
;


-- 
-- TABLE: Respuestas 
--

ALTER TABLE Respuestas ADD CONSTRAINT RefClaves51 
    FOREIGN KEY (idClave, idMateria, idCarrera, idEncuesta, idFormulario)
    REFERENCES Claves(idClave, idMateria, idCarrera, idEncuesta, idFormulario)
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
    FOREIGN KEY (idCarrera)
    REFERENCES Carreras(idCarrera)
;


-- 
-- TABLE: Usuarios 
--

ALTER TABLE Usuarios ADD CONSTRAINT RefImagenes87 
    FOREIGN KEY (idImagen)
    REFERENCES Imagenes(idImagen)
;


-- 
-- TABLE: Usuarios_Grupos 
--

ALTER TABLE Usuarios_Grupos ADD CONSTRAINT RefGrupos77 
    FOREIGN KEY (id_grupo)
    REFERENCES Grupos(id)
;

ALTER TABLE Usuarios_Grupos ADD CONSTRAINT RefUsuarios78 
    FOREIGN KEY (id_usuario)
    REFERENCES Usuarios(id)
;


