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
  
  <title>Respuestas por Encuesta - <?php echo NOMBRE_SISTEMA?></title>
  <style>
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
        <h5 class="text-center"><?php echo $carrera->nombre?></h5>
        <h4 class="text-center">Asignatura: <?php echo $materia->nombre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5 class="separador">Datos Generales</h5>
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
            echo '<p>Claves de acceso: '.$clave->clave.'</p>';
            echo '<p>Clave generada el : '.$clave->generada.'</p>';
            echo '<p>Clave utilizada el: '.date('d/m/Y G:i:s', strtotime($clave->utilizada)).'</p>';
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <?php 
        foreach ($secciones as $seccion) {
          //por cada subseccion y por cada docente
          if (count($seccion['subsecciones']) > 0){
            echo '
            <h5 class="separador">'.$seccion['seccion']->texto.'</h5>';
            foreach ($seccion['subsecciones'] as $subseccion){
              echo'
              <div class="row">
                <div class="span12">
                  <h3>'.$subseccion['docente']->nombre.' '.$subseccion['docente']->apellido.'</h3>
                  <div class="row">';
                    $col = 0;
                    foreach ($subseccion['items'] as $i){
                      $item = &$i['item'];
                      $respuestas = &$i['respuestas'];

                      //genero el html de la ayuda contextual
                      $tip = ($item->descripcion!='')?'<span class="badge badge-info" data-toggle="tooltip" title="'.$item->descripcion.'">!</span>':'';
                      
                      switch($item->tipo){
                      case TIPO_SELECCION_SIMPLE: case TIPO_NUMERICA: case TIPO_TEXTO_SIMPLE:
                        printf('
                        <div class="item span6">
                          <p>%s %s</p>
                          <p><b>%s</b></p>
                        </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
                        $col = $col+1;
                        break;
                      case TIPO_TEXTO_MULTILINEA:
                        if ($col%2 == 1) echo'</div><div class="row">';
                        printf ('
                        <div class="item span12">
                          <p>%s %s</p>
                          <p><b>%s</b></p>
                        </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
                        $col = 0;
                        break;
                      }
                      if ($col%2 == 0) echo'</div><div class="row">';
                    }//foreach items
                    echo '
                  </div>';
                  if($subseccion['indice']) echo '<h4>Índice para el docente: '.round($subseccion['indice'],2).'</h4>';
                  echo '
                </div>
              </div>';
            }//foreach subsecciones
            if($seccion['indice']) echo '<h4>Índice de la Sección: '.round($seccion['indice'],2).'</h4>';
          }//if 
        }//foreach secciones
        if($indice) echo '<h5 class="separador"></h5><h4>Índice global: '.round($indice,2).'</h4>';
        ?>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>  

  <script src="<?php echo base_url('js/bootstrap-tooltip.min.js')?>"></script>
  <script>
    $('span.badge').tooltip();
  </script>
</body>
</html>