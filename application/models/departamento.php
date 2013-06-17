<?php

/**
 * 
 */
class Departamento extends CI_Model{
  
  var $idDepartamento;
  var $idJefeDepartamento;
  var $nombre;
  var $publicarInformes = 'N';
  var $publicarHistoricos = 'N';
  
  function __construct(){
    parent::__construct();
  }
 
 
  /**
   * Obtener el listado de carreras del departamento. Devuleve un array de objetos.
   *
   * @access  public
   * @return  arrayCarreras
   */
  public function listarCarreras(){
    $idDepartamento = $this->db->escape($this->idDepartamento);
    $query = $this->db->query("call esp_listar_carreras_departamento($idDepartamento)");
    $data = $query->result('Carrera');
    $query->free_result();
    return $data;
  }  
    
  /**
   * Obtener el listado de materias que pertenecen al departamento. Devuleve un array.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listarMaterias($pagInicio=0, $pagLongitud=1000){
    $idDepartamento = $this->db->escape($this->idDepartamento);
    $query = $this->db->query("call esp_listar_materias_departamento($idDepartamento, $pagInicio, $pagLongitud)");
    $data = $query->result_array();
    $query->free_result();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad de carreras del departamento.
   *
   * @access public
   * @return int
   
  public function cantidadCarreras(){
    $idDepartamento = $this->db->escape($this->IdDepartamento);
    $query = $this->db->query("call esp_cantidad_carreras_departamento($idDepartamento)");
    $data=$query->row();
    $query->free_result();
    return ($data)?$data->cantidad:0;
  }*/ 

}

?>