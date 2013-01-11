<?php

/**
 * 
 */
class Materia extends CI_Model{
  var $IdMateria;
  var $Nombre;
  var $Codigo;
  var $Alumnos;
  
  function __construct(){
    parent::__construct();
  }
}

?>