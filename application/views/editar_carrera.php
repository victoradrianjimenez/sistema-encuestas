<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar carrera</title>
  <style>
    .button-group li a { margin-right: 5px; }
  </style>
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
          <h3><?php echo $carrera['Nombre']?> - Plan <?php echo $carrera['Plan'] ?></h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Materia</th> <th>Codigo</th> <th>Alumnos</th> <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><?php echo $fila['Nombre']?></a></td>
                  <td><?php echo $fila['Codigo']?></td>
                  <td><?php echo $fila['Alumnos']?></td>
                  <td>
                    <a class="Quitar" href="" title="Quitar asociación de la materia con la carrera" value="<?php echo $fila['IdMateria']?>">Quitar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="twelve columns">
          <ul class="button-group">
            <li><a class="button" data-reveal-id="modalModificar">Modificar carrera</a></li>
            <li><a class="button" data-reveal-id="modalAsociar">Asociar materia</a></li>
          </ul>
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
  
  <!-- ventana modal para editar datos de la carrera -->
  <div id="modalModificar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('carreras/modificar');  
      include 'elements/form-editar-carrera.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalAsociar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('carreras/asociarMateria');  
      include 'elements/form-asociar-materia.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- ventana modal para desasociar materias a la carrera -->
  <div id="modalDesasociar" class="reveal-modal medium">
    <form action="<?php echo site_url('carreras/desasociarMateria')?>" method="post">
      <h3>Desasociar materia</h3>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="IdCarrera" value="<?php echo $carrera['IdCarrera']?>" />
      <input type="hidden" name="IdMateria" value="" />
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
    
    $('.Quitar').click(function(){
      IdMateria = $(this).attr('value');
      $('#modalDesasociar input[name="IdMateria"]').val(IdMateria);
      $("#modalDesasociar").reveal();
      return false;
    });
    
  </script>
</body>
</html>