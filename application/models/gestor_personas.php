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
   * @param apellido de la persona
   * @param nombre de la persona
   * @return  string
   */
  public function alta($Apellido, $Nombre){
    $Apellido = $this->db->escape($Apellido);
    $Nombre = $this->db->escape($Nombre);
    $query = $this->db->query("call esp_alta_persona($Apellido, $Nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
   * Da de Baja una persona. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la persona
   * @return string
   */
  public function baja($IdPersona){
    $IdPersona = $this->db->escape($IdPersona);
    $query = $this->db->query("call esp_baja_persona($IdPersona)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
   * Modifica una persona. Devuleve 'ok.' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de la persona
   * @param apellido de la persona
   * @param nombre de la persona
   * @return string
   */
  public function modificar($IdPersona, $Apellido, $Nombre){
    $IdPersona = $this->db->escape($IdPersona);
    $Apellido = $this->db->escape($Apellido);
    $Nombre = $this->db->escape($Nombre);
    $query = $this->db->query("call esp_modificar_persona($IdPersona, $Apellido, $Nombre)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
    return ($data)?$data->Cantidad:0;
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
