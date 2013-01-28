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
   * @return arraySecciones
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
  public function altaSeccion($IdCarrera, $Texto, $Descripcion, $Tipo){
    $IdCarrera = $this->db->escape($IdCarrera);
    $Texto = $this->db->escape($Texto);
    $Descripcion = $this->db->escape($Descripcion);
    $IdFormulario = $this->db->escape($this->IdFormulario);
    $Tipo = $this->db->escape($Tipo);
    $query = $this->db->query("call esp_alta_seccion($IdFormulario, $IdCarrera, $Texto, $Descripcion, $Tipo)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>