<?php

/**
 * 
 */
class Seccion extends CI_Model{
  var $IdSeccion;
  var $IdFormulario;
  var $IdCarrera;
  var $Texto;
  var $Descripcion;
  var $Tipo;
  
  function __construct(){
    parent::__construct();
  }
  
  /**
   * Obtener el listado de items que pertenecen al formulario (y una carrera). Devuleve un array de objetos.
   *
   * @access public
   * @return arrayItems
   */
  public function listarItems(){
    $idFormulario = $this->db->escape($this->IdFormulario);
    $idSeccion = $this->db->escape($this->IdSeccion);
    $query = $this->db->query("call esp_listar_items_seccion($idSeccion, $idFormulario)");
    $data = $query->result('Item');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
   
  /**
   * Da de Alta un nuevo item. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la pregunta que corresponde al item
   * @param identificador de la carrera que crea el item (si es nulo es comunn a todas las carreras)
   * @param posicion del item dentro de la seccion
   * @param cantidad de preguntas que las carreras pueden agregar al formulario
   * @return  string
   */
  public function altaItem($IdPregunta, $IdCarrera, $Posicion){
    $IdPregunta = $this->db->escape($IdPregunta);
    $IdCarrera = $this->db->escape($IdCarrera);
    $Posicion = $this->db->escape($Posicion);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $IdSeccion = $this->db->escape($this->IdSeccion);
    $query = $this->db->query("call esp_alta_item($IdSeccion, $IdFormulario, $IdPregunta, $IdCarrera, $Posicion)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
}

?>