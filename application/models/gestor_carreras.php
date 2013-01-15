<?php

/**
 * 
 */
class Gestor_carreras extends CI_Model{

	function __construct() {
		parent::__construct();
	}


  /**
   * Da de Alta una nueva carrera. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador del departamento al que pertenece la carrera
   * @param nombre de la carrera
   * @param plan de la carrera (año)
   * @return  string
   */
  public function alta($IdDepartamento, $Nombre, $Plan){
    $IdDepartamento = $this->db->escape($IdDepartamento);
    $Nombre = $this->db->escape($Nombre);
    $Nombre = $this->db->escape($Plan);
    $query = $this->db->query("call esp_alta_carrera($IdDepartamento, $Nombre, $Plan)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }


  /**
   * Da de Baja una carrera. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la carrera
   * @return string
   */
  public function baja($IdCarrera){
    $IdCarrera = $this->db->escape($IdCarrera);
    $query = $this->db->query("call esp_baja_carrera($IdCarrera)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Modifica una carrera. Devuleve 'ok.' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador del departamento al que pertenece la carrera
   * @param identificador de la carrera a modificar
   * @param nuevo nombre de la carrera
   * @param plan de la carrera (año)
   * @return string
   */
  public function modificar($IdCarrera, $IdDepartamento, $Nombre, $Plan){
    $IdDepartamento = $this->db->escape($IdDepartamento);
    $IdCarrera = $this->db->escape($IdCarrera);
    $Nombre = $this->db->escape($Nombre);
    $Plan = $this->db->escape($Plan);
    $query = $this->db->query("call esp_modificar_carrera($IdCarrera, $IdDepartamento, $Nombre, $Plan)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
  
  
  /**
   * Obtener el listado de carreras. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_carreras($pagNumero, $pagLongitud)");
    $data = $query->result('Carrera');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad total de carreras.
   *
   * @access public
   * @return int
   */ 
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_carreras()");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Cantidad:0;
  }
  
}
?>