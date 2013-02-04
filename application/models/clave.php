<?php

/**
 * 
 */
class Clave extends CI_Model{
	var $idClave;
  var $idMateria;
  var $idCarrera;
  var $idEncuesta;
  var $idFormulario;
  var $clave;
  var $tipo;
  var $generada;
  var $utilizada;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Da de Alta una respuesta a una pregunta. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la pregunta
   * @param identificador de docente. Este parametro puede ser NULL.
   * @param número de opcion, en caso de ser del tipo opcion simple o de opcion multiple
   * @param texto de la respuesta, en caso de ser del tipo texto simple o multilinea
   * @return  string
   */
  public function altaRespuesta($idPregunta, $idDocente, $opcion, $texto){
    $idPregunta = $this->db->escape($idPregunta);
    $idDocente = $this->db->escape($idDocente);
    $opcion = $this->db->escape($opcion);
    $texto = $this->db->escape($texto);
    $idClave = $this->db->escape($this->idClave);
    $idMateria = $this->db->escape($this->idMateria);
    $idCarrera = $this->db->escape($this->idCarrera);
    $idEncuesta = $this->db->escape($this->idEncuesta);
    $idFormulario = $this->db->escape($this->idFormulario);
    $query = $this->db->query("call esp_alta_respuesta($idPregunta, $idClave, $idMateria, $idCarrera, $idEncuesta, $idFormulario, $idDocente, $opcion, $texto)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  /**
   * Registrar que la clave ya fue utilizada, guardando la fecha actual. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @return string
   */
  public function registrar(){
    $idClave = $this->db->escape($this->idClave);
    $idMateria = $this->db->escape($this->idMateria);
    $idCarrera = $this->db->escape($this->idCarrera);
    $idEncuesta = $this->db->escape($this->idEncuesta);
    $idFormulario = $this->db->escape($this->idFormulario);
    $query = $this->db->query("call esp_registrar_clave($idClave, $idMateria, $idCarrera, $idEncuesta, $idFormulario)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>