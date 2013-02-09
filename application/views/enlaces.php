<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Enlaces</title>
</head>
<body>
  <!-- Header -->
  <div class="row">
    <div class="twelve columns">
      <?php include 'elements/header.php'?>
    </div>
  </div>

  <div class="row">
    <!-- Nav Sidebar -->
    <div class="three columns">
      <!-- Panel de navegación -->
      <?php include 'elements/nav-sidebar.php'?>
    </div>  
    
    <!-- Main Section -->  
    <div id="Main" class="nine columns">
      <h3>Enlaces</h3>
      <div class="twelve columns">
        <ul>
          <li>
            <h5><a href="http://www.unt.edu.ar/">Universidad Nacional de Tucumán</a></h5>
            <p>Sitio Oficial de la Universidad Nacional de Tucumán</p>
          </li>
          <li>
            <h5><a href="http://www1.herrera.unt.edu.ar/faceyt/">Facultad de Ciencias Exactas y Tecnología</a></h5>
            <p>Sitio oficial de la Facultad de Ciencias Exactas y Tecnología de la Universidad Nacional de Tucumán</p>
          </li>
          <li>
            <h5><a href="http://www.herrera.unt.edu.ar/centroherrera">Centro Herrera</a></h5>
            <p>Servidor Web del Centro Centro Ing. Roberto Herrera de la Universidad Nacional de Tucumán</p>
          </li>
        </ul>
      </div>
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