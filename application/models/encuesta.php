<?php

/**
 * 
 */
class Encuesta extends CI_Model{
	var $IdEncuesta;
  var $IdFormulario;
  var $Año;
  var $Cuatrimestre;
  var $FechaInicio;
  var $FechaFin;
  
  function __construct(){
    parent::__construct();
  }
  
  
  /**
   * Buscar una clave (usada o no). Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param clave alfanumérica
   * @return object
   */
  public function buscarClave($clave){
    $clave = $this->db->escape($clave);
    $query = $this->db->query("call esp_buscar_clave($clave)");
    $data = $query->result('Clave');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  
  /**
   * Obtiene cuantas claves se generaron y cuantas se usaron de una encuesta para una materia y una carrera.
   *
   * @access public
   * @param identificador de materia
   * @param idenificador de carrera
   * @return array
   */  
  public function cantidadClavesMateria($IdMateria, $IdCarrera){
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_cantidad_claves_materia($IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario)");
    $data=$query->row_array();
    $query->free_result();
    $this->db->reconnect();
    return $data; //devuelve dos elementos: Generadas y Utilizadas
  }
  
  /**
   * Obtiene las respuestas a una pregunta para una materia, carrera y encuesta. Devuelve un array, y la ultima fila contiene el total de respuestas.
   *
   * @access public
   * @param identificador de la pregunta
   * @param identificador de materia
   * @param idenificador de carrera
   * @return array
   */  
  public function respuestasPreguntaMateria($IdPregunta, $IdMateria, $IdCarrera){
    $IdPregunta = $this->db->escape($IdPregunta);
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_respuestas_pregunta_materia($IdPregunta, $IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario)");
    $data=$query->result_array();
    $query->free_result();
    $this->db->reconnect();
    return $data; //Opcion, Cantidad (ultima fila = cantidad total)
  }
  
  
  /**
   * Obtiene las respuestas del tipo texo de una pregunta para una materia, carrera y encuesta. Devuelve un array.
   *
   * @access public
   * @param identificador de la pregunta
   * @param identificador de materia
   * @param idenificador de carrera
   * @return array
   */  
  public function textosPreguntaMateria($IdPregunta, $IdMateria, $IdCarrera){
    $IdPregunta = $this->db->escape($IdPregunta);
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_textos_pregunta_materia($IdPregunta, $IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario)");
    $data=$query->result_array();
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
}

?>