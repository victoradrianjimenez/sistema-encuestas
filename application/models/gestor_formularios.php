<?php

/**
 * 
 */
class Gestor_formularios extends CI_Model{
    
  function __construct(){
    parent::__construct();
  }
  
  
  /**
   * Obtener un formulario a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador del formulario
   * @return object
   */
  public function dame($idFormulario){
    $idFormulario = $this->db->escape($idFormulario);
    $query = $this->db->query("call esp_dame_formulario($idFormulario)");
    $data = $query->result('Formulario');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
}

?>