<?php

/**
 * 
 */
class Persona extends CI_Model{
  var $IdPersona;
  var $Apellido;
  var $Nombre;
  var $Usuario;
  var $Email;
  var $Contraseña;
  var $UltimoAcceso;
  var $Estado;

	function __construct() {
	  parent::__construct();
	}

}


?>