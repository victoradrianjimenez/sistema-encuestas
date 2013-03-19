<?php

/**
 * 
 */
class Materia extends CI_Model{
  var $idMateria;
  var $nombre;
  var $codigo;
  var $publicarInformes='N';
  var $publicarHistoricos='N';
  var $publicarDevoluciones='N';
  
  function __construct(){
    parent::__construct();
  }
  
  
  /**
   * Obtener el listado de docentes relacionados a la materia. Devuleve un array.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  arrayUsuarios
   */  
  public function listarDocentes($pagNumero=0, $pagLongitud=1000){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $idMateria = $this->db->escape($this->idMateria);
    $query = $this->db->query("call esp_listar_docentes_materia($idMateria, $pagNumero, $pagLongitud)");
    $data = $query->result('Usuario');
    $query->free_result();
    return $data;
  }
  
  
  
  /**
   * Obtener el listado de carreras a la que pertenece la materia. Devuleve un array de objetos.
   *
   * @access  public
   * @return  array
   
  public function listarCarreras(){
    $idMateria = $this->db->escape($this->idMateria);
    $query = $this->db->query("call esp_listar_carreras_materia($idMateria)");
    $data = $query->result('Carrera');
    $query->free_result();
    return $data;
  }
  */
  
  /**
   * Obtener la cantidad de docentes relacionados a la materia.
   *
   * @access public
   * @return int
   */ 
  public function cantidadDocentes(){
    $idMateria = $this->db->escape($this->idMateria);
    $query = $this->db->query("call esp_cantidad_docentes_materia($idMateria)");
    $data=$query->row();
    $query->free_result();
    return ($data)?$data->cantidad:0;
  }
  
  
  /**
   * Asocia un docente a la materia. Devuleve PROCEDURE_SUCCESS en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de usuario
   * @return string
   */
  public function asociarDocente($id, $ordenFormulario, $cargo){
    $idMateria = $this->db->escape($this->idMateria);
    $id = $this->db->escape($id);
    $ordenFormulario = $this->db->escape($ordenFormulario);
    $cargo = $this->db->escape($cargo);
    $query = $this->db->query("call esp_asociar_docente_materia($id, $idMateria, $ordenFormulario, $cargo)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Elimina la asociación de un docente con la materia. Devuleve PROCEDURE_SUCCESS en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de usuario
   * @return string
   */
  public function desasociarDocente($id){
    $idMateria = $this->db->escape($this->idMateria);
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_desasociar_docente_materia($id, $idMateria)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  } 
  
  
  /**
   * Guarda la cantidad de claves de acceso generadas en una materia y carrera (representa la cantidad de alumnos)
   *
   * @access public
   * @param identificador de carrera
   * @param cantidad de claves generadas, que se quiere guardar para futuras consultas
   * @return string
   */
  public function asignarCantidadClaves($idCarrera, $cantidad){
    $idMateria = $this->db->escape($this->idMateria);
    $idCarrera = $this->db->escape($idCarrera);
    $cantidad = $this->db->escape($cantidad);
    $query = $this->db->query("call esp_asignar_cantidad_claves($idCarrera, $idMateria, $cantidad)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  } 

  
  /**
   * Lee la cantidad de claves de acceso generadas en una materia y carrera (representa la cantidad de alumnos)
   *
   * @access public
   * @param identificador de carrera
   * @return int
   */
  public function dameCantidadClaves($idCarrera){
    $idMateria = $this->db->escape($this->idMateria);
    $idCarrera = $this->db->escape($idCarrera);
    $query = $this->db->query("call esp_dame_cantidad_claves($idCarrera, $idMateria)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->cantidad:0;
  } 
}

?>