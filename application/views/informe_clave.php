<!DOCTYPE html>
<!-- Última revisión: 2012-02-03 4:33 p.m. -->

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
    .titulo_seccion > h5{
      border-bottom: 4px solid #2795B6;
    }
    #titulo h2, #titulo h4, #titulo h5{
      text-align: center;
    }
    a.button{
      width: 100%;
    }
  </style>  
</head>
<body>

  <div class="row">
    <!-- Main Section -->
    
    <div class="twelve columns">
      <div id="titulo">
        <h2><?php echo $formulario->titulo?></h2>
        <h5><?php echo $carrera->nombre?></h5>
        <h4>Asignatura: <?php echo $materia->nombre?></h4>
      </div>
      <form id="formulario" action="<?php echo site_url('claves/responder')?>" method="post">
        <input type="hidden" name="clave" value="<?php echo $clave->clave?>" />        
        <?php foreach ($secciones as $itemSeccion): ?>
          <div class="row">
            <div class="twelve columns">
              <div class="titulo_seccion">
                <h5><?php echo $itemSeccion['seccion']->texto?></h5>
                <h6><?php echo $itemSeccion['seccion']->descripcion?></h6>
              </div>  
              <?php 
              $colCount = 0; //variable para verificar si me paso del maximo de 4 columnas
              
              //SUBSECCIONES
              
              foreach ($itemSeccion['subsecciones'] as $subseccion){
                $docente = $subseccion['docente'];
                $ncol = 0;
                printf('
                <div class="row">
                  <div class="twelve columns">
                    <h3>%s %s</h3>
                  </div>', $docente->nombre, $docente->apellido);
                
                //ITEMS

                foreach ($subseccion['items'] as $i){
                  $item = &$i['item'];
                  $respuestas = &$i['respuestas'];
                  
                  if ($ncol>=12){
                    $ncol=12-$ncol;
                    echo '<div class="row"></div>';
                  }
                  
                  //genero el html de la ayuda contextual
                  $tip = ($item->descripcion!='')?'<span class="secondary round label has-tip" title="'.$item->descripcion.'">!</span>':'';
                  
                  //para las preguntas con opciones
                  if($item->tipo == 'S'){
                    printf('
                    <div class="item six columns">
                      <div class="twelve columns">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>
                    </div>', $item->texto, $tip, $respuestas[0]['texto']);
                    $ncol += 6;
                  }
                  //multiple choice
                  elseif($item->tipo == 'M'){
                    $ncol += 6;
                  }
                  //para las preguntas numericas
                  elseif($item->tipo == 'N'){
                    printf ('
                    <div class="item six columns">
                      <div class="twelve columns">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>
                    </div>', $item->texto, $tip, $respuestas[0]['texto']);
                    $ncol += 6; 
                  }
                  //texto de una linea
                  elseif($i['item']->tipo == 'T'){
                    printf ('
                    <div class="item six columns">
                      <div class="twelve columns">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>
                    </div>', $item->texto, $tip, $respuestas[0]['texto']);
                    $ncol += 12;
                  }
                  //texto multilinea
                  elseif($item->tipo == 'X'){
                    printf ('
                    <div class="item six columns">
                      <div class="twelve columns">
                        <p>%s %s</p>
                        <p><b>%s</b></p>
                      </div>
                    </div>', $item->texto, $tip, $respuestas[0]['texto']);
                    $ncol += 12;
                  }
                }//foreach items
                echo '</div>';
              }//foreach subsecciones
              ?>
            </div>
          </div>
        <?php endforeach?>
        
        <div class="three mobile-two columns centered push-one-mobile">
          <a class="button" data-reveal-id="modalConfirmar">Enviar</a>
        </div>
        
        <!-- ventana modal para eliminar materias -->
        <div id="modalConfirmar" class="reveal-modal small">
          <h3>Confirmación</h3>
          <p>¿Desea continuar?</p>
          <div class="row">         
            <div class="ten columns centered">
              <div class="six mobile-one columns push-one-mobile">
                <a class="button cancelar">Cancelar</a>
              </div>
              <div class="six mobile-one columns pull-one-mobile ">
                <input type="submit" class="button" name="submit" value="Enviar" />
              </div>
            </div>
          </div>
          <a class="close-reveal-modal">&#215;</a>
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
  <script>
    $('.cancelar').click(function(){
      $(this).trigger('reveal:close'); //cerrar ventana
    });
  </script>
</body>
</html>