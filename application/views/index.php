<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Sistema Encuestas</title>
  <style>
    header{
      background-color: #FAA732;
      padding:10px;
      height: 150px;
    }
    header>div{
      margin-top: 30px;
    }
  </style>
</head>

<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <header class="jumbotron subhead" id="overview">
      <div class="text-center">
        <h1>Sistema Encuestas</h1>
        <p class="lead">Para mejorar la calidad de la enseñanza</p>
      </div>
    </header>
    
    <div class="container">
      <div class="row-fluid">
        <div class="span4">
          <h2><a href="#">Sección 1</a></h2>
          <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
        </div>
        <div class="span4">
          <h2><a href="#">Sección 2</a></h2>
          <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
        </div>
        <div class="span4">
          <h2><a href="#">Sección 3</a></h2>
          <p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit. Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine spare ribs reprehenderit culpa. Boudin aliqua adipisicing rump corned beef.</p>
        </div>
      </div>
    </div>
    <div id="push"></div>
  </div>
  <?php include 'templates/footer.php'?>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/jquery.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-scrollspy.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-tab.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-tooltip.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-popover.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-button.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <?php if(isset($showLogin)) echo'<script>$("#LoginModal").modal()</script>'//abrir ventana de login?>
</body>
</html>