<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Materias - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
          <h3>Gestión de Departamentos, Carreras y Materias</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de las materias pertenecientes a la facultad para la toma de encuestas.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 3;
            include 'templates/submenu-facultad.php';
          ?>
        </div>
        
        <!-- Main -->
        <div id="contenedor" class="span9">
          
          <h4>Buscar</h4>
          <div class="control-group" title="">
            <label class="control-label" for="buscarMateria"></label>
            <div class="controls buscador">
              <input class="input-block-level" id="buscarMateria" name="buscarMateria" type="text" data-provide="typeahead" autocomplete="off" value=""><i class="icon-search"></i>
              <input type="hidden" name="idMateria" value=""/>
              <?php echo form_error('idMateria')?>
            </div>
          </div> 
            
          <h4>Materias</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table id="tablaItems" class="table table-bordered table-striped">
              <thead>
                <th>Nombre</th>
                <th>Código</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr class="fila">
                  <td><a class="nombre" href="<?php echo site_url("materias/ver/".$item->idMateria)?>"><?php echo $item->nombre?></a></td>
                  <td><?php echo $item->codigo?></td>
                  <td>
                    <a class="modificar" href="<?php echo site_url('materias/modificar/'.$item->idMateria)?>">Modificar</a> /
                    <a class="eliminar" href="#" value="<?php echo $item->idMateria?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('materias/nueva')?>">Agregar materia</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  

  <!-- ventana modal para eliminar materias -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar materia</h3>
    </div>
    <form action="<?php echo site_url('materias/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idMateria" value="" />
        <h5 class="nombre"></h5>
        <p>¿Desea continuar?</p>      
      </div>
      <div class="modal-footer">
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script>
  
    $('input[name="buscarMateria"]').keydown(function(event){
      query = $(this).val();
      url = "<?php echo site_url('materias/buscarAJAX')?>";
      return $.ajax({
        type: "POST", 
        url: url, 
        data:{ buscar: query}
      }).done(function(msg){
        $('.fila').remove();
        $('.pagination').remove();
        var filas = msg.split("\n");
        var items = new Array();
        for (var i=0; i<filas.length; i++){
          if (filas[i].length<5) continue;
          cols = filas[i].split("\t");
          $('#tablaItems').append(
            '<tr class="fila">'+
            '  <td><a class="nombre" href="<?php echo site_url("materias/ver")?>/'+cols[0]+'">'+cols[1]+'</a></td>'+
            '  <td>'+cols[2]+'</td>'+
            '  <td>'+
            '    <a class="modificar" href="<?php echo site_url('materias/modificar')?>/'+cols[0]+'">Modificar</a> /'+
            '    <a class="eliminar" href="#" value="'+cols[0]+'">Eliminar</a>'+
            '  </td>'+
            '</tr>');
          $('.eliminar').click(function(){
            idMateria = $(this).attr('value');
            nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
            //cargo el id de la materia en el formulario
            $('#modalEliminar input[name="idMateria"]').val(idMateria);
            //pongo el nombre de la materia en el dialogo
            $("#modalEliminar").find('.nombre').html(nombre);
            $("#modalEliminar").modal();
            return false;
          });
        }
      });
    });
  
    $('.eliminar').click(function(){
      idMateria = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      //cargo el id de la materia en el formulario
      $('#modalEliminar input[name="idMateria"]').val(idMateria);
      //pongo el nombre de la materia en el dialogo
      $("#modalEliminar").find('.nombre').html(nombre);
      $("#modalEliminar").modal();
      return false;
    });
  </script>
</body>
</html>