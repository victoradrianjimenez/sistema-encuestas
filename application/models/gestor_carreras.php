<?php

/**
 * 
 */
class Gestor_carreras extends CI_Model{

	function __construct() {
		parent::__construct();
	}



  /**
   * Obtener una carrera a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de la carrera
   * @return object
   */
  public function dameCarrera($idCarrera){
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_dame_carrera($idCarrera)");
    $data = $query->result('Carrera');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
}
?>