<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista preguntas</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <div class="row">
        <div class="twelve columns">
          <h3>Preguntas</h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron preguntas.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Texto</th>
                <th>Creacion</th>
                <th>Tipo</th>
                <th>Obligatoria</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><a class="texto" href="<?php echo site_url('preguntas/ver/'.$fila['IdPregunta'])?>"/><?php echo $fila['Texto']?></a></td>
                  <td class="creacion"><?php echo $fila['Creacion']?></td>
                  <td class="tipo"><?php echo $fila['Tipo']?></td>
                  <td class="obligatoria"><?php echo $fila['Obligatoria']?></td>
                  <td>
                    <a class="eliminar" href="" value="<?php echo $fila['IdPregunta']?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="six mobile-two columns pull-one-mobile">
          <a class="button" href="<?php echo site_url('preguntas/editar')?>">Nueva Pregunta</a>
        </div>          
      </div>
    </div>

    <!-- Nav Sidebar -->
    <div class="three columns pull-nine">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>    
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
    
  <!-- ventana modal para eliminar preguntas -->
  <div id="modalEliminar" class="reveal-modal medium">
    <form action="<?php echo site_url('preguntas/eliminar')?>" method="post">
      <input type="hidden" name="IdPregunta" value="" />
      <h3>Eliminar pregunta</h3>
      <h5 class="texto"></h5>
      <p>¿Desea continuar?</p>
      <div class="row">         
        <div class="ten columns centered">
          <div class="six mobile-one columns push-one-mobile">
            <input class="button cancelar" type="button" value="Cancelar"/>
          </div>
          <div class="six mobile-one columns pull-one-mobile ">
            <input class="button" type="submit" name="submit" value="Aceptar" />
          </div>
        </div>
      </div>
    </form>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
  
  <script>
    $('.cancelar').click(function(){
      $('.cancelar').trigger('reveal:close'); //cerrar ventana
    });

    $('.eliminar').click(function(){
      IdPregunta = $(this).attr('value');
      Texto = $(this).parentsUntil('tr').parent().find('.texto').text();
      //cargo el id de la pregunta en el formulario
      $('#modalEliminar input[name="IdPregunta"]').val(IdPregunta);
      //pongo el texto de la pregunta en el dialogo
      $("#modalEliminar").find('.texto').html(Texto);
      $("#modalEliminar").reveal();
      return false;
    });
  </script>
</body>
</html>