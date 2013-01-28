<?php

/**
 * 
 */
class Gestor_preguntas extends CI_Model{

	function __construct() {
		parent::__construct();
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
    $this->db->reconnect();
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
    $this->db->reconnect();
    return ($data)?$data->Cantidad:0;
  }

    
  /**
   * Da de Alta una nueva pregunta. Devuleve el id en caso de éxito o un mensaje en caso de error.
   *
   * @access  public
   * @param identificador de la carrera que crea la pregunta
   * @param texto de la pregunta
   * @param una descripcion opcional de la pregunta. Se usará como ayuda contextual.
   * @param tipo de respuesta a la pregunta
   * @param valor que indica si la pregunta puede debe ser respondida si o si.
   * @param minimo valor que puede tomar la respuesta si es del tipo numerica
   * @param maximo valor que puede tomar la respuesta si es del tipo numerica
   * @param paso de un valor a otro en la respuesta si es del tipo numerica
   * @param unidad en que se mide la respuesta a la pregunta
   * @return  string
   */
  public function alta($IdCarrera, $Texto, $Descripcion, $Tipo, $Obligatoria, $OrdenInverso, $LimiteInferior, $LimiteSuperior, $Paso, $Unidad){
    $Nombre = $this->db->escape($Nombre);
    $IdJefeDepartamento = $this->db->escape($IdJefeDepartamento);
    $query = $this->db->query("call esp_alta_pregunta($IdCarrera, $Texto, $Descripcion, $Tipo, $Obligatoria, $OrdenInverso, $LimiteInferior, $LimiteSuperior, $Paso, $Unidad)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
  }
  
  
  /**
   * Da de Baja una pregunta. Devuleve 'ok' en caso de éxito o un mensaje en caso de error.
   *
   * @access public
   * @param identificador de pregunta
   * @return string
   */
  public function baja($IdPregunta){
    $IdPregunta = $this->db->escape($IdPregunta);
    $query = $this->db->query("call esp_baja_pregunta($IdPregunta)");
    $data = $query->row();
    $query->free_result();
    $this->db->reconnect();
    return ($data)?$data->Mensaje:'No se pudo conectar con la base de datos.';
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
    $this->db->reconnect();
    return $data;
  }


}
?>