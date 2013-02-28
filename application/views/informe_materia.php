<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.css')?>" rel="stylesheet">
  <style>body{padding-top:40px;}</style>
  <link href="<?php echo base_url('css/bootstrap-responsive.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/imprimir.css')?>" rel="stylesheet" media="print">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>

  <title>Informe Materia</title>
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{text-align:center;}
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
    .row-fluid [class*="span"]{margin-left:0;}
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
            <li><a href="#" onclick="window.print()">Imprimir...</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Descargar Datos <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#">
                    <form action="<?php echo site_url('informes/archivoMateria')?>" method="post">
                      <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
                      <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
                      <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
                      <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
                      <input type="hidden" name="tipo" value="xlsx" />
                      <input type="submit" name="submit" value="Libro de Excel 2007-2010 (.xlsx)..." />
                    </form>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <form action="<?php echo site_url('informes/archivoMateria')?>" method="post">
                      <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
                      <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
                      <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
                      <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
                      <input type="hidden" name="tipo" value="xls" />
                      <input type="submit" name="submit" value="Libro de Excel 97-2003 (.xls)..." />
                    </form>
                  </a>
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
        <h2><?php echo $formulario->titulo?></h2>
        <h4><?php echo $formulario->descripcion?></h4>
        <h4><?php echo $departamento->nombre?></h4>
        <h5><?php echo $carrera->nombre?></h5>
        <h5>Asignatura: <?php echo $materia->nombre?></h5>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5 class="separador">Estadísticas Generales</h5>
        <div class="row">
          <div class="span6">
            <?php
            echo '<p>Año: '.$encuesta->año.'</p>';
            echo '<p>Cuatrimestre / período: '.$encuesta->cuatrimestre.'</p>';
            echo '<p>Fecha de inicio de las encuestas: '.$encuesta->fechaInicio.'</p>';
            echo '<p>Fecha de finalización de las encuestas: '.$encuesta->fechaFin.'</p>';
            ?>
          </div>
          <div class="span6">
            <?php
            echo '<p>Claves generadas: '.$claves['generadas'].'</p>';
            echo '<p>Claves utilizadas: '.$claves['utilizadas'].'</p>';
            echo '<p>Primer acceso: '.$claves['primerAcceso'].'</p>';
            echo '<p>Último acceso: '.$claves['ultimoAcceso'].'</p>';
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <?php 
        foreach ($secciones as $i => $seccion){
          //por cada subseccion y por cada docente
          if (count($seccion['subsecciones']) > 0){
            echo '
            <h5 class="separador">'.$seccion['seccion']->texto.'</h5>';
            foreach ($seccion['subsecciones'] as $subseccion){
              echo '
              <div class="row">
                <div class="span12">
                  <h3>'.$subseccion['docente']->nombre.' '.$subseccion['docente']->apellido.'</h3>';
                  //por cada pregunta perteneciente a la seccion
                  foreach ($subseccion['preguntas'] as $pregunta){
                    switch($pregunta['item']->tipo){
                    //preguntas con opciones
                    case 'S':case 'N': 
                      echo '
                      <div class="row">';
                        echo ($graficos)?'<div class="span9">':'<div class="span12">';
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
                          <div class="span3">
                            <img src="'.site_url("pcharts/graficoPreguntaMateria/".
                              $encuesta->idEncuesta.'/'.$encuesta->idFormulario."/".$pregunta['item']->idPregunta.'/'.$subseccion['docente']->id.'/'.$materia->idMateria.'/'.$carrera->idCarrera).
                              '" width="400" height="160" />
                          </div>';
                        }
                      echo '</div>';
                      break;
                    case 'T': case 'X':
                      echo '
                      <p>'.$pregunta['item']->texto.'</p>
                      <ul class="respuestas">';
                        foreach ($pregunta['respuestas'] as $k => $respuesta){   
                          echo '<li>'.$respuesta['texto'].'</li>';
                        }
                      echo '
                      </ul>';
                      break;
                    }//switch
                  }//foreach preguntas
                  if($subseccion['indice']) echo '<h4>Índice para el docente: '.$subseccion['indice'].'</h4>';
                  echo '
                </div>
              </div>';
            }//foreach subsecciones
            if($seccion['indice']) echo '<h4>Índice de la Sección: '.$seccion['indice'].'</h4>'; 
          }//if
        }//foreach secciones
        ?>
        <?php if($indice) echo '<h5 class="separador"></h5><h4>Índice global: '.$indice?>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>

  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
</body>
</html>