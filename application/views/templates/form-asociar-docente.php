<!-- Última revisión: 2012-02-01 4:17 p.m. -->

<h5><?php echo $materia->nombre.' - Código '.$materia->codigo?></h5>
<input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
<div class="control-group"> 
  <label class="control-label" for="buscarUsuario">Buscar docente: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="buscarUsuario" type="text" data-provide="typeahead" autocomplete="off">
    <input type="hidden" name="idDocente" value=""/>
    <?php echo form_error('idDocente')?>
  </div>
</div>
<div class="control-group"> 
  <label class="control-label" for="campoOrdenFormulario">Posición en formulario: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoOrdenFormulario" type="number" min="0" max="255" step="1" name="ordenFormulario" value="1" required/>
    <?php echo form_error('ordenFormulario')?>
  </div>
</div>
<div class="control-group"> 
  <label class="control-label" for="campoCargo">Cargo:</label>
  <div class="controls">
    <input class="input-xlarge" id="campoCargo" type="text" name="cargo" />
    <?php echo form_error('cargo')?>
  </div>
</div>

<script>
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarUsuario').keydown(function(){
    if (event.which==9) return; //ignorar al presionar Tab
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
  $('input[type="text"], input[type="number"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>