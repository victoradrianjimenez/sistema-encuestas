<?php

/**
 * 
 */
class Gestor_departamentos extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
    
  /**
   * Da de Alta un nuevo departamento. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador del jefe de departamento
   * @param nombre del departamento
   * @return  string
   */
  public function alta($IdJefeDepartamento, $Nombre){
    $Nombre = $this->db->escape($Nombre);
    $IdJefeDepartamento = $this->db->escape($IdJefeDepartamento);
    $query = $this->db->query("call esp_alta_departamento($IdJefeDepartamento, $Nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Baja un departamento. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de departamento
   * @return string
   */
  public function baja($IdDepartamento){
    $IdDepartamento = $this->db->escape($IdDepartamento);
    $query = $this->db->query("call esp_baja_departamento($IdDepartamento)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Modificar los datos de un departamento. Devuleve el 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param nombre del departamento
   * @return  string
   */
  public function modificar($IdDepartamento, $IdJefeDepartamento, $Nombre){
    $Nombre = $this->db->escape($Nombre);
    $IdJefeDepartamento = $this->db->escape($IdJefeDepartamento);
    $IdDepartamento = $this->db->escape($IdDepartamento);
    $query = $this->db->query("call esp_modificar_departamento($IdDepartamento, $IdJefeDepartamento, $Nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Obtener un departamento a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de departamento
   * @return object
   */
  public function dame($idDepartamento){
    $idDepartamento = $this->db->escape($idDepartamento);
    $query = $this->db->query("call esp_dame_departamento($idDepartamento)");
    $data = $query->result('Departamento');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }


  /**
   * Obtener el listado de departamentos. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listar($pagInicio, $pagLongitud){
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_departamentos($pagInicio, $pagLongitud)");
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
    return ($data)?$data->Cantidad:0;
  }


  /**
   * Buscar un departamento por su nombre. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre del departamento
   * @return arrayDepartamentos
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_departamentos($nombre)");
    $data = $query->result('Departamento');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
}
?>