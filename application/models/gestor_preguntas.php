<?php

/**
 * 
 */
class Gestor_preguntas extends CI_Model{

	function __construct() {
		parent::__construct();
	}

  
  /**
   * Obtener una pregunta a partir de su id. Devuleve un objeto en caso de éxito, o FALSE en caso de error.
   *
   * @access public
   * @param identificador de pregunta
   * @return object
   */
  public function dame($idPregunta){
    $idPregunta = $this->db->escape($idPregunta);
    $query = $this->db->query("call esp_dame_pregunta($idPregunta)");
    $data = $query->result('Pregunta');
    $query->free_result();
    //$this->db->reconnect();
    return ($data != FALSE)?$data[0]:FALSE;
  }


  /**
   * Obtener el listado de preguntas. Devuleve un array de objetos.
   *
   * @access public
   * @param item inicial del listado a mostrar
   * @param cantidad de items a mostrar (tamaño de página)
   * @return array
   */
  public function listar($pagInicio, $pagLongitud){
    $pagInicio = $this->db->escape($pagInicio);
    $pagLongitud = $this->db->escape($pagLongitud);
    $query = $this->db->query("call esp_listar_preguntas($pagInicio, $pagLongitud)");
    $data = $query->result('Pregunta');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }


  /**
   * Obtener la cantidad de preguntas. 
   *
   * @access public
   * @return int
   */  
  public function cantidad(){
    $query = $this->db->query("call esp_cantidad_preguntas()");
    $data=$query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->cantidad:0;
  }

    
  /**
   * Da de Alta una nueva pregunta. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la carrera que crea la pregunta
   * @param texto de la pregunta
   * @param una descripcion opcional de la pregunta. Se usará como ayuda contextual.
   * @param tipo de respuesta a la pregunta
   * @param minimo valor que puede tomar la respuesta si es del tipo numerica
   * @param maximo valor que puede tomar la respuesta si es del tipo numerica
   * @param paso de un valor a otro en la respuesta si es del tipo numerica
   * @param unidad en que se mide la respuesta a la pregunta
   * @return  string
   */
  public function alta($texto, $descripcion, $tipo, $ordenInverso, $limiteInferior, $limiteSuperior, $paso, $unidad){
    $texto = $this->db->escape($texto);
    $descripcion= $this->db->escape($descripcion);
    $tipo = $this->db->escape($tipo);
    $ordenInverso = $this->db->escape($ordenInverso);
    $limiteInferior = $this->db->escape($limiteInferior);
    $limiteSuperior = $this->db->escape($limiteSuperior);
    $paso = $this->db->escape($paso);
    $unidad = $this->db->escape($unidad);
    $query = $this->db->query("call esp_alta_pregunta($texto, $descripcion, $tipo, $ordenInverso, $limiteInferior, $limiteSuperior, $paso, $unidad)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Baja una pregunta. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de pregunta
   * @return string
   */
  public function baja($idPregunta){
    $idPregunta = $this->db->escape($idPregunta);
    $query = $this->db->query("call esp_baja_pregunta($idPregunta)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Buscar preguntas por el texto de la misma. Devuleve un array de objetos.
   *
   * @access public
   * @param fragmento de la pregunta
   * @return arrayPregunta
   */
  public function buscar($texto){
    $texto = $this->db->escape($texto);
    $query = $this->db->query("call esp_buscar_preguntas($texto)");
    $data = $query->result('Pregunta');
    $query->free_result();
    //$this->db->reconnect();
    return $data;
  }


  /**
   * Modificar una pregunta (solamente reformularla)
   * 
   * @access  public
   * @param identificador de la pregunta
   * @param texto de la pregunta
   * @param una descripcion opcional de la pregunta. Se usará como ayuda contextual.
   * @return  string
   */
  public function modificar($idPregunta, $texto, $descripcion){
    $idPregunta = $this->db->escape($idPregunta);
    $texto = $this->db->escape($texto);
    $descripcion = $this->db->escape($descripcion);
    $query = $this->db->query("call esp_modificar_pregunta($idPregunta, $texto, $descripcion)");
    $data = $query->row();
    $query->free_result();
    //$this->db->reconnect();
    return ($data)?$data->mensaje:'No se pudo conectar con la base de datos.';
  }
}
?>