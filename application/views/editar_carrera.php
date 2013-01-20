<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar Carrera</title>
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
    <!-- Main -->
    <div id="Main" class="nine columns push-three">
      <form class="custom" action="<?php echo $link?>" method="post"> 
        <fieldset>
          <legend>Carrera</legend>
          <input type="hidden" name="IdCarrera" value="<?php echo $carrera['IdCarrera']?>"/>
          <div class="twelve columns">
            <label for="campoIdDepartamento">Departamento: </label>
            <?php 
              $selOpt = 0; //posicion del item seleccionado
              $options = '';
              foreach ($departamentos as $i => $departamento) {
                $options = $options.'<option value="'.$departamento['IdDepartamento'].'">'.$departamento['Nombre'].'</option>';
                if ($departamento['IdDepartamento'] == $carrera['IdDepartamento']){
                  $selOpt = $i;
                }
              }
              echo '<select id="campoIdDepartamento" name="IdDepartamento" value="'.$selOpt.'">'.$options.'</select>';
            ?>
          </div>
          <div class="nine mobile-three columns">
            <label for="campoNombre">Nombre: </label>
            <input id="campoNombre" type="text" name="Nombre" value="<?php echo $carrera['Nombre']?>"/>
            <?php echo form_error('nombre'); ?>
          </div>
          <div class="three mobile-one columns">
            <label for="campoPlan">Plan: </label>
            <input id="campoPlan" type="number" min="1900" max="2100" name="Plan" value="<?php echo $carrera['Plan']?>"/>
          </div>
          <div class="row">         
            <div class="six columns centered">
              <div class="six mobile-one columns push-one-mobile">
                <button id="botonVolver" class="button">Cancelar</button>
              </div>
              <div class="six mobile-one columns pull-one-mobile ">
                <input class="button" type="submit" name="submit" value="Aceptar" />
              </div>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    
    <!-- Nav Sidebar -->
    <!-- This is source ordered to be pulled to the left on larger screens -->
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
  
  <script language="JavaScript">
    //ocultar mensajes de errores de formulario de CodeIgniter, cuando se presiona una tecla en el input
    $('input[type="text"]').keypress(function(){
      $(this).next('small.error').hide('fast', function(){$(this).remove();});
    });
    
    //funcionalidad del boton volver atras
    $('#botonVolver').click(function(){
      window.history.back();
      return false;
    });
  </script>
</body>
</html>