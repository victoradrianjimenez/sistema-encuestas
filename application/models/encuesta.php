<?php

/**
 * 
 */
class Encuesta extends CI_Model{
	var $idEncuesta;
  var $idFormulario;
  var $año;
  var $cuatrimestre;
  var $fechaInicio;
  var $fechaFin;
  
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
   * Obtener el listado de claves de acceso de la encuesta para una materia y carrera dada. Devuleve un array de objetos.
   *
   * @access  public
   * @param identificador de materia
   * @param identificador de carrera
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listarClavesMateria($IdMateria, $IdCarrera, $pagNumero, $pagLongitud){
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_claves_encuesta_materia($IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario, $pagNumero, $pagLongitud)");
    $data = $query->result('Clave');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  /**
   * Cerrar o finalizar una encuesta. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @return string
   */
  public function finalizar(){
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_finalizar_encuesta($IdEncuesta, $IdFormulario)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
  public function respuestasPreguntaMateria($IdPregunta, $IdDocente, $IdMateria, $IdCarrera){
    $IdPregunta = $this->db->escape($IdPregunta);
    $IdDocente = $this->db->escape($IdDocente);
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_respuestas_pregunta_materia($IdPregunta, $IdDocente, $IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario)");
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
  
  
    
  /**
   * Obtener el listado de docentes de las que hace referencia la encuesta. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador de la materia
   * @param identificador de la carrera
   * @return array
   */
  public function listarDocentes($IdMateria, $IdCarrera){
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_listar_docentes_encuesta($IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario)");
    $data = $query->result('Usuario');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Da de Alta una clave. Devuleve '=###' en caso de éxito donde ### es la clave generada, o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la materia
   * @param identificador de la carrera
   * @param tipo de clave a generar
   * @return  string
   */
  public function altaClave($IdMateria, $IdCarrera, $Tipo){
    $IdMateria = $this->db->escape($IdMateria);
    $IdCarrera = $this->db->escape($IdCarrera);
    $Tipo = $this->db->escape($Tipo);
    $IdEncuesta = $this->db->escape($this->IdEncuesta);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $query = $this->db->query("call esp_alta_clave($IdMateria, $IdCarrera, $IdEncuesta, $IdFormulario, $Tipo)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
}

?>