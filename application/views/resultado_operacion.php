<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ver carrera</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <br />
      <div class="modals span8 offset2" style="position:static">
        <div class="modal-header">
          <h3>Resultado de la operación:</h3>
        </div>
        <div class="modal-body">
          <p><?php echo $mensaje ?></p><br />
          <div>
            <a id="botonVolver" href="#" class="btn">Volver</a>
            <a href="<?php echo $link ?>" class="btn btn-primary">Aceptar</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script language="JavaScript">
    //funcionalidad del boton volver atras
    $('#botonVolver').click(function(){
      window.history.back();
      return false;
    });
  </script>  
</body>
</html>