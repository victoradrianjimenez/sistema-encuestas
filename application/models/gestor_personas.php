<?php

/**
 * 
 */
class Gestor_personas extends CI_Model{
	
	function __construct() {
    parent::__construct();		
	}
  
  /**
   * Obtener los datos de una persona a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de persona
   * @return object
   */
  public function damePersona($IdPersona){
    $IdPersona = $this->db->escape($IdPersona);
    $query = $this->db->query("call esp_dame_persona($IdPersona)");
    $data = $query->result('Persona');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
}
