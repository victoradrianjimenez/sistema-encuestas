<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar Informe por Clave de acceso - <?php echo NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <style>
    .form-horizontal .controls {
      margin-left: 90px;
    }
    .form-horizontal .control-label {
      width: 70px;
      float: left;
    }
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
          <h3>Respuestas por clave de acceso</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- Main -->
        <div id="contenedor" class="span12">
          <h4>Solicitar informe por clave de acceso</h4>
          <form class="form-horizontal" action="<?php echo site_url('informes/clave')?>" method="post">
            
            <div class="control-group">
              <label class="control-label" for="buscarCarrera">Carrera: </label>
              <div class="controls">
                <input class="input-block-level" id="buscarCarrera" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idCarrera" required/>
                <?php echo form_error('idCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarMateria">Materia: </label>
              <div class="controls">
                <input class="input-block-level" id="buscarMateria" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idMateria" required/>
                <?php echo form_error('idMateria')?>
              </div>
            </div>
            <div class="control-group">  
              <label class="control-label" for="buscarEncuesta">Año: </label>
              <div class="controls">
                <input class="input-block-level" id="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idEncuesta" required/>
                <?php echo form_error('idEncuesta')?>
                <input type="hidden" name="idFormulario" required/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="listaClaves">Accesos: </label>
              <div class="controls">
                <select id="listaClaves" name="idClave" size="3" required>
                </select>
                <?php echo form_error('idClave')?>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="indicesSecciones" value="1" checked />Incluir índices de secciones</label>
                <label class="checkbox"><input type="checkbox" name="indicesDocentes" value="1" checked />Incluir índices para cada docente</label>
                <label class="checkbox"><input type="checkbox" name="indiceGlobal" value="1" checked />Incluir indice global</label>
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
  <script src="<?php echo base_url('js/bootstrap-tooltip.js')?>"></script>
  <script src="<?php echo base_url('js/formulario.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_carrera("<?php echo site_url('carreras/buscarAJAX')?>");
    autocompletar_encuesta("<?php echo site_url('encuestas/buscarAJAX')?>");
    autocompletar_materia("<?php echo site_url('carreras/buscarMateriasAJAX')?>");
    
    //listar claves de acceso al elegir la encuesta
    $('#buscarEncuesta').change(function(){
      $('#listaClaves').empty();
      var idEncuesta = $('#buscarEncuesta').siblings('input[name="idEncuesta"]').val();
      var idFormulario = $('#buscarEncuesta').siblings('input[name="idFormulario"]').val();
      var idMateria = $('#buscarMateria').siblings('input[name="idMateria"]').val();
      var idCarrera = $('#buscarCarrera').siblings('input[name="idCarrera"]').val();
      //si no se ingreso algun campo, terminar sin buscar nada
      if (idEncuesta==''||idFormulario==''||idMateria==''||idCarrera=='') return;
      $.ajax({
        type: "POST", 
        url: "<?php echo site_url('claves/listarClavesMateriaAJAX')?>", 
        data: { 
          idEncuesta: idEncuesta,
          idFormulario: idFormulario,
          idMateria: idMateria,
          idCarrera: idCarrera
        }
      }).done(function(msg){
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<5) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0];
          var datos = columnas[1]+" - "+columnas[3];
          //agregar fila a la lista desplegable
          $('#listaClaves').append('<option value="'+id+'">'+datos+'</option>');
        }
        $('#listaClaves').children().first().attr('selected','');
      });
    });
  </script>
</body>
</html>