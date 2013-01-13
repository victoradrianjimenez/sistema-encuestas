<?php

/**
 * 
 */
class Clave extends CI_Model{
	var $IdClave;
  var $IdMateria;
  var $IdCarrera;
  var $IdEncuesta;
  var $IdFormulario;
  var $Clave;
  var $Tipo;
  var $Generada;
  var $Utilizada;
  
  function __construct(){
    parent::__construct();
  }
    
}

?>