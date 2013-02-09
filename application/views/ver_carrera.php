<!DOCTYPE html>
<!-- Última revisión: 2012-02-01 2:42 a.m. -->

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ver carrera</title>
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
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div> 
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <div class="row">
        <div class="twelve columns">
          <h3><?php echo $carrera->nombre?> - Plan <?php echo $carrera->plan?></h3>
          <h5>Director de carrera: <?php echo $director->nombre.' '.$director->apellido?></h5>
          <h5><?php echo $departamento->nombre?></h5>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Materia</th>
                <th>Código</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="nombre"><?php echo $item->nombre?></a></td>
                  <td class="codigo"><?php echo $item->codigo?></td>
                  <td><a class="quitar" href="" title="Quitar asociación de la materia con la carrera" value="<?php echo $item->idMateria?>">Quitar</a></td>
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
            <li><a class="button" data-reveal-id="modalModificar">Modificar carrera...</a></li>
            <li><a class="button" data-reveal-id="modalAsociar">Asociar materia...</a></li>
          </ul>
        </div>
      </div>
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
      $titulo = 'Editar carrera';
      $link = site_url('carreras/modificar');  
      include 'elements/form-editar-carrera.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalAsociar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta  
      include 'elements/form-asociar-materia.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- ventana modal para desasociar materias a la carrera -->
  <div id="modalDesasociar" class="reveal-modal medium">
    <form action="<?php echo site_url('carreras/desasociarMateria')?>" method="post">
      <h3>Desasociar materia</h3>
      <h5 class="nombre"></h5>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
      <input type="hidden" name="idMateria" value="" />
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
    
    $('.quitar').click(function(){
      idMateria = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      codigo = $(this).parentsUntil('tr').parent().find('.codigo').text();
      //cargo el id de la materia en el formulario
      $('#modalDesasociar input[name="idMateria"]').val(idMateria);
      //pongo el nombre de la materia en el dialogo
      $("#modalDesasociar").find('.nombre').html(nombre+" ("+codigo+")");
      $("#modalDesasociar").reveal();
      return false;
    });
    
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('small.error').parentsUntil('.reveal-modal').parent().first().reveal();
  </script>
</body>
</html>