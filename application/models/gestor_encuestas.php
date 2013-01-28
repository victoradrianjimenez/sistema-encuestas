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
  

  /**
   * Da de Alta una periodo de encuestas. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador del formulario que se usará en la encuesta
   * @param año lectivo a la que se refiere la encuesta
   * @param cuatrimestre o periodo al que se refiere la encuesta
   * @return  string
   */
  public function alta($IdFormulario, $Año, $Cuatrimestre){
    $IdFormulario = $this->db->escape($IdFormulario);
    $Año = $this->db->escape($Año);
    $Cuatrimestre = $this->db->escape($Cuatrimestre);
    $query = $this->db->query("call esp_alta_encuesta($IdFormulario, $Año, $Cuatrimestre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }


  /**
   * Obtener el listado de encuestas. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_encuestas($pagNumero, $pagLongitud)");
    $data = $query->result('Encuesta');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad total de encuestas.
   *
   * @access public
   * @return int
   */ 
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_encuestas()");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Cantidad:0;
  }
  
      
}
?>