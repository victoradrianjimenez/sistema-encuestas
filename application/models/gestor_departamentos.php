<?php

/**
 * 
 */
class Gestor_departamentos extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
  
  /**
   * Obtener el listado de departamentos. Devuleve un array de objetos.
   *
   * @access public
   * @param numero de pagina a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_departamentos($pagNumero, $pagLongitud)");
    $data = $query->result('Departamento');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }


  /**
   * Obtener la cantidad de departamentos. 
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_departamentos()");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Cantidad:0;
  }



}
?>