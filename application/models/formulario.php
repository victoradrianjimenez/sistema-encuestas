<?php

/**
 * 
 */
class Formulario extends CI_Model{
  var $IdFormulario;
  var $Nombre;
  var $Titulo;
  var $Descripcion;
  var $Creacion;
  var $PreguntasAdicionales;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Obtener el listado de secciones que conforman el formulario. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador del formulario
   * @param identidicador de la carrera
   * @return array
   */
  public function listarSeccionesCarrera($idCarrera){
    $idFormulario = $this->db->escape($this->IdFormulario);
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_listar_secciones_carrera($idFormulario, $idCarrera)");
    $data = $query->result('Seccion');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
    
}

?>