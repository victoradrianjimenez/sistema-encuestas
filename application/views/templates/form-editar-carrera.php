<!-- Última revisión: 2012-02-01 2:33 p.m. -->

<input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>"/>
<div class="control-group">
  <label class="control-label" for="buscarDepartamento">Departamento: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="buscarDepartamento" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo $departamento->nombre?>">
    <input type="hidden" name="idDepartamento" value="<?php echo $carrera->idDepartamento?>"/>
    <?php echo form_error('idDepartamento')?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoNombre" type="text" name="nombre" value="<?php echo $carrera->nombre?>" required />
    <?php echo form_error('Nombre'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoPlan">Plan: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoPlan" type="number" min="1900" max="2100" name="plan" value="<?php echo $carrera->plan?>" required />
    <?php echo form_error('Plan'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="buscarUsuario">Director de carrera: </label>
  <div class="controls">
    <input class="input-xlarge" id="buscarUsuario" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo ($director->nombre||$director->apellido)?$director->nombre.' '.$director->apellido:''?>">
    <input type="hidden" name="idDirectorCarrera" value="<?php echo $director->id?>"/>
    <?php echo form_error('idDirectorCarrera')?>
  </div>
</div>

<script>
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarUsuario').keydown(function(){
    $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  $('#buscarUsuario').typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      return $.ajax({
        type: "POST", 
        url: "<?php echo site_url('usuarios/buscarAJAX')?>", 
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
      var texto = cols[1]+' '+cols[2]+' (ID='+cols[0]+')'; //nombre, apellido e id
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      $('#buscarUsuario').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+' '+cols[2];
    }
  });
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarDepartamento').keydown(function(){
    $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de departamentos con AJAX
  $('#buscarDepartamento').typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      return $.ajax({
        type: "POST", 
        url: "<?php echo site_url('departamentos/buscarAJAX')?>", 
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
      var texto = cols[1]; //nombre
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      $('#buscarDepartamento').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1];
    }
  });

  //ocultar mensaje de error al escribir
  $('input[type="text"], input[type="number"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>