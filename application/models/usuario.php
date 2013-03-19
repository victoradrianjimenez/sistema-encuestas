<?php

/**
 * 
 */
class Usuario extends CI_Model{
  var $id = null;
  var $username;
  var $password;
  var $email;
  var $last_login;
  var $active;
  var $nombre;
  var $apellido;
  var $idImagen;
  
	function __construct(){
		parent::__construct();
	}
  
  
  /**
   * Obtener datos de un docente en su relacion con una materia. Devuleve un array.
   *
   * @access public
   * @param identificador de la materia
   * @return object
   */
  public function dameDatosDocente($idMateria){
    $idMateria = $this->db->escape($idMateria);
    $id = $this->db->escape($this->id);
    $query = $this->db->query("call esp_dame_docente_materia($id, $idMateria)");
    $data = $query->row_array();
    $query->free_result();
    return $data;
  }

}

?>