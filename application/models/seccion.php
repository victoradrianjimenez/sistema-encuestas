<?php

/**
 * 
 */
class Seccion extends CI_Model{
  var $IdSeccion;
  var $IdCarrera;
  var $IdFormulario;
  var $Texto;
  var $Descripcion;
  var $Tipo;
  
  function __construct(){
    parent::__construct();
  }
}

?>