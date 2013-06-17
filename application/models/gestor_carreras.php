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
   * @param identificador de usuario correspondiente al director de carrera
   * @param nombre de la carrera
   * @param plan de la carrera (año)
   * @return  string
   */
  public function alta($idDepartamento, $idDirectorCarrera, $idOrganizador, $nombre, $plan, $publicarInformes, $publicarHistoricos){
    $publicarInformes = $this->db->escape($publicarInformes);
    $publicarHistoricos = $this->db->escape($publicarHistoricos);
    $idDepartamento = $this->db->escape($idDepartamento);
    $idOrganizador = $this->db->escape($idOrganizador);
    $idDirectorCarrera = $this->db->escape($idDirectorCarrera);
    $nombre = $this->db->escape($nombre);
    $plan = $this->db->escape($plan);
    $query = $this->db->query("call esp_alta_carrera($idDepartamento, $idDirectorCarrera, $idOrganizador, $nombre, $plan, $publicarInformes, $publicarHistoricos)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }


  /**
   * Da de Baja una carrera. Devuleve PROCEDURE_SUCCESS en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la carrera
   * @return string
   */
  public function baja($idCarrera){
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_baja_carrera($idCarrera)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Modifica una carrera. Devuleve 'ok.' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador del departamento al que pertenece la carrera
   * @param identificador de la carrera a modificar
   * @param identificador de usuario correspondiente al director de carrera
   * @param nuevo nombre de la carrera
   * @param plan de la carrera (año)
   * @return string
   */
  public function modificar($idCarrera, $idDepartamento, $idDirectorCarrera, $idOrganizador, $nombre, $plan, $publicarInformes, $publicarHistoricos){
    $publicarInformes = $this->db->escape($publicarInformes);
    $publicarHistoricos = $this->db->escape($publicarHistoricos);
    $idDepartamento = $this->db->escape($idDepartamento);
    $idDirectorCarrera = $this->db->escape($idDirectorCarrera);
    $idOrganizador = $this->db->escape($idOrganizador);
    $idCarrera = $this->db->escape($idCarrera);
    $nombre = $this->db->escape($nombre);
    $plan = $this->db->escape($plan);
    $query = $this->db->query("call esp_modificar_carrera($idCarrera, $idDepartamento, $idDirectorCarrera, $idOrganizador, $nombre, $plan, $publicarInformes, $publicarHistoricos)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Obtener una carrera a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de la carrera
   * @return object
   */
  public function dame($idCarrera){
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_dame_carrera($idCarrera)");
    $data = $query->result('Carrera');
    $query->free_result();
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
  public function listar($pagNumero=0, $pagLongitud=1000){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_carreras($pagNumero, $pagLongitud)");
    $data = $query->result('Carrera');
    $query->free_result();
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
    return ($data)?$data->cantidad:0;
  }
  
      
  /**
   * Buscar carreras por el nombre. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre de la carrera
   * @return array
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_carreras($nombre)");
    $data = $query->result('Carrera');
    $query->free_result();
    return $data;
  }
  
}
?>