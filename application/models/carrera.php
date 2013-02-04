<?php

/**
 * 
 */
class Carrera extends CI_Model{
  var $idCarrera;
  var $idDepartamento;
  var $idDirectorCarrera;
  var $idFormulario;
  var $nombre;
  var $plan;
  
  function __construct(){
    parent::__construct();
  }

  /**
   * Buscar materias que pertenecen a la carrera. Devuleve un array de objetos.
   *
   * @access  public
   * @param nombre o fragmento del nombre de la materia a buscar.
   * @return  arrayMaterias
   */  
  public function buscarMaterias($nombre){
    $idCarrera = $this->db->escape($this->idCarrera);
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_materias_carrera($idCarrera, $nombre)");
    $data = $query->result('Materia');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener el listado de materias que pertenecen a la carrera. Devuleve un array de objetos.
   *
   * @access  public
   * @return  array
   */  
  public function listarMaterias(){
    $idCarrera = $this->db->escape($this->idCarrera);
    $query = $this->db->query("call esp_listar_materias_carrera($idCarrera)");
    $data = $query->result('Materia');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad de materias que pertenecen a la carrera.
   *
   * @access public
   * @return int
   */ 
  public function cantidadMaterias(){
    $idCarrera = $this->db->escape($this->idCarrera);
    $query = $this->db->query("call esp_cantidad_materias_carrera($idCarrera)");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  
  /**
   * Asocia una materia a la carrera. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de materia
   * @return string
   */
  public function asociarMateria($idMateria){
    $idCarrera = $this->db->escape($this->idCarrera);
    $idMateria = $this->db->escape($idMateria);
    $query = $this->db->query("call esp_asociar_materia_carrera($idMateria, $idCarrera)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Elimina la asociación de una materia a la carrera. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de materia
   * @return string
   */
  public function desasociarMateria($idMateria){
    $idCarrera = $this->db->escape($this->idCarrera);
    $idMateria = $this->db->escape($idMateria);
    $query = $this->db->query("call esp_desasociar_materia_carrera($idMateria, $idCarrera)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>