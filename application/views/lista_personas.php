<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Personas</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>
  
  <!-- Main Section -->
  <div class="row">
    <!-- Main Section -->  
    <div id="Main" class="nine columns push-three">
      <div class="row">
        <div class="twelve columns">
          <h4>Personas</h4>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron carreras.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Último acceso</th>
                <th>Estado</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><?php echo $fila['Apellido'].(($fila['Nombre']!='')?', ':'').$fila['Nombre']?></td>
                  <td><?php echo $fila['Usuario']?></td>
                  <td><?php echo $fila['Email']?></td>
                  <td><?php echo $fila['UltimoAcceso']?></td>
                  <td><?php echo $fila['Estado']?></td>
                  <td>
                    <a href="<?php echo site_url("personas/ver/".$fila['IdPersona'])?>">Ver</a>
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
          <a class="button" data-reveal-id="modalNueva">Nuevo Usuario</a>
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
  
  <!-- ventana modal para agregar una persona -->
  <div id="modalNueva" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('personas/nueva');  
      $persona = array('IdPersona'=>0, 'Nombre'=>'', 'Apellido'=>'', 'Email'=>'', 'Usuario'=>'', 'Contraseña'=>'');
      include 'elements/form-editar-persona.php'; 
    ?>
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
      IdMateria = $(this).attr('value');
      $('#modalEliminar input[name="IdMateria"]').val(IdMateria);
      $("#modalEliminar").reveal();
      return false;
    });
  </script>
</body>
</html>