<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ver encuesta</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <?php include 'templates/menu-nav.php'?>
  <div id="wrapper" class="container">
    <div class="row">
      <!-- Titulo -->
      <div class="span12">
        <h3>Gestión de Encuestas</h3>
        <p>---Descripción---</p>
      </div>
    </div>
    
    <div class="row">
      <!-- SideBar -->
      <div class="span3" id="menu">
        <h4>Navegación</h4>
        <ul class="nav nav-pills nav-stacked">      
          <li class="active"><a href="<?php echo site_url("encuestas")?>">Encuestas realizadas</a></li>
          <li><a href="<?php echo site_url("claves")?>">Claves de acceso</a></li>
        </ul>
      </div>
      
      <!-- Main -->
      <div class="span9">
        <h4>Encuesta</h4>
        <h5>Período: <?php echo $encuesta->año.' / '.$encuesta->cuatrimestre?></h5>
        <h5>Fecha de inicio de la toma de encuestas: <?php echo $encuesta->fechaInicio?></h5>
        <h5>Fecha de cierre de las encuestas: <?php echo $encuesta->fechaFin?></h5>
        
        <!-- Botones -->
        <div>
          <button class="btn btn-primary" href="#modalGenerarClaves" role="button" data-toggle="modal">Generar claves de acceso</button>
          <button class="btn btn-primary" href="#modalFinalizar" role="button" data-toggle="modal">Cerrar periodo de encuesta</button>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para generar claves de acceso -->
  <div id="modalGenerarClaves" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  </div>

  <!-- ventana modal para finalizar un periodo de encuestas -->
  <div id="modalFinalizar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Finalizar período de encuesta</h3>
    </div>
    <form action="<?php echo site_url('encuestas/finalizar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
        <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
        <h5><?php echo $encuesta->año.' / '.$encuesta->cuatrimestre?></h5>
        <p>¿Desea continuar?</p>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script>
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>