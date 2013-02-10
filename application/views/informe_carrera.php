<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Informe Carrera</title>
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{text-align:center;}
    h5.separador{border-bottom: 3px solid #2BA6CB;}
  </style>
</head>
<body>
  <div id="header" class="row">
    <h2><?php echo $formulario->titulo?></h2>
    <h4><?php echo $formulario->descripcion?></h4>
    <h4><?php echo $carrera->nombre?></h4>
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
  <div class="row">
    <?php foreach ($secciones as $i => $seccion):?>
      <h5 class="separador"><?php echo $seccion['seccion']->texto?></h5>
      <div class="row">
        <div class="twelve columns">
          <?php
          //por cada subseccion o docente
          foreach ($seccion['subsecciones'] as $j => $pregunta){
            echo '
            <div class="row ">
              <div class="twelve columns">';
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
                    <img src="'.site_url("pcharts/graficoPreguntaCarrera/".$encuesta->idEncuesta.'/'.$encuesta->idFormulario."/".$pregunta['item']->idPregunta.'/'.$carrera->idCarrera).'" width="400" height="160" />
                  </div>';
                  break;
                }//switch
            echo '
            </div></div>' ;
          }
          ?>
        </div>
      </div>   
    <?php endforeach //secciones?>
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