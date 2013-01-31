<?php

/**
 * 
 */
class Clave extends CI_Model{
	var $idClave;
  var $idMateria;
  var $idCarrera;
  var $idEncuesta;
  var $idFormulario;
  var $clave;
  var $tipo;
  var $generada;
  var $utilizada;
  
  function __construct(){
    parent::__construct();
  }
    
}

?>