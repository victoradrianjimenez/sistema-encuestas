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
    //$this->db->reconnect();
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
    //$this->db->reconnect();
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
    //$this->db->reconnect();
    return ($data)?$data->tipo:'';
  }
}

?>