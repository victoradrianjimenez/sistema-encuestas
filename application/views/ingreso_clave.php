<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <?php include 'elements/head.php'?> 
  <title>Ingresar Clave de Acceso</title>
  
  <style>
    input[name="submit"]{width:100%;}
    input[name="clave"]{text-align:center;}
    form h6{text-align:center;}
  </style>  
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
    <div class="six columns centered">  
      <form action="<?php echo site_url('claves/ingresar')?>" method="post">
        <fieldset>
          <div class="twelve columns">
            <h6>Ingrese la Clave de Acceso que figura en la tarjeta entregada por el representante de la Comisión Académica</h6>
            <input class="<?php echo ($mensaje!='')?'error':''?>" name="clave" type="text" id="clave" maxlength="16" placeholder="Ingrese su clave de acceso" value="<?php echo $clave?>"/>
            <small class="<?php echo ($mensaje!='')?'error':'hide'?>"><?php echo $mensaje?></small>           
          </div>
          <div class="four mobile-two columns centered pull-one-mobile pull-four">
            <input class="button" name="submit" type="submit" id="aceptar" value="Aceptar" />
          </div>
        </fieldset>
      </form>
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
  
  <script type="text/javascript">
    //esconder los mensajes de error al establecer foco
    $('input[name="clave"]').focus(function(){
      $('small.error').hide();
      $('.error').removeClass('error');
    });
  </script>
</body>
</html>