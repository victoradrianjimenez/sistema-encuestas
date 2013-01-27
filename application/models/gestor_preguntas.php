<?php

/**
 * 
 */
class Gestor_preguntas extends CI_Model{

	function __construct() {
		parent::__construct();
	}


  /**
   * Buscar preguntas por el texto de la misma. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento de la pregunta
   * @return arrayPregunta
   */
  public function buscar($texto){
    $texto = $this->db->escape($texto);
    $query = $this->db->query("call esp_buscar_preguntas($texto)");
    $data = $query->result('Pregunta');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }

}
?>