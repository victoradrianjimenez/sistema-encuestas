<?php


/**
 * 
 */
class Gestor_devoluciones extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
  /**
   * Obtener el listado de devoluciones. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listar($pagInicio, $pagLongitud){
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_devoluciones($pagInicio, $pagLongitud)");
    $data = $query->result('Devolucion');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  /**
   * Obtener la cantidad de devoluciones
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_devoluciones()");
    $data=$query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  

}

?>