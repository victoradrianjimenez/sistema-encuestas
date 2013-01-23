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
  
  /**
   * Obtener el listado de formularios. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_formularios($pagNumero, $pagLongitud)");
    $data = $query->result('Formulario');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
}

?>