<?php

/**
 * 
 */
class Encuesta extends CI_Model{
	var $IdEncuesta;
  var $IdFormulario;
  var $Año;
  var $Cuatrimestre;
  var $FechaInicio;
  var $FechaFin;
  
  function __construct(){
    parent::__construct();
  }
  
  
  /**
   * Buscar una clave (usada o no). Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param clave alfanumérica
   * @return object
   */
  public function buscarClave($clave){
    $clave = $this->db->escape($clave);
    $query = $this->db->query("call esp_buscar_clave($clave)");
    $data = $query->result('Clave');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
}

?>