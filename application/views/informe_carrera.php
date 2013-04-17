<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.min.css')?>" rel="stylesheet">
  <style>body{padding-top:40px;}</style>
  <link href="<?php echo base_url('css/bootstrap-responsive.min.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.min.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/imprimir.css')?>" rel="stylesheet" media="print">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>
  
  <title>Informe por Carrera - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
    .row-fluid [class*="span"]{margin-left:0;}
    .row.not-break{page-break-inside:avoid;}
  </style>
</head>
<body>
  <!-- Menu de opciones -->
  <div id="barra-herramientas">
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="<?php echo site_url()?>">Sistema Encuestas</a>
          <ul class="nav">
            <li><a href="#" onclick="window.print()"><i class="icon-print"></i> Imprimir...</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-download-alt"></i> Descargar Datos <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <a class="form"><form action="<?php echo site_url('informes/archivoCarrera')?>" method="post">
                    <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
                    <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
                    <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
                    <input type="hidden" name="tipo" value="xlsx" />
                    <input type="submit" name="submit" class="btn btn-link" value="Libro de Excel 2007-2010 (.xlsx)..." />
                  </form></a>
                </li>
                <li>
                  <a class="form"><form action="<?php echo site_url('informes/archivoMateria')?>" method="post">
                    <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
                    <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
                    <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
                    <input type="hidden" name="tipo" value="xls" />
                    <input type="submit" name="submit" class="btn btn-link" value="Libro de Excel 97-2003 (.xls)..."/>
                  </form></a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Informe -->
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2 class="text-center"><?php echo $formulario->titulo?></h2>
        <h4 class="text-center"><?php echo $formulario->descripcion?></h4>
        <h4 class="text-center"><?php echo $departamento->nombre?></h4>
        <h5 class="text-center"><?php echo $carrera->nombre?></h5>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5 class="separador">Estadísticas Generales</h5>
        <div class="row">
          <div class="span6">
            <?php
            echo '<p>Año: '.$encuesta->año.'</p>';
            echo '<p>'.PERIODO.': '.$encuesta->cuatrimestre.'</p>';
            echo '<p>Fecha de inicio de las encuestas: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaInicio)).'</p>';
            echo '<p>Fecha de finalización de las encuestas: '.date('d/m/Y G:i:s', strtotime($encuesta->fechaFin)).'</p>';
            ?>
          </div>
          <div class="span6">
            <?php
            echo '<p>Claves generadas: '.$claves['generadas'].'</p>';
            echo '<p>Claves utilizadas: '.$claves['utilizadas'].'</p>';
            echo '<p>Primer acceso: '.date('d/m/Y G:i:s', strtotime($claves['primerAcceso'])).'</p>';
            echo '<p>Último acceso: '.date('d/m/Y G:i:s', strtotime($claves['ultimoAcceso'])).'</p>';
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <?php
        foreach ($secciones as $i => $seccion){
          echo '
          <h5 class="separador">'.$seccion['seccion']->texto.'</h5>';
          //por cada item de la sección
          foreach ($seccion['items'] as $pregunta){
            switch($pregunta['item']->tipo){
            //preguntas con opciones
            case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA:
              echo '
              <div class="row not-break">';
                echo ($graficos)?'<div class="span8">':'<div class="span12">';
                  echo'
                  <p>'.$pregunta['item']->texto.'</p>
                  <div class="row-fluid">';
                    foreach ($pregunta['respuestas'] as $k => $respuesta){   
                      echo '
                      <div class="span3">'.
                        (($respuesta['texto']!='')?$respuesta['texto']:'No Contesta').
                        ': <b>'.$respuesta['cantidad'].'</b>
                      </div>';
                    }
                    echo '
                  </div>
                </div>';
                if ($graficos){
                  echo '
                  <div class="span4">
                    <img src="'.site_url("pCharts/graficoPreguntaCarrera/".
                    $encuesta->idEncuesta.'/'.$encuesta->idFormulario."/".$pregunta['item']->idPregunta.'/'.$carrera->idCarrera).
                    '" width="400" height="120" />
                  </div>';
                }
              echo '</div>';
              break;
            }//switch
          }//foreach preguntas
          if($seccion['indice']) echo '<h4>Índice de la Sección: '.round($seccion['indice'],2).'</h4>'; 
        }//foreach secciones
        if($indice) echo '<h5 class="separador"></h5><h4>Índice global: '.round($indice,2).'</h4>';
        ?>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>
  
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
</body>
</html>