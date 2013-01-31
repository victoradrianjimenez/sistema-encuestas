<?php

/**
 * 
 */
class Devolucion extends CI_Model{
	var $idDevolucion;
  var $idMateria;
  var $idCarrera;
  var $idEncuesta;
  var $idFormulario;
  var $fecha;
  var $fortalezas;
  var $debilidades;
  var $alumnos;
  var $docentes;
  var $mejoras;
  
  function __construct(){
    parent::__construct();
  }
}

?>