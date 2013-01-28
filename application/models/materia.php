<?php

/**
 * 
 */
class Materia extends CI_Model{
  var $IdMateria;
  var $Nombre;
  var $Codigo;
  var $Alumnos;
  
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
    $IdMateria = $this->db->escape($this->IdMateria);
    $query = $this->db->query("call esp_listar_docentes_materia($IdMateria)");
    $data = $query->result('Persona');
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
    $IdMateria = $this->db->escape($this->IdMateria);
    $query = $this->db->query("call esp_cantidad_docentes_materia($IdMateria)");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Cantidad:0;
  }
  
  
  /**
   * Asocia un docente a la materia. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de persona
   * @return string
   */
  public function asociarDocente($IdPersona, $TipoAcceso, $OrdenFormulario, $Cargo){
    $IdMateria = $this->db->escape($this->IdMateria);
    $IdPersona = $this->db->escape($IdPersona);
    $TipoAcceso = $this->db->escape($TipoAcceso);
    $OrdenFormulario = $this->db->escape($OrdenFormulario);
    $Cargo = $this->db->escape($Cargo);
    $query = $this->db->query("call esp_asociar_docente_materia($IdPersona, $IdMateria, $TipoAcceso, $OrdenFormulario, $Cargo)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Elimina la asociación de un docente con la materia. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de persona
   * @return string
   */
  public function desasociarDocente($IdPersona){
    $IdMateria = $this->db->escape($this->IdMateria);
    $IdPersona = $this->db->escape($IdPersona);
    $query = $this->db->query("call esp_desasociar_docente_materia($IdPersona, $IdMateria)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>