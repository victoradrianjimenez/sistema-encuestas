<!-- Última revisión: 2012-02-05 11:35 p.m. -->

<div class="control-group">
  <label class="control-label" for="buscarFormulario">Formulario: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="buscarFormulario" type="text" autocomplete="off">
    <input type="hidden" name="idFormulario" value=""/>
    <?php echo form_error('idFormulario')?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoAnio">Año: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoAnio" type="number" name="anio" min="1900" max="2100" step="1" value="<?php echo date('Y')?>"/>
    <?php echo form_error('anio')?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="campoCuatrimestre" title="Período/Cuatrimestre">Período: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" id="campoCuatrimestre" type="number" name="cuatrimestre" min="1" step="1" value="1" />
    <?php echo form_error('cuatrimestre')?>
  </div>
</div>

<script>
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarFormulario').keydown(function(){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de usuarios con AJAX
  $('#buscarFormulario').typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      return $.ajax({
        type: "POST", 
        url: "<?php echo site_url('formularios/buscarAJAX')?>", 
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
      var texto = cols[1]+' ('+cols[2]+')'; //nombre (fecha)
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      $('#buscarFormulario').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+' ('+cols[2]+')';
    }
  });
</script>