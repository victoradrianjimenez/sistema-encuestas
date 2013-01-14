<?php

/**
 * 
 */
class Pregunta extends CI_Model{
  var $IdPregunta;
  var $IdCarrera;
  var $Texto;
  var $Descripcion;
  var $Creacion;
  var $Tipo;
  var $Obligatoria;
  var $OrdenInverso;
  var $LimiteInferior;
  var $LimiteSuperior;
  var $Paso;
  var $Unidad;
  
  function __construct(){
    parent::__construct();
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