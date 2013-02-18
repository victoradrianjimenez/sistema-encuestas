<!-- Última revisión: 2012-02-01 2:56 a.m. -->

<h5><?php echo $carrera->nombre.' - Plan '.$carrera->plan?></h5>
<input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
<div class="control-group"> 
  <label class="control-label" for="buscarMateria">Buscar materia: </label>
  <div class="controls">
    <input class="input-xlarge" id="buscarMateria" type="text" data-provide="typeahead" autocomplete="off">
    <input type="hidden" name="idMateria" value=""/>
    <?php echo form_error('idMateria')?>
  </div>
</div>

<script>
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarMateria').keydown(function(){
    if (event.which==9) return; //ignorar al presionar Tab
    $(this).parentsUntil('control-group').first().parent().addClass('error').find('input[type="hidden"]').val('');
  });
  //realizo la busqueda de materias con AJAX
  $('#buscarMateria').typeahead({
    matcher: function (item) {return true},    
    sorter: function (items) {return items},
    source: function(query, process){
      return $.ajax({
        type: "POST", 
        url: "<?php echo site_url('materias/buscarAJAX')?>", 
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
      var texto = cols[1]+' ('+cols[2]+')'; //nombre (codigo)
      var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
      return texto.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    },
    updater: function (item) {
      var cols = item.split("\t");
      $('#buscarMateria').parentsUntil('control-group').first().parent().removeClass('error').find('input[type="hidden"]').val(cols[0]);
      return cols[1]+' ('+cols[2]+')';
    }
  });
  $('input[type="text"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>