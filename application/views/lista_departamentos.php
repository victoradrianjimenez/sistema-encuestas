<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Departamentos</title>
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
          <h3>Departamentos</h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron departamentos.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Nombre</th>
                <th>Jefe de Departamento</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><a class="nombre" href="<?php echo site_url('departamentos/ver/'.$fila['IdDepartamento'])?>"/><?php echo $fila['Nombre']?></a></td>
                  <td><a class="jefeDepartamento" href="<?php echo site_url('personas/ver/'.$fila['JefeDepartamento']['IdPersona'])?>"/><?php echo $fila['JefeDepartamento']['Nombre'].' '.$fila['JefeDepartamento']['Apellido']?></a></td>
                  <td>
                    <a class="eliminar" href="" value="<?php echo $fila['IdDepartamento']?>">Eliminar</a>
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
          <a class="button" data-reveal-id="modalNuevo">Nuevo Departamento</a>
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

  <!-- ventana modal para editar datos del departamento -->
  <div id="modalNuevo" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('departamentos/nuevo');  
      $departamento = array('IdDepartamento'=>0, 'IdJefeDepartamento'=>0, 'Nombre'=>'');
      include 'elements/form-editar-departamento.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- ventana modal para eliminar materias -->
  <div id="modalEliminar" class="reveal-modal medium">
    <form action="<?php echo site_url('departamentos/eliminar')?>" method="post">
      <input type="hidden" name="IdDepartamento" value="" />
      <h3>Eliminar departamento</h3>
      <h5 class="nombre"></h5>
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
      IdDepartamento = $(this).attr('value');
      Nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id del departamento en el formulario
      $('#modalEliminar input[name="IdDepartamento"]').val(IdDepartamento);
      //pongo el nombre del departamento en el dialogo
      $("#modalEliminar").find('.nombre').html(Nombre);
      $("#modalEliminar").reveal();
      return false;
    });
  </script>
</body>
</html>