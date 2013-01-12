<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />
  
  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Listar Departamentos</title>
  
  <link rel="stylesheet" href="<?php echo base_url()?>css/foundation.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>css/app.css">

  <script src="<?php echo base_url()?>js/jquery.js"></script>
  <script src="<?php echo base_url()?>js/foundation/modernizr.foundation.js"></script>
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
          
          <h4>Departamentos</h4>
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
                  <td><a href="<?php echo site_url("carreras/listar/".$fila['IdDepartamento'])?>"/><?php echo $fila['Nombre']?></a></td>
                  <td><?php echo $fila['JefeDepartamento']?></td>
                  <td>
                    <a href="<?php echo site_url("departamentos/modificar/".$fila['IdDepartamento'])?>">Modificar</a> /
                    <a href="<?php echo site_url("departamentos/eliminar/".$fila['IdDepartamento'])?>">Eliminar</a>
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
          <a class="button" href="<?php echo base_url()."index.php/departamentos/nuevo"?>">Nuevo Departamento</a>
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
  
  <!-- Included JS Files (Compressed) -->
  <script src="<?php echo base_url()?>js/foundation/foundation.min.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php echo base_url()?>js/foundation/app.js"></script>
</body>
</html>