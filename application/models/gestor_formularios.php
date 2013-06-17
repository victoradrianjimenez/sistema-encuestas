<?php

/**
 * 
 */
class Gestor_formularios extends CI_Model{
    
  function __construct(){
    parent::__construct();
  }
  
    
  /**
   * Da de Alta un nuevo formulario. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param nombre con el que se identifica al formulario
   * @param titulo que se mostrará en el formulario
   * @param descripcion opcional del formulario
   * @param cantidad de preguntas que las carreras pueden agregar al formulario
   * @return  string
   */
  public function alta($nombre, $titulo, $descripcion, $preguntasAdicionales){
    $nombre = $this->db->escape($nombre);
    $titulo = $this->db->escape($titulo);
    $descripcion = $this->db->escape($descripcion);
    $preguntasAdicionales = $this->db->escape($preguntasAdicionales);
    $query = $this->db->query("call esp_alta_formulario($nombre, $titulo, $descripcion, $preguntasAdicionales)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  /**
   * Obtener un formulario a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador del formulario
   * @return object
   */
  public function dame($idFormulario){
    $idFormulario = $this->db->escape($idFormulario);
    $query = $this->db->query("call esp_dame_formulario($idFormulario)");
    $data = $query->result('Formulario');
    $query->free_result();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  /**
   * Da de Baja un formulario. Devuleve PROCEDURE_SUCCESS en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de formulario
   * @return string
   */
  public function baja($idFormulario){
    $idFormulario = $this->db->escape($idFormulario);
    $query = $this->db->query("call esp_baja_formulario($idFormulario)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  /**
   * Buscar formularios por el nombre. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre del formulario
   * @return arrayFormularios
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_formularios($nombre)");
    $data = $query->result('Formularios');
    $query->free_result();
    return $data;
  }
  
  /**
   * Obtener el listado de formularios. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_formularios($pagNumero, $pagLongitud)");
    $data = $query->result('Formulario');
    $query->free_result();
    return $data;
  }
  
  /**
   * Obtener la cantidad de formularios. 
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_formularios()");
    $data=$query->row();
    $query->free_result();
    return ($data)?$data->cantidad:0;
  }
  
}

?>