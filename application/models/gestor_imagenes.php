<?php


/**
 * 
 */
class Gestor_imagenes extends CI_Model{

	function __construct() {
		parent::__construct();
	}


  /**
   * Da de Alta una imagen. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param ruta del archivo de imagen
   * @param tipo de imagen (Content-Type de cabeceras HTTP)
   * @return  string
   */
  public function alta($archivo, $tipo){
    $oFile = fopen($archivo, 'r');
    if ($oFile){
      $tipo = $this->db->escape($tipo);
      $sContent = fread($oFile, filesize($archivo));
      $query = $this->db->query("call esp_alta_imagen('".base64_encode($sContent)."', $tipo)");
      $data = $query->row();
      $query->free_result();
      return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
    }      
    return 'No se pudo acceder al archivo de imagen.';    
  }
  
    
  /**
   * Obtener una imagen a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de imagen
   * @return object
   */
  public function dame($idImagen){
    $idImagen = $this->db->escape($idImagen);
    $query = $this->db->query("call esp_dame_imagen($idImagen)");
    $data = $query->result();
    $query->free_result();
    return ($data != FALSE)?$data[0]:FALSE;
  }
  
  
  /**
   * Dar de baja una imagen. Devuleve un PROCEDURE_SUCCESS en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de imagen
   * @return object
   */
  public function baja($idImagen){
    $idImagen = $this->db->escape($idImagen);
    $query = $this->db->query("call esp_baja_imagen($idImagen)");
    $data = $query->row();
    $query->free_result();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
}
?>