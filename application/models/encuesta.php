<?php

/**
 * 
 */
class Encuesta extends CI_Model{
	var $IdEncuesta;
  var $IdFormulario;
  var $Año;
  var $Cuatrimestre;
  var $FechaInicio;
  var $FechaFin;
  
  function __construct(){
    parent::__construct();
  }
  
}

?>