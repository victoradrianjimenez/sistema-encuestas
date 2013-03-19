<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Datos Materia - <?php echo NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    
    <?php include 'templates/menu-nav.php'?>
    
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
            <?php include 'templates/descripcion-departamentos.php'?>
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
        <div class="span9">
          <h4><?php echo $materia->nombre.' ('.$materia->codigo.')'?></h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron docentes.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Cargo</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="nombre"><?php echo $item['docente']->apellido?></td>
                  <td class="apellido"><?php echo $item['docente']->nombre?></td>
                  <td class="cargo"><?php echo (isset($item['datos']['cargo'])) ? $item['datos']['cargo'] : ''?></td>
                  <td>
                    <a class="quitar" href="#" title="Quitar asociación del docente con la materia" value="<?php echo $item['docente']->id?>">Quitar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="">
            <a class="btn btn-primary" href="<?php echo site_url('materias/modificar/'.$materia->idMateria)?>">Modificar materia</a>
            <button class="btn btn-primary" href="#modalAsociar" role="button" data-toggle="modal">Asociar docente...</button>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
  
  <!-- ventana modal para asociar docentes a la materia -->
  <div id="modalAsociar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Asociar materia</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('materias/asociarDocente')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-asociar-docente.php'?>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- ventana modal para desasociar docentes de la materia -->
  <div id="modalDesasociar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Desasociar docente de <?php echo $materia->nombre?></h3>
    </div>
    <form action="<?php echo site_url('materias/desasociarDocente')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
        <input type="hidden" name="idDocente" value="" />
        <h5 class="nombre"></h5>
        <p>¿Desea continuar?</p>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script>
    $('.quitar').click(function(){
      idDocente = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      apellido = $(this).parentsUntil('tr').parent().find('.apellido').text();
      //cargo el id del docente en el formulario      
      $('#modalDesasociar input[name="idDocente"]').val(idDocente);
      //pongo el nombre del docente en el dialogo
      $("#modalDesasociar").find('.nombre').html(nombre+' '+apellido);
      $("#modalDesasociar").modal();
      return false;
    });
  </script>
</body>
</html>