<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Listar Departamentos</title>
  
  <style>
    #header h1, #header h2, #header h3, #header h4, #header h5{
      text-align:center;
    }
    
    h5.separador{
      border-bottom: 3px solid #2BA6CB;
    }
    
  </style>
  
</head>
<body>
  <div id="header" class="row">
    <h2><?php echo $formulario['titulo']?></h2>
    <h4><?php echo $formulario['descripcion']?></h4>
    <h4><?php echo $carrera['nombre']?></h4>
    <h4>Asignatura: <?php echo $materia['nombre']?></h4>
  </div>
  <div class="row">
    <h5 class="separador">Estadísticas Generales</h5>
    <div class="six columns">
      <?php
        echo '<p>Año: '.$encuesta['año'].'</p>';
        echo '<p>Cuatrimestre/periodo: '.$encuesta['cuatrimestre'].'</p>';
        echo '<p>Fecha de Inicio de las encuestas: '.$encuesta['fechaInicio'].'</p>';
        echo '<p>Fecha de Fin de las encuestas: '.$encuesta['fechaFin'].'</p>';
      ?>
    </div>
    <div class="six columns">
      <?php
        echo '<p>Claves generadas: '.$claves['Generadas'].'</p>';
        echo '<p>Claves utilizadas: '.$claves['Utilizadas'].'</p>';
        echo '<p>Primer acceso: '.$claves['PrimerAcceso'].'</p>';
        echo '<p>Último acceso: '.$claves['UltimoAcceso'].'</p>';
      ?>
    </div>
  </div>
  <div class="row">
    <?php foreach ($secciones as $i => $seccion):?>

      <h5 class="separador"><?php echo $seccion['texto']?></h5>
      <div class="row">
          
        <?php foreach ($seccion['preguntas'] as $j => $pregunta){
          switch($pregunta['tipo']){
          case 'S':case 'N': //selección simple
            echo '
            <div class="nine columns">
              <p>'.$pregunta['texto'].'</p>
              <div class="row">';
                foreach ($pregunta['opciones'] as $k => $opcion){
                  echo '<div class="three mobile-one columns">'.$opcion['texto'].': ';
                  $cant = 0;
                  foreach ($pregunta['respuestas'] as $r){
                    if ($opcion['idOpcion'] == $r['Opcion']){
                      $cant = $r['Cantidad'];
                      break;
                    }
                  }
                  echo "<b>$cant</b></div>";
                }
              echo '
              </div>
            </div>
            <div class="three columns">
              <img src="'.site_url("encuestas/graficoPregunta/1/1/".$pregunta['idPregunta'].'/5/5').'" width="400" height="160" />
            </div>';
            break;
          case 'T': //testo simple
            echo '
            <div class="twelve columns">
              <p>'.$pregunta['texto'].'</p>
              <ul>';
                foreach ($pregunta['respuestas'] as $r){
                  echo '<li>'.$r['Texto'].'</li>';
                }
              echo '
              </ul>
            </div>';
            break;
          }//switch
        }//foreach?> 
      </div>   
    <?php endforeach //secciones?>
    <h5 class="separador"></h5>
    
  </div>
  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
</body>
</html>