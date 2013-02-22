<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Generar Informe por Clave de acceso</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <style>
    .form-horizontal .controls {
      margin-left: 90px;
    }
    .form-horizontal .control-label {
      width: 70px;
      float: left;
    }
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Informes por Claves de Acceso</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
        </div>
        
        <!-- Main -->
        <div class="span9">
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
                <?php echo form_error('idFormulario')?>
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
                <label class="checkbox"><input type="checkbox" name="indicesSecciones" checked />Incluir índices de secciones</label>
                <label class="checkbox"><input type="checkbox" name="indicesDocentes" checked />Incluir índices para cada docente</label>
                <label class="checkbox"><input type="checkbox" name="indiceGlobal" checked />Incluir indice global</label>
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
  <script>
    //listar claves de acceso al elegir la encuesta
    listarClaves = function(){
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
          idCarrera: idCarrera,
        }
      }).done(function(msg){
        var filas = msg.split("\n");
        for (var i=0; i<filas.length-1; i++){
          if (filas[i].length<5) continue;
          //separo datos en columnas
          var columnas = filas[i].split("\t");
          var id = columnas[0];
          var datos = columnas[1]+" / "+columnas[3];
          //agregar fila a la lista desplegable
          $('#listaClaves').append('<option value="'+id+'">'+datos+'</option>');
        }
        $('#listaClaves').children().first().attr('selected','');
      });
    };  
  
    //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
    $('#buscarCarrera').keydown(function(event){
      if (event.which==9) return; //ignorar al presionar Tab
      $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
    });
    //realizo la busqueda de usuarios con AJAX
    $('#buscarCarrera').typeahead({
      matcher: function (item) {return true},    
      sorter: function (items) {return items},
      source: function(query, process){
        return $.ajax({
          type: "POST", 
          url: "<?php echo site_url('carreras/buscarAJAX')?>", 
          data:{ buscar: query}
        }).done(function(msg){
          var filas = msg.split("\n");
          var items = new Array();
          for (var i=0; i<filas.length; i++){
            if (filas[i].length<5) continue;
            items.push(filas[i]);
          }
          return process(items);
        });
      },
      highlighter: function (item) {
        var cols = item.split("\t");
        var texto = cols[1]+" / "+cols[2]; //nombre / plan
        var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
        return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
          return '<strong>' + match + '</strong>'
        })
      },
      updater: function (item) {
        var cols = item.split("\t");
        $('#buscarCarrera').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
        listarClaves();
        return cols[1]+" / "+cols[2];
      }
    });
    
    //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
    $('#buscarMateria').keydown(function(event){
      if (event.which==9) return; //ignorar al presionar Tab
      $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
    });
    //realizo la busqueda de usuarios con AJAX
    $('#buscarMateria').typeahead({
      matcher: function (item) {return true},    
      sorter: function (items) {return items},
      source: function(query, process){
        return $.ajax({
          type: "POST", 
          url: "<?php echo site_url('carreras/buscarMateriasAJAX')?>", 
          data:{ 
            buscar: query,
            idCarrera: $('input[name="idCarrera"]').val()
          }
        }).done(function(msg){
          var filas = msg.split("\n");
          var items = new Array();
          for (var i=0; i<filas.length; i++){
            if (filas[i].length<5) continue;
            items.push(filas[i]);
          }
          return process(items);
        });
      },
      highlighter: function (item) {
        var cols = item.split("\t");
        var texto = cols[1]+" / "+cols[2]; //nombre / codigo
        var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
        return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
          return '<strong>' + match + '</strong>'
        })
      },
      updater: function (item) {
        var cols = item.split("\t");
        $('#buscarMateria').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
        listarClaves();
        return cols[1]+" / "+cols[2];
      }
    });

    //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
    $('#buscarEncuesta').keydown(function(event){
      if (event.which==9) return; //ignorar al presionar Tab
      $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
    });
    //realizo la busqueda de usuarios con AJAX
    $('#buscarEncuesta').typeahead({
      matcher: function (item) {return true},    
      sorter: function (items) {return items},
      source: function(query, process){
        return $.ajax({
          type: "POST", 
          url: "<?php echo site_url('encuestas/buscarEncuestaAJAX')?>", 
          data:{buscar: query}
        }).done(function(msg){
          var filas = msg.split("\n");
          var items = new Array();
          for (var i=0; i<filas.length; i++){
            if (filas[i].length<5) continue;
            items.push(filas[i]);
          }
          return process(items);
        });
      },
      highlighter: function (item) {
        var cols = item.split("\t");
        var texto = cols[2]+" / "+cols[3]; //año / cuatrimestre
        var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
        return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
          return '<strong>' + match + '</strong>'
        })
      },
      updater: function (item) {
        var cols = item.split("\t");
        cont = $('#buscarEncuesta').parentsUntil('control-group').first().parent().removeClass('error');
        cont.find('input[name="idEncuesta"]').val(cols[0]);
        cont.find('input[name="idFormulario"]').val(cols[1]);
        listarClaves();
        return cols[2]+" / "+cols[3];
      }
    });
    
    
    //ocultar mensaje de error al escribir
    $('input[type="text"]').keyup(function(){
      $(this).siblings('span.label').hide('fast');
    });
  </script>
</body>
</html>