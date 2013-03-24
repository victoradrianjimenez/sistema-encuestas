<?php

/**
 * 
 */
class Formulario extends CI_Model{
  var $idFormulario;
  var $nombre;
  var $titulo;
  var $descripcion;
  var $creacion;
  var $preguntasAdicionales;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Obtener una sección a partir de su ID. Devuleve un objeto Seccion.
   *
   * @access public
   * @param identificador de seccion
   * @return Seccion
   */
  public function dameSeccion($idSeccion){
    $idSeccion = $this->db->escape($idSeccion);
    $idFormulario= $this->db->escape($this->idFormulario);
    $query = $this->db->query("call esp_dame_seccion($idSeccion, $idFormulario)");
    $data = $query->result('Seccion');
    $query->free_result();
    return ($data != FALSE)?$data[0]:FALSE;
  } 
   
  /**
   * Obtener el listado de secciones que conforman el formulario. Devuleve un array de objetos.
   *
   * @access public
   * @return arraySecciones
   */
  public function listarSecciones(){
    $idFormulario = $this->db->escape($this->idFormulario);
    $query = $this->db->query("call esp_listar_secciones($idFormulario)");
    $data = $query->result('Seccion');
    $query->free_result();
    return $data;
  }
  
  /**
   * Obtener el listado de secciones que conforman el formulario, incluyendo las secciones de una carrera. Devuleve un array de objetos.
   *
   * @access public
   * @param identidicador de la carrera
   * @return arraySecciones
   */
  public function listarSeccionesCarrera($idCarrera){
    $idFormulario = $this->db->escape($this->idFormulario);
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_listar_secciones_carrera($idFormulario, $idCarrera)");
    $data = $query->result('Seccion');
    $query->free_result();
    return $data;
  }
 
  /**
   * Da de Alta un nuevo formulario. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la carrera que crea la seccion (si es nulo es comunn a todas las carreras)
   * @param texto o titulo de la seccion
   * @param descripcion opcional de la seccion
   * @param tipo de seccion, puede ser normal o referida a docentes
   * @return  string
   */
  public function altaSeccion($idCarrera, $texto, $descripcion, $tipo){
    $idCarrera = $this->db->escape($idCarrera);
    $texto = $this->db->escape($texto);
    $descripcion = $this->db->escape($descripcion);
    $idFormulario = $this->db->escape($this->idFormulario);
    $tipo = $this->db->escape($tipo);
    $query = $this->db->query("call esp_alta_seccion($idFormulario, $idCarrera, $texto, $descripcion, $tipo)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>