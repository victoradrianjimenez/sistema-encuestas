<?php

/**
 * 
 */
class Gestor_materias extends CI_Model{

	function __construct() {
		parent::__construct();
	}
  
  /**
   * Obtener una materia a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de la materia
   * @return object
   */
  public function dame($idMateria){
    $idMateria = $this->db->escape($idMateria);
    $query = $this->db->query("call esp_dame_materia($idMateria)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  /**
   * Obtener el listado de materias. Devuleve un array de objetos.
   *
   * @access  public
   * @param posicion del primer item de la lista a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return  array
   */  
  public function listar($pagNumero, $pagLongitud){
    $pagNumero = $this->db->escape($pagNumero);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_materias($pagNumero, $pagLongitud)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener la cantidad total de materias.
   *
   * @access public
   * @return int
   */ 
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_materias()");
    $data=$query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  
        
  /**
   * Buscar materias por el nombre. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre de la materia
   * @return array
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_materias($nombre)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  

  /**
   * Modifica una materia. Devuleve 'ok.' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la materia
   * @param nuevo nombre de la materia
   * @param nuevo codigo de la materia
   * @return string
   */
  public function modificar($idMateria, $nombre, $codigo, $alumnos){
    $idMateria = $this->db->escape($idMateria);
    $nombre = $this->db->escape($nombre);
    $codigo = $this->db->escape($codigo);
    $alumnos = $this->db->escape($alumnos);
    $query = $this->db->query("call esp_modificar_materia($idMateria, $nombre, $codigo, $alumnos)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Alta una nueva materia. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param nombre de la materia
   * @param código de la materia
   * @return  string
   */
  public function alta($nombre, $codigo, $alumnos){
    $nombre = $this->db->escape($nombre);
    $codigo = $this->db->escape($codigo);
    $alumnos = $this->db->escape($alumnos);
    $query = $this->db->query("call esp_alta_materia($nombre, $codigo, $alumnos)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Baja una materia. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la materia
   * @return string
   */
  public function baja($idMateria){
    $idMateria = $this->db->escape($idMateria);
    $query = $this->db->query("call esp_baja_materia($idMateria)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
   
  /**
   * Obtener el listado de materias a las que puede acceder el docente. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador de usuario
   * @return array
   */
  public function listarMateriasDocente($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_listar_materias_docente($id)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener el listado de materias a las que puede acceder el director de carreras. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador de usuario
   * @return array
   */
  public function listarMateriasDirector($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_listar_materias_director($id)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  
  /**
   * Obtener el listado de materias a las que puede acceder el jefe de departamento. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador de usuario
   * @return array
   */
  public function listarMateriasJefeDepartamento($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_listar_materias_jefe_departamento($id)");
    $data = $query->result('Materia');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }

}
?>