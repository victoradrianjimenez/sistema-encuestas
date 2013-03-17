<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar Informe por Facultad</title>
  <link href="<?php echo base_url('css/datepicker.css')?>" rel="stylesheet">
  <style>
    .form-horizontal .controls {margin-left: 100px}
    .form-horizontal .control-label {width: 80px; float: left}
    #contenedor{padding-top:9px}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Informes Históricos</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 4;
            include 'templates/submenu-historicos.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Solicitar informe por facultad</h4>
          <form class="form-horizontal" action="<?php echo site_url('encuestas/informeFacultad')?>" method="post">
            <div class="control-group">
              <label class="control-label" for="buscarPregunta">Pregunta:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idMateria" required/>
                <?php echo form_error('idPregunta')?>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span6 control-group">
                <label class="control-label" for="dpd1">Fecha Inicio:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" value="" id="dpd1" data-date-viewmode="years">
                </div>
              </div>
              <div class="span6 control-group">
                <label class="control-label" for="dpd1">Fecha Fin:</label>
                <div class="controls">
                  <input class="input-block-level" type="text" class="span2" value="" id="dpd2" data-date-viewmode="years">
                </div>
              </div>
            </div>
            <div class="controls btn-group">
              <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
            </div>
          </form>
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
  <script src="<?php echo base_url('js/bootstrap-datepicker.js')?>"></script>
  <script>
    //selectores de fecha
    var checkin = $('#dpd1').datepicker({
      format: 'dd/mm/yyyy'
    }).on('changeDate', function(ev) {
      if (ev.date.valueOf() > checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 1);
      }
      else var newDate = checkout.date;
      checkout.setValue(newDate);
      checkin.hide();
      $('#dpd2')[0].focus();
    }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
      format: 'dd/mm/yyyy',
      onRender: function(date) {return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';}
    }).on('changeDate', function(ev) {
      checkout.hide();
    }).data('datepicker');

    //ocultar mensaje de error al escribir
    $('input[type="text"]').keyup(function(){
      $(this).siblings('span.label').hide('fast');
    });
  </script>
</body>
</html>