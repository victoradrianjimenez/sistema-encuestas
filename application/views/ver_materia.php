<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Lista Materias</title>
  <style>
    .buscador{
      position: relative;
    }
    .buscador i{
      position: absolute; right: 0; top:0; margin:5px; font-size: 20px; color: #F2F2F2;
    }
    
    .button-group li a{
      margin-right: 5px;
    }
    
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
          <h3><?php echo $materia['Nombre'].' ('.$materia['Codigo'].')'?></h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Docente</th>
                <th>Cargo</th>
                <th title="D=Docente, J=Jefe de cátedra">Tipo acceso</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><?php echo $fila['Apellido'].', '.$fila['Nombre']?></td>
                  <td><?php echo $fila['Cargo']?></td>
                  <td><?php echo $fila['TipoAcceso']?></td>
                  <td>
                    <a class="Quitar" href="" title="Quitar asociación del docente con la materia" value="<?php echo $fila['IdPersona']?>">Quitar</a>
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
            <li><a class="button" data-reveal-id="modalModificar">Modificar materia</a></li>
            <li><a class="button" data-reveal-id="modalAsociar">Asociar docente</a></li>
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
      $link = site_url('materias/modificar');  
      include 'elements/form-editar-materia.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
    
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalAsociar" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      $link = site_url('materias/asociarDocente');  
      include 'elements/form-asociar-docente.php'; 
    ?>
    <a class="close-reveal-modal">&#215;</a>
  </div>
  
  <!-- ventana modal para desasociar materias a la carrera -->
  <div id="modalDesasociar" class="reveal-modal medium">
    <form action="<?php echo site_url('materias/desasociarDocente')?>" method="post">
      <h3>Desasociar docente</h3>
      <p>¿Desea continuar?</p>
      <input type="hidden" name="IdMateria" value="<?php echo $materia['IdMateria']?>" />
      <input type="hidden" name="IdDocente" value="" />
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
      IdDocente = $(this).attr('value');
      $('#modalDesasociar input[name="IdDocente"]').val(IdDocente);
      $("#modalDesasociar").reveal();
      return false;
    });
    
  </script>
</body>
</html>