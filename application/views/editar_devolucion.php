<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Editar Devolución</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Devoluciones</h3>
          <p>---Descripción---</p>
        </div>
      </div>
  
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Nueva devolución</h4>
          <p>Asignatura: <?php echo $materia->nombre.' / '.$materia->codigo?></p>
          <form action="<?php echo site_url('devoluciones/nueva')?>" method="post">
            <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" required/>
            <fieldset>
              <legend>Evaluación de la cátedra sobre los resultados de las encuestas</legend>
              
              <label for="buscarEncuesta">Año:</label>
              <div class="controls">
                <input class="input-block-level" id="buscarEncuesta" type="text" autocomplete="off" data-provide="typeahead" required>
                <input type="hidden" name="idEncuesta" required/>
                <?php echo form_error('idEncuesta')?>
                <input type="hidden" name="idFormulario" required/>
              </div>
              
              <label for="campoFortalezas">Identifique las fortalezas del curso: </label>
              <textarea class="input-block-level" id="campoFortalezas"  name="fortalezas" rows="4"></textarea>
              <?php echo form_error('fortalezas')?>
              
              <label for="campoDebilidades">Identifique las debilidades del curso: </label>
              <textarea class="input-block-level" id="campoDebilidades" name="debilidades" rows="4"></textarea>
              <?php echo form_error('debilidades')?>
              
              <label for="campoAlumnos">Reflexiones sobre las opiniones de los alumnos: </label>
              <textarea class="input-block-level" id="campoAlumnos" name="alumnos" rows="4"></textarea>
              <?php echo form_error('alumnos')?>
              
              <label for="campoDocentes">Reflexiones sobre el desempeño de los docentes: </label>
              <textarea class="input-block-level" id="campoDocentes" name="docentes" rows="4"></textarea>
              <?php echo form_error('docentes')?>
              
              <label for="campoMejoras">Plan de mejoras propuesto: </label>
              <textarea class="input-block-level" id="campoMejoras" name="mejoras" rows="4"></textarea>
              <?php echo form_error('mejoras')?>
  
              <div>
                <input id="Aceptar" class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
              </div>
            </fieldset>
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
        return cols[2]+" / "+cols[3];
      }
    });
  </script>
</body>
</html>