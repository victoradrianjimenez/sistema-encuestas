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
<?php
  function ncolumnas($tamaño){
    switch ($tamaño) {
      case 1: $numcol = 'three'; break;
      case 2: $numcol = 'six'; break;
      case 3: $numcol = 'nine'; break;
      default: $numcol = 'twelve';
    }
    return $numcol;
  }
?>
<body>

  <div class="row">
    <!-- Main Section -->
    
    <div class="twelve columns">
      <div id="titulo">
        <h2><?php echo $formulario['Titulo']?></h2>
        <h5><?php echo $carrera['Nombre']?></h5>
        <h4>Asignatura: <?php echo $materia['Nombre']?></h4>
      </div>
      <form id="formulario" action="<?php echo site_url('claves/encuesta')?>" method="post">
        <input type="hidden" name="Clave" value="<?php echo $clave['Clave']?>" />        
        <?php foreach ($secciones as $i => $seccion): ?>
          <div class="row">
            <div class="twelve columns">
              <div class="titulo_seccion">
                <h5><?php echo $seccion['Texto']?></h5>
                <h6><?php echo $seccion['Descripcion']?></h6>
              </div>  
              <?php 
              $colCount = 0; //variable para verificar si me paso del maximo de 4 columnas
              
              //SUBSECCIONES
              
              foreach ($seccion['Subsecciones'] as $j => $subseccion){

                printf('
                  <h3>%s %s</h3>',
                  $subseccion['Nombre'], $subseccion['Apellido']);
                
                //ITEMS
                
                foreach ($subseccion['Items'] as $k => $item){
                  
                  //verifico si se llego a completar una fila de preguntas 
                  $colCount += $item['Tamaño'];
                  if ($colCount > 4){ //verifico si se completaron las 4 columnas
                    $colCount-=4;
                    echo '<div class="row"></div>';
                  }
                  
                  //genero un contenedor para la pregunta
                  echo '<div class="item '.ncolumnas($item['Tamaño']).' columns">';
                    
                    //genero el html de la ayuda contextual
                    $tip = ($item['Descripcion']!='')?'<span class="secondary round label has-tip" title="'.$item['Descripcion'].'">!</span>':'';
                    
                    //para las preguntas con opciones
                    if($item['Tipo'] == 'S'){
                      $opciones = '';
                      foreach($item['Opciones'] as $k => $opcion){
                        $opciones .= '<option value="'.$opcion['IdOpcion'].'">'.$opcion['Texto'].'</option>';
                      }
                      printf('
                        <div class="eight mobile-three columns">
                          <p>%s %s</p>
                        </div>
                        <div class="four mobile-one columns">
                          <select name="IdPregunta_%d_%d">
                            <option value="">(No Contesta)</option>%s
                          </select>
                        </div>',
                        $item['Texto'], $tip, $item['IdPregunta'], $subseccion['IdDocente'], $opciones);
                    }
                    //multiple choice
                    elseif($item['Tipo'] == 'M'){
                      
                    }
                    //para las preguntas numericas
                    elseif($item['Tipo'] == 'N'){
                      printf ('
                        <div class="eight mobile-three columns">
                          <p>%s %s</p>
                        </div>
                        <div class="four mobile-one columns">
                          <input type="number" name="IdPregunta_%d_%d" min="%f" max="%f" step="%f"/>
                        </div>',
                        $item['Texto'], $tip, $item['IdPregunta'], $subseccion['IdDocente'], 
                        $item['LimiteInferior'], $item['LimiteSuperior'], $item['Paso']);
                    }
                    //texto de una linea
                    elseif($item['Tipo'] == 'T'){
                      printf ('
                        <div class="twelve columns">
                          <p>%s %s<input type="text" name="IdPregunta_%d_%d"/></p>
                        </div>', 
                        $item['Texto'], $tip, $item['IdPregunta'], $subseccion['IdDocente']);
                    }
                    //texto multilinea
                    elseif($item['Tipo'] == 'X'){
                      printf ('
                        <div class="twelve columns">
                          <p>%s %s<textarea name="IdPregunta_%d_%d"></textarea></p>
                        </div>',
                        $item['Texto'], $tip, $item['IdPregunta'], $subseccion['IdDocente']);
                    }
                  //cierro contenedor de pregunta
                  echo '</div>';
                  
                }//foreach items
              }//foreach subsecciones
              ?>
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