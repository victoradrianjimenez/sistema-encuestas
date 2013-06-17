<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Claves de Acceso - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    .container .form-horizontal .controls {margin-left: 120px}
    .container .form-horizontal .control-label {width: 100px; float: left}
    #verClaves{display:inline;}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Encuestas</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de las encuestas y claves de acceso.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 2;
            include 'templates/submenu-encuestas.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          <h4>Claves de acceso</h4>
          <p>Carrera: <?php echo "$carrera->nombre (plan $carrera->plan)"?></p>
          <p>Asignatura: <?php echo "$materia->nombre ($materia->codigo)"?></p>
          <p>Cantidad de claves generadas: <?php echo $claves['generadas']?></p>
          <p>Cantidad de claves utilizadas: <?php echo $claves['utilizadas']?></p>
          
          <button class="generar btn btn-primary">Generar Claves de Acceso</button>
          <form id="verClaves" class="form-horizontal" action="<?php echo site_url('claves/listar')?>" method="post">
            <input type="hidden" name="idEncuesta" value="<?php echo $encuesta->idEncuesta?>" />
            <input type="hidden" name="idFormulario" value="<?php echo $encuesta->idFormulario?>" />
            <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
            <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
            <input class="btn btn-primary" type="submit" name="submit" value="Ver Claves generadas " />
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalGenerar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Generar Claves de Acceso</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('claves/generar')?>" method="post">
      <div class="modal-body">
        <h5></h5>
        <div class="control-group"> 
          <input type="hidden" name="idMateria" value="" required/>
          <input type="hidden" name="idCarrera" value="" required/>
          <input type="hidden" name="idFormulario" value="" required/>
          <input type="hidden" name="idEncuesta" value="" required/>
          <label class="control-label" for="campoCantidad">Cantidad de claves: </label>
          <div class="controls">
            <input class="input-xlarge" id="campoCantidad" type="number" name="cantidad" value="30" />
            <?php echo form_error('cantidad')?>
          </div>
          <div class="controls">
            <label class="checkbox"><input type="checkbox" name="guardarCantidad" value="1" checked />Guardar valor para futuras consultas</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.min.js')?>"></script>
  <script>
    $('.generar').click(function(){
      idMateria = $('#contenedor input[name="idMateria"]').val();
      idCarrera = $('#contenedor input[name="idCarrera"]').val();
      idFormulario = $('#contenedor input[name="idFormulario"]').val();
      idEncuesta = $('#contenedor input[name="idEncuesta"]').val();
      if (idMateria != '' && idCarrera != '' && idFormulario!=''&&idEncuesta!=''){
        //cargo el id de la materia en el formulario
        $('#modalGenerar input[name="idMateria"]').val(idMateria);
        $('#modalGenerar input[name="idCarrera"]').val(idCarrera);
        $('#modalGenerar input[name="idFormulario"]').val(idFormulario);
        $('#modalGenerar input[name="idEncuesta"]').val(idEncuesta);
        $.ajax({
          type: "POST", 
          url: "<?php echo site_url('materias/cantidadClavesAJAX')?>", 
          data: {idMateria: idMateria, idCarrera: idCarrera}
        }).done(function(msg){
          $('#modalGenerar input[name="cantidad"]').attr('value', msg.replace(/^\s+/g,'').replace(/\s+$/g,'')); //trim
        });
        $("#modalGenerar").modal();
      }
      return false;
    });
  </script>
</body>
</html>