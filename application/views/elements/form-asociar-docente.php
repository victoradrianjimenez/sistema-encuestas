<!-- Última revisión: 2012-02-01 4:17 p.m. -->

<form action="<?php echo $link?>" method="post">
  <h3>Asociar docente</h3>
  <h5><?php echo $materia->nombre.' - Código '.$materia->codigo?></h5>
  <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
  <label for="buscarUsuario">Buscar docente: <span class="opcional">*</span></label>
  <div class="buscador">
    <input id="buscarUsuario" type="text" autocomplete="off">
    <i class="gen-enclosed foundicon-search"></i>
    <select id="listaResultado" name="idDocente" size="3" required>
    </select>
    <?php echo form_error('idDocente')?>
  </div>

  <label for="campoOrdenFormulario">Orden en el que aparece en el formulario: <span class="opcional">*</span></label>
  <input id="campoOrdenFormulario" type="number" min="0" max="255" step="1" name="ordenFormulario" value="1" required/>
  <?php echo form_error('ordenFormulario')?>
  
  <label for="campoCargo">Cargo:</label>
  <input id="campoCargo" type="text" name="cargo" />
  <?php echo form_error('cargo')?>
  <div class="row">         
    <div class="ten columns centered">
      <div class="six mobile-one columns push-one-mobile">
        <input class="button cancelar" type="button" value="Cancelar"/>
      </div>
      <div class="six mobile-one columns pull-one-mobile ">
        <input class="button" type="submit" name="submit" value="Aceptar" />
      </div>
    </div>
  </div>
</form>
<script>
  //realizo la busqueda de materias con AJAX
  $('#buscarUsuario').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('usuarios/buscarAJAX')?>", 
      data:{ buscar: $('#buscarUsuario').val() }
    }).done(function(msg){
      $('#listaResultado').empty();
      //separo los datos separados en filas
      var filas = msg.split("\n");
      for (var i=0; i<filas.length-1; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listaResultado').append('<option value="'+id+'">'+datos+'</option>');
      }
    });
  });
  $('#listaResultado').change(function(){
    $(this).next('small.error').hide('fast');
  });
  $('input[type="text"]').keyup(function(){
    $(this).next('small.error').hide('fast');
  });
  $('input[type="number"]').change(function(){
    $(this).next('small.error').hide('fast');
  });
</script>