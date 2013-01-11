<?php

/**
 * 
 */
class Respuesta extends CI_Model{
	var $IdRespuesta;
  var $IdPregunta;
  var $IdClave;
  var $IdMateria;
  var $IdCarrera;
  var $IdEncuesta;
  var $IdFormulario;
  var $IdDocente;
  var $Opcion;
  var $Texto;
  
  function __construct(){
    parent::__construct();
  }
}

?>