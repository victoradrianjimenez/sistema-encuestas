<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Claves de Acceso - <?php echo NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <style>
    .container .form-horizontal .controls {margin-left: 120px}
    .container .form-horizontal .control-label {width: 100px; float: left}
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
          <?php include 'templates/descripcion-encuestas.php'?>
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
          <h4>Datos de la Encuesta</h4>
          <form class="form-horizontal" action="<?php echo site_url('claves/claves_acceso')?>" method="post">
    
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo ($carrera->nombre)?$carrera->nombre.' / '.$carrera->plan:''?>">
                <input type="hidden" name="idCarrera" required value="<?php echo $carrera->idCarrera?>"/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarMateria">Materia:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo ($materia->nombre)?$materia->nombre.' / '.$materia->codigo:''?>">
                <input type="hidden" name="idMateria" required value="<?php echo $materia->idMateria?>"/>
                <?php echo form_error('idMateria')?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="buscarEncuesta">Año/<?php echo PERIODO?>:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required value="<?php echo ($encuesta->año)?$encuesta->año.'/'.$encuesta->cuatrimestre.' '.PERIODO:''?>">
                <input type="hidden" name="idEncuesta" required value="<?php echo $encuesta->idEncuesta?>"/>
                <?php echo form_error('idEncuesta')?>
                <input type="hidden" name="idFormulario" required value="<?php echo $encuesta->idFormulario?>"/>
              </div>
            </div>
            <div class="controls">
              <button class="generar btn btn-primary" href="#modalGenerar" role="button" data-toggle="modal">Generar Claves de Acceso</button>
              <input class="btn btn-primary" type="submit" name="submit" value="Ver Claves generadas " />
            </div>
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
      <h3 id="myModalLabel">Generar Claves de Accesos</h3>
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
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_carrera("<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_materia("<?php echo site_url('carreras/buscarMateriasAJAX')?>");
    autocompletar_encuesta("<?php echo site_url('encuestas/buscarAJAX')?>");
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