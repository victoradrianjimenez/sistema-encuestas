<!DOCTYPE html>
<!-- Última revisión: 2012-02-10 2:35 a.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Informe Materia</title>
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{text-align:center;}
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
  </style>
</head>
<body>
  <div id="header" class="row">
    <h2><?php echo $formulario->titulo?></h2>
    <h4><?php echo $formulario->descripcion?></h4>
    <h4><?php echo $departamento->nombre?></h4>
    <h5><?php echo $carrera->nombre?></h5>
    <h5>Asignatura: <?php echo $materia->nombre?></h5>
  </div>
  <div class="row">
    <h5 class="separador">Estadísticas Generales</h5>
    <div class="six columns">
      <?php
        echo '<p>Año: '.$encuesta->año.'</p>';
        echo '<p>Cuatrimestre / período: '.$encuesta->cuatrimestre.'</p>';
        echo '<p>Fecha de inicio de las encuestas: '.$encuesta->fechaInicio.'</p>';
        echo '<p>Fecha de finalización de las encuestas: '.$encuesta->fechaFin.'</p>';
      ?>
    </div>
    <div class="six columns">
      <?php
        echo '<p>Claves generadas: '.$claves['generadas'].'</p>';
        echo '<p>Claves utilizadas: '.$claves['utilizadas'].'</p>';
        echo '<p>Primer acceso: '.$claves['primerAcceso'].'</p>';
        echo '<p>Último acceso: '.$claves['ultimoAcceso'].'</p>';
      ?>
    </div>
  </div>
  
  <?php 
  foreach ($secciones as $i => $seccion){
    //por cada subseccion y por cada docente
    if (count($seccion['subsecciones']) > 0){
      echo '
      <div class="row">
        <h5 class="separador">'.$seccion['seccion']->texto.'</h5>
        <div class="twelve columns">
          <div class="row">';
            foreach ($seccion['subsecciones'] as $j => $subseccion){
              echo '
              <div class="row">
                <div class="twelve columns">
                <h3>'.$subseccion['docente']->nombre.' '.$subseccion['docente']->apellido.'</h3>';
                //por cada pregunta perteneciente a la seccion
                foreach ($subseccion['preguntas'] as $pregunta){
                  switch($pregunta['item']->tipo){
                  //preguntas con opciones
                  case 'S':case 'N': 
                    echo '
                    <div class="nine columns">
                      <p>'.$pregunta['item']->texto.'</p>
                      <div class="row">';
                        foreach ($pregunta['respuestas'] as $k => $respuesta){   
                          echo '
                          <div class="three mobile-one columns end">'.
                            (($respuesta['texto']!='')?$respuesta['texto']:'No Contesta').
                            ': <b>'.$respuesta['cantidad'].'</b>
                          </div>';
                        }
                      echo '
                      </div>
                    </div>
                    <div class="three columns">
                      <img src="'.site_url("pcharts/graficoPreguntaMateria/".
                        $encuesta->idEncuesta.'/'.$encuesta->idFormulario."/".$pregunta['item']->idPregunta.'/'.$subseccion['docente']->id.'/'.$materia->idMateria.'/'.$carrera->idCarrera).
                        '" width="400" height="160" />
                    </div>';
                    break;
                  case 'T': case 'X':
                    echo '
                    <div class="twelve columns">
                      <p>'.$pregunta['item']->texto.'</p>
                      <ul class="respuestas">';
                        foreach ($pregunta['respuestas'] as $k => $respuesta){   
                          echo '<li>'.$respuesta['texto'].'</li>';
                        }
                      echo '
                      </ul>
                    </div>';
                    break;
                  }//switch
                }//foreach preguntas
                echo '
                <div class="twelve columns">';
                  if($subseccion['indice']) echo '<h4>Índice para el docente: '.$subseccion['indice'].'</h4>';
                echo '
                </div>
              </div>
            </div>';
          }//foreach subsecciones
          if($seccion['indice']) echo '<h4>Índice de la Sección: '.$seccion['indice'].'</h4>'; 
        echo '
        </div>
      </div>
    </div>';
    }//if
  }//foreach secciones
  ?>
  <div class="row">
    <?php if($indice) echo '<h5 class="separador"></h5><h4>Índice global: '.$indice?>
  </div>
  
  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer2.php'?>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
</body>
</html>