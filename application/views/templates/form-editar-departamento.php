<!-- Última revisión: 2012-02-01 1:36 p.m. -->

<input type="hidden" name="idDepartamento" value="<?php echo $departamento->idDepartamento?>" required /> 
<div class="control-group">
  <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
  <div class="controls">
    <input class="input-xlarge" type="text" id="campoNombre" name="nombre" value="<?php echo $departamento->nombre?>" required />
    <?php echo form_error('nombre')?>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="buscarUsuario">Jefe de Departamento: </label>
  <div class="controls">
    <input class="input-xlarge" type="text" id="buscarUsuario" data-provide="typeahead" autocomplete="off" value="<?php echo ($jefeDepartamento->nombre || $jefeDepartamento->apellido)?$jefeDepartamento->nombre.' '.$jefeDepartamento->apellido:''?>"/>
    <input type="hidden" name="idJefeDepartamento" value="<?php echo $departamento->idJefeDepartamento?>"/>
    <?php echo form_error('idJefeDepartamento')?>
  </div>
</div>

<script>
  //cuando edito el buscador, lo pongo en rojo hasta que elija un item del listado
  $('#buscarUsuario').keydown(function(event){
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

  //ocultar mensaje de error al escribir
  $('input[type="text"]').keyup(function(){
    $(this).siblings('span.label').hide('fast');
  });
</script>