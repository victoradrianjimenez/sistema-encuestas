<!-- Última revisión: 2012-02-01 2:20 a.m. -->

<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Carreras</title>
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
          <h3>Carreras</h3>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron carreras.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Nombre</th>
                <th>Plan</th>
                <th>Director</th>
                <th>Departamento</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="nombre" href="<?php echo site_url("carreras/ver/".$item['carrera']->idCarrera)?>"><?php echo $item['carrera']->nombre?></a></td>
                  <td class="plan"><?php echo $item['carrera']->plan?></td>
                  <td class="director"><?php echo $item['director']->nombre.' '.$item['director']->apellido?></td>
                  <td class="departamento"><?php echo $item['departamento']->nombre?></td>
                  <td><a class="eliminar" href="" value="<?php echo $item['carrera']->idCarrera?>">Eliminar</a></td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="six mobile-two columns pull-one-mobile">
          <a class="button" data-reveal-id="modalAgregar">Agregar carrera...</a>
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
  

  <!-- ventana modal para agregar una carrera -->
  <div id="modalAgregar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('carreras/nueva');
      $titulo = 'Crear nueva carrera';
      include 'elements/form-editar-carrera.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- ventana modal para eliminar una carrera -->
  <div id="modalEliminar" class="reveal-modal medium">
    <form action="<?php echo site_url('carreras/eliminar')?>" method="post">
      <h3>Eliminar carrera</h3>
      <h5 class="nombre"></h5>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="idCarrera" value="" />
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
      $(this).trigger('reveal:close'); //cerrar ventana
    });
    
    $('.eliminar').click(function(){
      idCarrera = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      plan = $(this).parentsUntil('tr').parent().find('.plan').text();
      $('#modalEliminar input[name="idCarrera"]').val(idCarrera);
      //pongo el nombre del departamento en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre + ' - Plan: '+plan);
      $("#modalEliminar").reveal();
      return false;
    });
    
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('small.error').parentsUntil('.reveal-modal').parent().first().reveal();
  </script>
</body>
</html>