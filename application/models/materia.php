<?php

/**
 * 
 */
class Materia extends CI_Model{
  var $idMateria;
  var $nombre;
  var $codigo;
  var $alumnos;
  
  function __construct(){
    parent::__construct();
  }
  
  
  /**
   * Obtener el listado de docentes relacionados a la materia. Devuleve un array.
   *
   * @access  public
   * @return  array
   */  
  public function listarDocentes(){
    $idMateria = $this->db->escape($this->idMateria);
    $query = $this->db->query("call esp_listar_docentes_materia($idMateria)");
    $data = $query->result_array();
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad de docentes relacionados a la materia.
   *
   * @access public
   * @return int
   */ 
  public function cantidadDocentes(){
    $idMateria = $this->db->escape($this->idMateria);
    $query = $this->db->query("call esp_cantidad_docentes_materia($idMateria)");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  
  /**
   * Asocia un docente a la materia. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de usuario
   * @return string
   */
  public function asociarDocente($id, $ordenFormulario, $cargo){
    $idMateria = $this->db->escape($this->idMateria);
    $id = $this->db->escape($id);
    $ordenFormulario = $this->db->escape($ordenFormulario);
    $cargo = $this->db->escape($cargo);
    $query = $this->db->query("call esp_asociar_docente_materia($id, $idMateria, $ordenFormulario, $cargo)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Elimina la asociación de un docente con la materia. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de usuario
   * @return string
   */
  public function desasociarDocente($id){
    $idMateria = $this->db->escape($this->idMateria);
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_desasociar_docente_materia($id, $idMateria)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>