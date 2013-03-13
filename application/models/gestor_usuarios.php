<?php

/**
 * 
 */
class Gestor_usuarios extends CI_Model{
	
	function __construct() {
    parent::__construct();		
	}

    
  /**
   * Da de Alta un nuevo usuario. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param apellido del usuario
   * @param nombre del usuario
   * @return  string
   
  public function alta($apellido, $nombre, $username, $email, $password){
    $apellido = $this->db->escape($apellido);
    $nombre = $this->db->escape($nombre);
    $username = $this->db->escape($username);
    $email = $this->db->escape($email);
    $password = $this->db->escape($password);
    $query = $this->db->query("call esp_alta_usuario($apellido, $nombre, $username, $email, $password)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  */
  
  /**
   * Obtener los datos de un usuario a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de usuario
   * @return object
   */
  public function dame($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_dame_usuario($id)");
    $data = $query->result('Usuario');
    $query->free_result();
    //$this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  /**
   * Da de Baja una usuario. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador del usuario
   * @return string
   */
  public function baja($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_baja_usuario($id)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Obtener el listado de usuarios. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listar($pagInicio, $pagLongitud){
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_usuarios($pagInicio, $pagLongitud)");
    $data = $query->result('Usuario');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  /**
   * Obtener el listado de usuarios que pertenecen a un grupo. Devuleve un array de objetos.
   *
   * @access public
   * @param identificador de grupo
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listarGrupo($idGrupo, $pagInicio, $pagLongitud){
    $idGrupo = $this->db->escape($idGrupo);
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_usuarios_grupo($idGrupo, $pagInicio, $pagLongitud)");
    $data = $query->result('Usuario');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  /**
   * Modifica un usuario. Devuleve 'ok.' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la usuario
   * @param apellido de la usuario
   * @param nombre de la usuario
   * @return string
   
  public function modificar($id, $apellido, $nombre){
    $id = $this->db->escape($id);
    $apellido = $this->db->escape($apellido);
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_modificar_usuario($id, $apellido, $nombre)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  */
  
  
  /**
   * Obtener la cantidad de usuarios.
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_usuarios()");
    $data=$query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  /**
   * Obtener la cantidad de usuarios.
   *
   * @access public
   * @return int
   */  
  public function cantidadGrupo($idGrupo){
    $idGrupo = $this->db->escape($idGrupo);
    $query = $this->db->query("call esp_cantidad_usuarios_grupo($idGrupo)");
    $data=$query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }
  
  /**
   * Buscar usuarios por nombre o apellido. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre o apellido del usuario
   * @return array
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_usuarios($nombre)");
    $data = $query->result('Usuario');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
  
  
  
  
  /**
   * Obtener el listado de carreras a las que puede acceder el usuario. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
  
  public function listarCarreras($id){
    $id = $this->db->escape($id);
    $query = $this->db->query("call esp_listar_carreras_usuario($id)");
    $data = $query->result('Carrera');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }
  
   */
  

  
  
  
  
  
  
  
  
  
}
