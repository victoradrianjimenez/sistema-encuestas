<?php

/**
 * 
 */
class Item extends Pregunta{
  var $idItem;
  var $idSeccion;
  var $idFormulario;
  var $posicion;
  var $importancia=null;
  
  
	function __construct(){
		parent::__construct();
	}
  
}
