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
  public function alta($idJefeDepartamento, $nombre){
    $nombre = $this->db->escape($nombre);
    $idJefeDepartamento = $this->db->escape($idJefeDepartamento);
    $query = $this->db->query("call esp_alta_departamento($idJefeDepartamento, $nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Baja un departamento. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de departamento
   * @return string
   */
  public function baja($idDepartamento){
    $idDepartamento = $this->db->escape($idDepartamento);
    $query = $this->db->query("call esp_baja_departamento($idDepartamento)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Modificar los datos de un departamento. Devuleve el 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param nombre del departamento
   * @return  string
   */
  public function modificar($idDepartamento, $idJefeDepartamento, $nombre){
    $nombre = $this->db->escape($nombre);
    $idJefeDepartamento = $this->db->escape($idJefeDepartamento);
    $idDepartamento = $this->db->escape($idDepartamento);
    $query = $this->db->query("call esp_modificar_departamento($idDepartamento, $idJefeDepartamento, $nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
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
    return ($data)?$data->cantidad:0;
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