<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Listar Departamentos</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>
  
  
  
    <?php foreach ($secciones as $i => $seccion):?>
      <div class="row">
        <h3><?php echo $seccion['texto']?></h3>
      </div>    
      <?php foreach ($seccion['preguntas'] as $j => $pregunta):?>
      <div class="row">
                
          
          <?php  if ($pregunta['tipo'] == 'S'):?>
            <div class="eight columns">
              <p><?php echo $pregunta['texto']?></p>
              <div class="row">
                <?php foreach ($pregunta['opciones'] as $k => $opcion){
                  echo '<div class="three columns">'.$opcion['texto'];
                  foreach ($pregunta['respuestas'] as $res){
                    if ($opcion['idOpcion'] == $res['Opcion']){
                      echo  $res['Cantidad'];
                      break;
                    }
                  }
                  echo '</div>';
                }?>
              </div>
            </div>
            <div class="four columns">
              <img src="<?php echo "encuestas/graficoPregunta/1/1/".$pregunta['idPregunta']."/5/5" ?>" width="400" height="160" />
            </div>
          <?php endif ?>
                
      </div>      
      <?php endforeach?>
    <?php endforeach?>
    
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