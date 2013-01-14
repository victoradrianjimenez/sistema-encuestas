<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Encuesta</title>
  
  <style>
    
    div.item{
      margin: 5px 0;
    }
    input[type="submit"], input[type="button"]{
      width: 100%;
    }
    .titulo_seccion > h5{
      border-bottom: 4px solid #2795B6;
    }
    #titulo h2, #titulo h4, #titulo h5{
      text-align: center;
    }
    
  </style>  
</head>
<body>

  <div class="row">
    <!-- Main Section -->
    
    <div class="twelve columns">
      <div id="titulo">
        <h2><?php echo $formulario['titulo']?></h2>
        <h5><?php echo $carrera['nombre']?></h5>
        <h4>Asignatura: <?php echo $materia['nombre']?></h4>
      </div>
      <form action="" method="post">
        
        <?php foreach ($secciones as $i => $seccion): ?>
          
          <div class="row">
            

            <div class="twelve columns">
              <div class="titulo_seccion">
                <h5><?php echo $seccion['texto']?></h5>
                <h6><?php echo $seccion['descripcion']?></h6>
              </div>  
              <?php 
              $colCount = 0; //variable para verificar si me paso del maximo de 4 columnas
              foreach ($seccion['items'] as $j => $item){
                //obtener numero de columnas (de un total de 12) en base al tamaño
                switch ($item['tamaño']) {
                  case 1: $numcol = 'three'; $colCount += 1; break;
                  case 2: $numcol = 'six'; $colCount += 2; break;
                  case 3: $numcol = 'nine'; $colCount += 3; break;
                  default: $numcol = 'twelve'; $colCount += 4;
                }
                //verifico si se completaron las 4 columnas
                if ($colCount > 4){
                  $colCount-=4;
                  echo '<div class="row"></div>';
                }
                //genero un contenedor para la pregunta
                echo '<div class="item '.$numcol.' columns">';
                
                $tip = ($item['descripcion']!='')?'<span class="secondary round label has-tip" title="'.$item['descripcion'].'">!</span>':'';
                
                //para las preguntas con opciones
                if($item['tipo'] == 'S'){                  
                  //texto de la pregunta
                  printf(
                    '<div class="eight mobile-three columns">'.
                    ' <p>%s %s</p>'.
                    '</div>'. 
                    '<div class="four mobile-one columns"><select>'.
                    ' <option value="0">(No Contesta)</option>',
                    $item['texto'], $tip);
                  //opciones
                  foreach($item['opciones'] as $k => $opcion){
                    echo '<option value="'.$opcion['idOpcion'].'">'.$opcion['texto'].'</option>';
                  }
                  echo '</select></div>';
                }
                //multiple choice
                elseif($item['tipo'] == 'M'){
                  
                }
                //para las preguntas numericas
                elseif($item['tipo'] == 'N'){
                  printf (
                    '<div class="eight mobile-three columns">'.
                    '  <p>%s %s</p>'.
                    '</div>'.
                    '<div class="four mobile-one columns">'.
                    '  <input type="number" min="%s" max="%s" step="%s"/>'.
                    '</div>', 
                    $item['texto'], $tip, $item['limiteInferior'], $item['limiteSuperior'], $item['paso']);
                }
                //texto de una linea
                elseif($item['tipo'] == 'T'){
                  printf (
                    '<div class="twelve columns">'.
                    '<p>%s %s<input type="text"/></p>'.
                    '</div>', 
                    $item['texto'], $tip);
                }
                //texto multilinea
                elseif($item['tipo'] == 'X'){
                  printf (
                    '<div class="twelve columns">'.
                    '<p>%s %s<textarea></textarea></p>'.
                    '</div>', 
                    $item['texto'], $tip);
                }
                //cierro contenedor de pregunta
                echo '</div>';     
              }?>
            </div>
          </div>
        <?php endforeach?>
        
        <div class="four columns centered">
          <div class="six mobile-two columns">
            <input type="button" class="button" name="submit" value="Cancelar" />
          </div>
          <div class="six mobile-two columns">
            <input type="submit" class="button" name="submit" value="Enviar" />
          </div>
        </div>
      </form>
    </div>
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