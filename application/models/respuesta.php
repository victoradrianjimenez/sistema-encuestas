<?php

/**
 * 
 */
class Respuesta extends CI_Model{
	var $idRespuesta;
  var $idPregunta;
  var $idClave;
  var $idMateria;
  var $idCarrera;
  var $idEncuesta;
  var $idFormulario;
  var $idDocente;
  var $opcion;
  var $texto;
  
  function __construct(){
    parent::__construct();
  }
}

?>