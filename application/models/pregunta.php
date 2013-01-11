<?php

/**
 * 
 */
class Pregunta extends CI_Model{
  var $IdPregunta;
  var $IdCarrera;
  var $Texto;
  var $Descripcion;
  var $Creacion;
  var $Tipo;
  var $Obligatoria;
  var $OrdenInverso;
  var $LimiteInferior;
  var $LimiteSuperior;
  var $Paso;
  var $Unidad;
  
  function __construct(){
    parent::__construct();
  }
}

?>