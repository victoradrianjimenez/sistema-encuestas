<?php

/**
 * 
 */
class Gestor_encuestas extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
  /**
   * Obtener una encuesta a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de enucesta
   * @param identificador del formulario usado en la encuesta
   * @return object
   */
  public function dame($idEncuesta, $idFormulario){
    $idEncuesta = $this->db->escape($idEncuesta);
    $idFormulario = $this->db->escape($idFormulario);
    $query = $this->db->query("call esp_dame_encuesta($idEncuesta, $idFormulario)");
    $data = $query->result('Encuesta');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  

}
?>