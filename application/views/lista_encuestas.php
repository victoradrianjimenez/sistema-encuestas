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
          <h3>Encuestas</h3>
          <?php if(count($tabla)== 0):?>
            <p>No se encontraron encuestas.</p>
          <?php else:?>
            <table class="twelve">
              <thead>
                <th>Año / Periodo</th>
                <th>Fecha inicio</th>
                <th>Fecha cierre</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($tabla as $fila): ?>  
                <tr>
                  <td><a href="<?php echo site_url("encuesta/ver/".$fila['IdEncuesta'].'/'.$fila['IdFormulario'])?>">
                    <?php echo $fila['Año'].' / '.$fila['Cuatrimestre']?>
                  </a></td>
                  <td><?php echo $fila['FechaInicio']?></td>
                  <td><?php echo $fila['FechaFin']?></td>
                  <td><?php echo ($fila['FechaFin']=='')?'<a>Cerrar período</a>':''?></td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
        </div>
      </div>
      <div class="row">
        <div class="three mobile-one columns">
          <a class="button" data-reveal-id="modalNueva">Nueva Encuesta</a>
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
  
  
  <!-- ventana modal para agregar una materia -->
  <div id="modalNueva" class="reveal-modal medium">
    <?php
      //a donde mandar los datos editados para darse de alta
      //$link = site_url('encuestas/nueva');  
      //include 'elements/form-editar-encuesta.php'; 
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
  </script>
</body>
</html>