<?php

/**
 * 
 */
class Pregunta extends CI_Model{
  var $idPregunta;
  var $tipo;
  var $texto;
  var $descripcion;
  var $creacion;
  var $ordenInverso;
  var $limiteInferior;
  var $limiteSuperior;
  var $paso;
  var $unidad;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Da de Alta una opcion. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param texto o etiqueta de la opcion
   * @return  string
   */
  public function altaOpcion($texto){
    $texto = $this->db->escape($texto);
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_alta_opcion($idPregunta, $texto)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  /**
   * Obtener el listado de opciones de una pregunta. Devuleve un array de objetos.
   *
   * @access public
   * @return array
   */
  public function listarOpciones(){
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_listar_opciones($idPregunta)");
    $data = $query->result('Opcion');
    $query->free_result();
    return $data;
  }
  
  /**
   * Devuelve el tipo (el texto solamente) de la pregunta
   *
   * @access public
   * @return array
   */
  public function tipo(){
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_tipo_pregunta($idPregunta)");
    $data=$query->row();
    $query->free_result();
    return ($data)?$data->tipo:'';
  }
  
  
  
  /**
   * Historicos por pregunta
   */
  
  public function historicoMateria($idMateria, $idCarrera, $fechaInicio, $fechaFin){
    $idMateria = $this->db->escape($idMateria);
    $idCarrera = $this->db->escape($idCarrera);
    $fechaInicio = $this->db->escape($fechaInicio);
    $fechaFin = $this->db->escape($fechaFin);
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_historico_pregunta_materia($idMateria, $idCarrera, $idPregunta, $fechaInicio, $fechaFin)");
    $data=$query->result_array();
    $query->free_result();
    return $data;
  }
  
  public function historicoCarrera($idCarrera, $fechaInicio, $fechaFin){
    $idCarrera = $this->db->escape($idCarrera);
    $fechaInicio = $this->db->escape($fechaInicio);
    $fechaFin = $this->db->escape($fechaFin);
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_historico_pregunta_carrera($idCarrera, $idPregunta, $fechaInicio, $fechaFin)");
    $data=$query->result_array();
    $query->free_result();
    return $data;
  }
  
  public function historicoDepartamento($idDepartamento, $fechaInicio, $fechaFin){
    $idDepartamento = $this->db->escape($idDepartamento);
    $fechaInicio = $this->db->escape($fechaInicio);
    $fechaFin = $this->db->escape($fechaFin);
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_historico_pregunta_departamento($idDepartamento, $idPregunta, $fechaInicio, $fechaFin)");
    $data=$query->result_array();
    $query->free_result();
    return $data;
  }
  
  public function historicoFacultad($fechaInicio, $fechaFin){
    $fechaInicio = $this->db->escape($fechaInicio);
    $fechaFin = $this->db->escape($fechaFin);
    $idPregunta = $this->db->escape($this->idPregunta);
    $query = $this->db->query("call esp_historico_pregunta_facultad($idPregunta, $fechaInicio, $fechaFin)");
    $data=$query->result_array();
    $query->free_result();
    return $data;
  }
  
  
  
  
  
  
  
  
  
}

?>