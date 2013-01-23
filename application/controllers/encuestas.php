<?php

/**
 * 
 */
class Encuestas extends CI_Controller{
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('index.php', $data);
  }
  
 
  public function listar($pagInicio=0){
    if (!is_numeric($pagInicio)){
      show_error('El número de página es inválido.');
      return;
    }
    
    //VERIFICAR QUE EL USUARIO TIENE PERMISOS PARA CONTINUAR!!!!
    
    //cargo modelos, librerias, etc.
    $this->load->library('pagination');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_encuestas','ge');
    
    $cantidadEncuestas = $this->ge->cantidad();
    $encuestas = $this->ge->listar($pagInicio, 5);

    //genero la lista de links de paginación
    $config['base_url'] = site_url("encuestas/listar");
    $config['total_rows'] = $cantidadEncuestas;
    $config['per_page'] = 5;
    $config['uri_segment'] = 3;
    $this->pagination->initialize($config);
    
    //obtengo lista de encuestas
    $tabla = array();
    foreach ($encuestas as $i => $encuesta) {
      $tabla[$i]=array(
        'IdEncuesta' => $encuesta->IdEncuesta,
        'IdFormulario' => $encuesta->IdFormulario,
        'Año' => $encuesta->Año,
        'Cuatrimestre' => $encuesta->Cuatrimestre,
        'FechaInicio' => $encuesta->FechaInicio,
        'FechaFin' => $encuesta->FechaFin
       );
    }

    //envio datos a la vista
    $data['tabla'] = $tabla; //array de datos de los Departamentos
    $data['paginacion'] = $this->pagination->create_links(); //html de la barra de paginación
    $data['usuarioLogin'] = unserialize($this->session->userdata('usuarioLogin')); //objeto Persona (usuario logueado)
    $this->load->view('lista_encuestas', $data);
  }
  
  
  public function informeMateria(){
    $IdMateria = 5;
    $IdCarrera = 5;
    $IdEncuesta = 1;
    $IdFormulario = 1;
    
    $this->load->model('Opcion');
    $this->load->model('Pregunta');
    $this->load->model('Item');
    $this->load->model('Seccion');
    $this->load->model('Materia');
    $this->load->model('Carrera');
    $this->load->model('Formulario');
    $this->load->model('Encuesta');
    $this->load->model('Gestor_formularios','gf');
    $this->load->model('Gestor_materias','gm');
    $this->load->model('Gestor_carreras','gc');
    $this->load->model('Gestor_encuestas','ge');

    $encuesta = $this->ge->dame($IdEncuesta, $IdFormulario);
    $formulario = $this->gf->dame($IdFormulario);
    $carrera = $this->gc->dame($IdCarrera);
    $materia = $this->gm->dame($IdMateria);
    
    $secciones = $formulario->listarSeccionesCarrera($IdCarrera);
    $datos_secciones = array(); 
    foreach ($secciones as $i => $seccion) {
      $items = $seccion->listarItems();
      $datos_items = array();
      foreach ($items as $j => $item) {
        $opciones = $item->listarOpciones();
        $datos_opciones = array();
        foreach ($opciones as $k => $opcion) {
          $datos_opciones[$k] = array(
            'idOpcion' => $opcion->IdOpcion,
            'texto' => $opcion->Texto
            );
        }
        switch ($item->Tipo) {
          case 'S': case 'M': case 'N':
            $datos_respuestas = $encuesta->respuestasPreguntaMateria($item->IdPregunta, $IdMateria, $IdCarrera);
            break;
          case 'T': case 'X':
            $datos_respuestas = $encuesta->textosPreguntaMateria($item->IdPregunta, $IdMateria, $IdCarrera);
          default:
            break;
        }
        
        $datos_items[$j] = array(
          'idPregunta' => $item->IdPregunta,
          'texto' => $item->Texto,
          'tipo' => $item->Tipo,
          'opciones' => $datos_opciones,
          'respuestas' => $datos_respuestas 
          );
      }
      $datos_secciones[$i] =  array(
        'texto' => $seccion->Texto,
        'preguntas' => $datos_items
        );
    }

    $datos['encuesta'] = array(
      'año' => $encuesta->Año,
      'cuatrimestre' => $encuesta->Cuatrimestre,
      'fechaInicio' => $encuesta->FechaInicio,
      'fechaFin' => $encuesta->FechaFin);
    $datos['formulario'] = array(
      'titulo' => $formulario->Titulo,
      'descripcion' => $formulario->Descripcion);
    $datos['carrera'] = array(
      'nombre' => $carrera->Nombre);
    $datos['materia'] = array(
      'nombre' => $materia->Nombre);
    $datos['claves'] = $encuesta->cantidadClavesMateria($IdMateria, $IdCarrera);
    $datos['secciones'] = $datos_secciones;
    $this->load->view('informe_materia', $datos);
  }




  public function graficoPregunta($IdEncuesta, $IdFormulario, $IdPregunta, $IdMateria, $IdCarrera){
    // Standard inclusions   
    $this->load->model('Encuesta');
    $this->load->library('pChart/pData');
    $this->load->library('pChart/pChart', array(500,160));
    
    $this->Encuesta->IdEncuesta = $IdEncuesta;
    $this->Encuesta->IdFormulario = $IdFormulario;
    $datos_respuestas = $this->Encuesta->respuestasPreguntaMateria($IdPregunta, $IdMateria, $IdCarrera);
    $datos = array(1,1,1,3);
    $etiquetas = array(1,2,3,4);
    foreach ($datos_respuestas as $i => $val) {
      $datos[$i] = $val['Cantidad'];
      $etiquetas[$i] = $val['Opcion'];
    }
    
    // Dataset definition 
    $this->pdata->AddPoint($datos,"Serie1");
    $this->pdata->AddPoint($etiquetas,"AbsciseLabels");
    $this->pdata->AddAllSeries();
    $this->pdata->SetAbsciseLabelSerie("AbsciseLabels");
    
     // Inicializar gráfico
    $this->pchart->setFontProperties("Fonts/tahoma.ttf",14);
    $this->pchart->setGraphArea(36,8,464,140);
    $this->pchart->drawFilledRectangle(0,0,500,160,255,255,255,FALSE);
    $this->pchart->drawGraphArea(255,255,255,TRUE);
    $this->pchart->drawScale($this->pdata->GetData(),$this->pdata->GetDataDescription(), SCALE_START0, 50,50,50, TRUE,0,2,TRUE);  
    $this->pchart->drawGrid(4,TRUE,230,230,230,50);
     
    // Dibujar el grafico de barras    
    $this->pdata->RemoveSerie("AbsciseLabels");
    $this->pchart->setColorPalette(0,0,150,110);
    $this->pchart->drawStackedBarGraph($this->pdata->GetData(),$this->pdata->GetDataDescription(),100);
    $this->pchart->Stroke();
  }
  
}

?>