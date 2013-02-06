<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Editar Devolución</title>
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
    <!-- Main Feed -->
    <!-- This has been source ordered to come first in the markup (and on small devices) but to be to the right of the nav on larger screens -->
    <div class="nine columns push-three">
      <form action="<?php echo site_url('devoluciones/nueva')?>" method="post">
        <fieldset>
          <legend>Evaluación de la cátedra sobre los resultados de las encuestas</legend>
          <div class="twelve columns">
            <label for="campoFortalezas">Identifique las fortalezas del curso: </label>
            <textarea id="campoFortalezas"  name="fortalezas" rows="4"></textarea>
            <?php echo form_error('fortalezas')?>
            
            <label for="campoDebilidades">Identifique las debilidades del curso: </label>
            <textarea id="campoDebilidades" name="debilidades" rows="4"></textarea>
            <?php echo form_error('debilidades')?>
            
            <label for="campoAlumnos">Reflexiones sobre las opiniones de los alumnos: </label>
            <textarea id="campoAlumnos" name="alumnos" rows="4"></textarea>
            <?php echo form_error('alumnos')?>
            
            <label for="campoDocentes">Reflexiones sobre el desempeño de los docentes: </label>
            <textarea id="campoDocentes" name="docentes" rows="4"></textarea>
            <?php echo form_error('docentes')?>
            
            <label for="campoMejoras">Plan de mejoras propuesto: </label>
            <textarea id="campoMejoras" name="mejoras" rows="4"></textarea>
            <?php echo form_error('mejoras')?>
          </div>
          <div class="row">
            <div class="two columns centered">
              <input id="Aceptar" class="button" type="submit" name="submit" value="Aceptar" />
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
</body>
</html>