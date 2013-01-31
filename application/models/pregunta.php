<?php

/**
 * 
 */
class Pregunta extends CI_Model{
  var $idPregunta;
  var $idCarrera;
  var $texto;
  var $descripcion;
  var $creacion;
  var $tipo;
  var $obligatoria;
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
  public function altaOpcion($Texto){
    $Texto = $this->db->escape($Texto);
    $IdPregunta = $this->db->escape($this->IdPregunta);
    $query = $this->db->query("call esp_alta_opcion($IdPregunta, $Texto)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  /**
   * Obtener el listado de opciones de una pregunta. Devuleve un array de objetos.
   *
   * @access public
   * @return array
   */
  public function listarOpciones(){
    $idPregunta = $this->db->escape($this->IdPregunta);
    $query = $this->db->query("call esp_listar_opciones($idPregunta)");
    $data = $query->result('Opcion');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
}

?>