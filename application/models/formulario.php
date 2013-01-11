<?php

/**
 * 
 */
class Formulario extends CI_Model{
  var $IdFormulario;
  var $Nombre;
  var $Titulo;
  var $Descripcion;
  var $Creacion;
  var $PreguntasAdicionales;
  
  function __construct(){
    parent::__construct();
  }
    
}

?>