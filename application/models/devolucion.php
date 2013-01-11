<?php

/**
 * 
 */
class Devolucion extends CI_Model{
	var $IdDevolucion;
  var $IdMateria;
  var $IdCarrera;
  var $IdEncuesta;
  var $IdFormulario;
  var $Fecha;
  var $Fortalezas;
  var $Debilidades;
  var $Alumnos;
  var $Docentes;
  var $Mejoras;
  
  function __construct(){
    parent::__construct();
  }
}

?>