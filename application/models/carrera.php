<?php

/**
 * 
 */
class Carrera extends CI_Model{
  var $IdCarrera;
  var $IdDepartamento;
  var $IdFormulario;
  var $Nombre;
  var $Plan;
  
  function __construct(){
    parent::__construct();
  }


  /**
   * Obtener el listado de materias que pertenecen a la carrera. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listarMaterias($pagNumero, $pagLongitud){
    $idCarrera = $this->db->escape($this->IdCarrera);
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_materias_carrera($idCarrera, $pagNumero, $pagLongitud)");
    $data = $query->result('Materia');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad de materias que pertenecen a la carrera.
   *
   * @access public
   * @return int
   */ 
  public function cantidadMaterias(){
    $idCarrera = $this->db->escape($this->IdCarrera);
    $query = $this->db->query("call esp_cantidad_materias_carrera($idCarrera)");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Cantidad:0;
  }
  
  
  /**
   * Asocia una materia a la carrera. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de materia
   * @return string
   */
  public function asociarMateria($IdMateria){
    $IdCarrera = $this->db->escape($this->IdCarrera);
    $IdMateria = $this->db->escape($IdMateria);
    $query = $this->db->query("call esp_asociar_materia_carrera($IdMateria, $IdCarrera)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
}

?>