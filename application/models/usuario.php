<?php

/**
 * 
 */
class Usuario extends CI_Model{
  var $id = null;
  var $username;
  var $password;
  var $email;
  var $last_login;
  var $active;
  var $nombre;
  var $apellido;
  
	function __construct(){
		parent::__construct();
	}

}
