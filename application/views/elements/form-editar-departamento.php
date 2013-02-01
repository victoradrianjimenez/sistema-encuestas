<!-- Última revisión: 2012-02-01 1:36 p.m. -->

<h3><?php echo $titulo?></h3>
<form action="<?php echo $link?>" method="post">
  <input type="hidden" name="idDepartamento" value="<?php echo $departamento->idDepartamento?>" required /> 
  <div class="twelve columns">
    <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
    <input type="text" id="campoNombre" name="nombre" value="<?php echo $departamento->nombre?>" required />
    <?php echo form_error('nombre')?>
    
    <label for="buscarUsuario">Jefe de Departamento: </label>
    <div class="buscador">
      <input id="buscarUsuario" type="text" autocomplete="off" value="<?php echo $jefeDepartamento->nombre.' '.$jefeDepartamento->apellido?>">
      <i class="gen-enclosed foundicon-search"></i>
      <select id="listaUsuarios" name="idJefeDepartamento" size="3">
        <?php if($jefeDepartamento->id):?>
        <option value="<?php echo $jefeDepartamento->id?>" selected>
          <?php echo $jefeDepartamento->nombre.' '.$jefeDepartamento->apellido.' (ID='.$jefeDepartamento->id.')'?>
        </option>
        <?php endif?>
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
      data:{ buscar:$(this).val() }
    }).done(function(msg){
      $('#listaUsuarios').empty();
      var filas = msg.split("\n");
      for (var i=0; i<filas.length; i++){
        if (filas[i].length<5) continue;
        //separo datos en columnas
        var columnas = filas[i].split("\t");
        var id = columnas[0];
        var datos = columnas[1] + ' ' + columnas[2] +' (ID='+columnas[0]+')';
        //agregar fila a la lista desplegable
        $('#listaUsuarios').append('<option value="'+id+'">'+datos+'</option>');
      }
      $('#listaUsuarios').children().first().attr('selected','');
    });
  });
  
  //al hacer click en el listado, copiar el texto en el buscador
  $('#listaUsuarios').change(function(){
    textoOpcion = $(this).children('option[selected]').text();
    $('#buscarUsuario').val(textoOpcion);
    $(this).next('small.error').hide('fast');
  });
  
  //ocultar mensaje de error al escribir
  $('input[type="text"]').keyup(function(){
    $(this).next('small.error').hide('fast');
  });
</script>