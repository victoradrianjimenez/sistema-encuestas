<h3><?php echo $titulo?></h3>
<form action="<?php echo $link?>" method="post">
  <input type="hidden" name="idDepartamento" value="<?php echo $departamento['idDepartamento']?>" required /> 
  <div class="twelve columns">
    <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
    <input type="text" id="campoNombre" name="nombre" value="<?php echo $departamento['nombre']?>" required />
    <?php echo form_error('nombre')?>
    
    <label for="buscarUsuario">Jefe de Departamento: </label>
    <div class="buscador">
      <input id="buscarUsuario" type="text" autocomplete="off">
      <i class="gen-enclosed foundicon-search"></i>
      <select id="listausuarios" name="idJefeDepartamento" size="3" required>
        <option value="<?php echo $departamento['idJefeDepartamento']?>" selected>(Jefe de departamento actual)</option>
        <option value="">(Nunguno)</option>
      </select>
      <?php echo form_error('idJefeDepartamento')?>
    </div>
    
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
  </div>
</form>
<script>
  //realizo la busqueda de usuarios con AJAX
  $('#buscarUsuario').keyup(function(){
    $.ajax({
      type: "POST", 
      url: "<?php echo site_url('usuarios/buscarAJAX')?>", 
      data:{ buscar: $(this).val() }
    }).done(function(msg){
      $('#listausuarios').empty();
      var filas = msg.split("\n");
      var cnt = 0;
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<6) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' ('+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listausuarios').append('<option value="'+id+'">'+datos+'</option>');
        cnt++;
      }
      if(cnt==0){
        $('#listausuarios').append('<option value="' + <?php echo $departamento['idJefeDepartamento']?> + '" selected>(Jefe de departamento actual)</option>');
        $('#listausuarios').append('<option value="">(Nunguno)</option>');
      }
      $('#listausuarios').children().first().attr('selected','');
    });
  });
  $('#listausuarios').change(function(){
    textoOpcion = $(this).children('option[selected]').text();
    $('#buscarUsuario').val(textoOpcion);
    $(this).next('small.error').hide('fast');
  });
  
  //ocultar mensaje de error al escribir
  $('#campoNombre').keyup(function(){
    $(this).next('small.error').hide('fast');
  });
</script>