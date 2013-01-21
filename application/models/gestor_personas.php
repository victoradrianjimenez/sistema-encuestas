<?php

/**
 * 
 */
class Gestor_personas extends CI_Model{
	
	function __construct() {
    parent::__construct();		
	}

    
  /**
   * Da de Alta una nueva persona. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param nombre del departamento
   * @return  string
   */
  public function alta($Apellido, $Nombre, $Usuario, $Email, $Contraseña){
    $Apellido = $this->db->escape($Apellido);
    $Nombre = $this->db->escape($Nombre);
    $Usuario = $this->db->escape($Usuario);
    $Email = $this->db->escape($Email);
    $Contraseña = $this->db->escape($Contraseña);
    $query = $this->db->query("call esp_alta_persona($Apellido, $Nombre, $Usuario, $Email, $Contraseña)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Obtener los datos de una persona a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de persona
   * @return object
   */
  public function dame($IdPersona){
    $IdPersona = $this->db->escape($IdPersona);
    $query = $this->db->query("call esp_dame_persona($IdPersona)");
    $data = $query->result('Persona');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  /**
   * Verificar un usuario y contraseña. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param nombre de usuario
   * @param contraseña (cifrada) 
   * @return object
   */
  function validarUsuario($Usuario, $Contraseña){
    $Usuario = $this->db->escape($Usuario);
    $Contraseña = $this->db->escape($Contraseña);
    $query = $this->db->query("call esp_validar_usuario($Usuario, $Contraseña)");
    $data = $query->result('Persona');
    $query->free_result();
    $this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  /**
   * Obtener el listado de personas. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return arrayPersonas
   */
  public function listar($pagInicio, $pagLongitud){
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_personas($pagInicio, $pagLongitud)");
    $data = $query->result('Persona');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }


  /**
   * Obtener la cantidad de personas.
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_personas()");
    $data=$query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data!=FALSE)?$data->Cantidad:0;
  }
  
    
  /**
   * Buscar personas por nombre o apellido. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento del nombre o apellido de la persona
   * @return arrayPersonas
   */
  public function buscar($nombre){
    $nombre = $this->db->escape($nombre);
    $query = $this->db->query("call esp_buscar_personas($nombre)");
    $data = $query->result('Persona');
    $query->free_result();
    $this->db->reconnect();
    return $data;
  }
  
}
