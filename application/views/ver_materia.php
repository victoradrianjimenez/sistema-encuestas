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
        <div class="span9">
          <h4><?php echo $materia->nombre.' ('.$materia->codigo.')'?></h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron docentes.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Tipo de Acceso</th>
                <th>Cargo</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="nombre"><?php echo $item['docente']->apellido?></td>
                  <td class="apellido"><?php echo $item['docente']->nombre?></td>
                  <td class="tipoAcceso"><?php echo ($item['datos']['tipoAcceso']==TIPO_ACCESO_JEFE_CATEDRA)?'Jefe de cátedra':'Docente'?></td>
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
        <h5><?php echo $materia->nombre.' - Código '.$materia->codigo?></h5>
        <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" />
        <div class="control-group"> 
          <label class="control-label" for="buscarUsuario">Buscar docente: <span class="opcional" title="Campo obligatorio.">*</span></label>
          <div class="controls buscador">
            <input class="input-block-level" id="buscarUsuario" type="text" data-provide="typeahead" autocomplete="off"><i class="icon-search"></i>
            <input type="hidden" name="idDocente" value=""/>
            <?php echo form_error('idDocente')?>
          </div>
        </div>
        <div class="control-group"> 
          <label class="control-label" for="campoTipoAcceso">Tipo de acceso: <span class="opcional" title="Campo obligatorio.">*</span></label>
          <div class="controls">
            <select class="input-block-level" id="campoTipoAcceso" name="tipoAcceso" required>
              <option value="<?php echo TIPO_ACCESO_DOCENTE?>" selected="selected">Docente</option>
              <option value="<?php echo TIPO_ACCESO_JEFE_CATEDRA?>">Jefe de Cátedra</option>
            </select>
            <?php echo form_error('tipoAcceso')?>
          </div>
        </div>
        <div class="control-group"> 
          <label class="control-label" for="campoOrdenFormulario">Posición en formulario: <span class="opcional" title="Campo obligatorio.">*</span></label>
          <div class="controls">
            <input class="input-block-level" id="campoOrdenFormulario" type="number" min="0" max="255" step="1" name="ordenFormulario" value="1" required/>
            <?php echo form_error('ordenFormulario')?>
          </div>
        </div>
        <div class="control-group"> 
          <label class="control-label" for="campoCargo">Cargo:</label>
          <div class="controls">
            <input class="input-block-level" id="campoCargo" type="text" name="cargo" />
            <?php echo form_error('cargo')?>
          </div>
        </div>   
      </div>
      <div class="modal-footer">
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
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
  <script src="<?php echo base_url('js/bootstrap-typeahead.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
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
    autocompletar_usuario($('#buscarUsuario'), "<?php echo site_url('usuarios/buscarAJAX')?>");
  </script>
</body>
</html>