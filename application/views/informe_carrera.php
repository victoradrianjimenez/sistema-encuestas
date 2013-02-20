<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <!-- Le styles -->
  <link href="<?php echo base_url('css/bootstrap.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('css/bootstrap-responsive.css')?>" rel="stylesheet" media="screen">
  <link href="<?php echo base_url('css/app.css')?>" rel="stylesheet">
  
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/html5shiv.js')?>"></script>
  
  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="ico/favicon.png">

  <title>Informe Carrera</title>
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{text-align:center;}
    h5.separador{border-bottom: 3px solid #2BA6CB;}
    ul.respuestas{list-style-position:inside;}
    .row-fluid [class*="span"]{margin-left:0;}
  </style>
</head>
<body>
  <div class="container">
    <div id="header" class="row">
      <div class="span12">
        <h2><?php echo $formulario->titulo?></h2>
        <h4><?php echo $formulario->descripcion?></h4>
        <h4><?php echo $departamento->nombre?></h4>
        <h5><?php echo $carrera->nombre?></h5>
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
          echo '
          <h5 class="separador">'.$seccion['seccion']->texto.'</h5>';
          foreach ($seccion['items'] as $pregunta){
            echo '
            <div class="row">
              <div class="span12">';
                switch($pregunta['item']->tipo){
                //preguntas con opciones
                case 'S':case 'N':
                  echo '
                  <div class="row">
                    <div class="span9">
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
                    </div>
                    <div class="span3">
                      <img src="'.site_url("pcharts/graficoPreguntaCarrera/".
                      $encuesta->idEncuesta.'/'.$encuesta->idFormulario."/".$pregunta['item']->idPregunta.'/'.$carrera->idCarrera).
                      '" width="400" height="160" />
                    </div>
                  </div>';
                  break;
                }//switch
                echo'
              </div>
            </div>';
          }//foreach preguntas
          if($seccion['indice']) echo '<h4>Índice de la Sección: '.$seccion['indice'].'</h4>'; 
        }//foreach secciones
        ?>
        <?php if($indice) echo '<h5 class="separador"></h5><h4>Índice global: '.$indice?>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>
</body>
</html>