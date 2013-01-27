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
          <h3>Docentes y Autoridades</h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron carreras.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>ID</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><?php echo $fila['IdPersona']?></td>
                  <td><a class="apellido" href="<?php echo site_url("personas/ver/".$fila['IdPersona'])?>"><?php echo $fila['Apellido']?></a></td>
                  <td class="nombre"><?php echo $fila['Nombre']?></td>
                  <td>
                    <a class="eliminar" href="" value="<?php echo $fila['IdPersona']?>">Eliminar</a>
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
          <a class="button" data-reveal-id="modalNueva">Nueva Persona</a>
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
      $persona = array('IdPersona'=>0, 'Nombre'=>'', 'Apellido'=>'');
      include 'elements/form-editar-persona.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- ventana modal para eliminar materias -->
  <div id="modalEliminar" class="reveal-modal medium">
    <form action="<?php echo site_url('personas/eliminar')?>" method="post">
      <input type="hidden" name="IdPersona" value="" />
      <h3>Eliminar persona</h3>
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
      IdPersona = $(this).attr('value');
      Apellido = $(this).parentsUntil('tr').parent().find('.apellido').text();
      Nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id de la persona en el formulario
      $('#modalEliminar input[name="IdPersona"]').val(IdPersona);
      //pongo el nombre de la persona en el dialogo
      $("#modalEliminar").find('.nombre').html(Nombre+' '+Apellido);
      $("#modalEliminar").reveal();
      return false;
    });
  </script>
</body>
</html>