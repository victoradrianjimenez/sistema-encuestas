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

  <title>Respuestas Encuesta</title>
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
        <h5><?php echo $carrera->nombre?></h5>
        <h4>Asignatura: <?php echo $materia->nombre?></h4>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <h5 class="separador">Estadísticas Generales</h5>
        <div class="row">
          <div class="span6">
            
            
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="span12">
        <?php foreach ($secciones as $seccion) {
          echo'
          <h5 class="separador">'.$seccion['seccion']->texto.'</h5>
          <h6>'.$seccion['seccion']->descripcion.'</h6>';
          
          //SUBSECCIONES
          
          foreach ($seccion['subsecciones'] as $subseccion){
            $docente = $subseccion['docente'];
            echo'
            <div class="row">
              <div class="span12">
                <h3>'.$docente->nombre.' '.$docente->apellido.'</h3>
                <div class="row-fluid">';
                  $col = 0;
                  //ITEMS
                  foreach ($subseccion['items'] as $i){
                    $item = &$i['item'];
                    $respuestas = &$i['respuestas'];
      
                    //genero el html de la ayuda contextual
                    $tip = ($item->descripcion!='')?'<i class="icon-info-sign" data-toggle="tooltip" title="" data-original-title="'.$item->descripcion.'"></i>':'';
                    
                    //para las preguntas con opciones
                    if($item->tipo == 'S'){
                      printf('
                      <div class="item span6">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
                    }
                    //multiple choice
                    elseif($item->tipo == 'M'){
      
                    }
                    //para las preguntas numericas
                    elseif($item->tipo == 'N'){
                      printf ('
                      <div class="item span6">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
      
                    }
                    //texto de una linea
                    elseif($i['item']->tipo == 'T'){
                      printf ('
                      <div class="item span6">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
                    }
                    //texto multilinea
                    elseif($item->tipo == 'X'){
                      printf ('
                      <div class="item span6">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>', $item->texto, $tip, (isset($respuestas[0]))?$respuestas[0]['texto']:'NC');
                    }
                    $col = ($col+1)%2;
                    if ($col == 0) echo'</div><div class="row-fluid">';
                  }//foreach items
                  echo '
                </div>
              </div>
            </div>';
          }//foreach subsecciones
        }
        ?>
      </div>
    </div>
  </div>
  <?php //include 'templates/footer2.php'?>  

  <script src="<?php echo base_url('js/bootstrap-tooltip.js')?>"></script>
  <script>
    $('i.icon-info-sign').tooltip();
  </script>
</body>
</html>