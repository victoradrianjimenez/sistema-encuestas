<?php

/**
 * 
 */
class Departamento extends CI_Model{
  
  var $idDepartamento;
  var $idJefeDepartamento;
  var $nombre;
  var $publicarInformes;
  var $publicarHistoricos;
  
  function __construct(){
    parent::__construct();
  }
 
 
  /**
   * Obtener el listado de carreras del departamento. Devuleve un array de objetos.
   *
   * @access  public
   * @return  arrayCarreras
   
  public function listarCarreras(){
    $idDepartamento = $this->db->escape($this->IdDepartamento);
    $query = $this->db->query("call esp_listar_carreras_departamento($idDepartamento)");
    $data = $query->result('Carrera');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }*/  
    
    
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
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }*/ 

}

?>