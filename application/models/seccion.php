<?php

/**
 * 
 */
class Seccion extends CI_Model{
  var $IdSeccion;
  var $IdFormulario;
  var $IdCarrera;
  var $Texto;
  var $Descripcion;
  var $Tipo;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Obtener el listado de items que pertenecen al formulario (y una carrera). Devuleve un array de objetos.
   *
   * @access public
   * @return arrayItems
   */
  public function listarItems(){
    $idFormulario = $this->db->escape($this->IdFormulario);
    $idSeccion = $this->db->escape($this->IdSeccion);
    $query = $this->db->query("call esp_listar_items_seccion($idSeccion, $idFormulario)");
    $data = $query->result('Item');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
}

?>