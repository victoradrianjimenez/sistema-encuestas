<!DOCTYPE html>
<!-- Última revisión: 2012-01-31 10:23 a.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Devoluciones</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>   
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <div class="row">
        <div class="twelve columns">
          <h3>Devoluciones</h3>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron devoluciones.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Fecha</th>
                <th>Materia</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="fecha" href="<?php echo site_url('devoluciones/ver/'.$item['devolucion']->idDevolucion)?>"/><?php echo $item['devolucion']->fecha?></a></td>
                  <td><a class="materia" /><?php echo $item['materia']->nombre?></a></td>
                  <td><a class="eliminar" href="#" value="<?php echo $item['devoluciones']->idDevolucion?>">Eliminar</a></td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="six mobile-two columns pull-one-mobile">
          <a class="button" href="<?php echo site_url('devoluciones/editar')?>">Agregar devolucion...</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->    
  <div class="row">    
    <?php include 'elements/footer.php'?>
  </div>
  
  <!-- ventana modal para eliminar materias -->
  <div id="modalEliminar" class="reveal-modal medium">
    <form action="<?php echo site_url('devoluciones/eliminar')?>" method="post">
      <input type="hidden" name="idDevolucion" value="" />
      <h3>Eliminar devolución</h3>
      <h5 class="decha"></h5>
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
      $(this).trigger('reveal:close'); //cerrar ventana
    });

    $('.eliminar').click(function(){
      idDevolucion = $(this).attr('value');
      fecha = $(this).parentsUntil('tr').parent().find('.fecha').text();
      //cargo el id de la devolucion en el formulario
      $('#modalEliminar input[name="idDepartamento"]').val(idDevolucion);
      //pongo la fecha de la devolucion en el dialogo
      $("#modalEliminar").find('.nombre').html(fecha);
      $("#modalEliminar").reveal();
      return false;
    });
    
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('small.error').parentsUntil('.reveal-modal').parent().first().reveal();
  </script>
</body>
</html>