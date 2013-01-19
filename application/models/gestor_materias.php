<?php

/**
 * 
 */
class Gestor_materias extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
  /**
   * Obtener una materia a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de la materia
   * @return object
   */
  public function dame($idMateria){
    $idMateria = $this->db->escape($idMateria);
    $query = $this->db->query("call esp_dame_materia($idMateria)");
    $data = $query->result('Materia');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
}
?>