<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Title -->
        <div class="span12">
          <h3>Gestión de Departamentos, Carreras y Materias</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los departamentos pertenecientes a la facultad. Las funcionalidades disponibles permiten agregar nuevos departamentos, modificar o bien eliminar departamentos existentes.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-facultad.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <form action="<?php echo $urlFormulario?>" method="post">
            <div class="control-group">
              <div class="controls">
                <h4><?php echo $tituloFormulario?></h4>
              </div>
            </div>
            <input type="hidden" name="idDepartamento" value="<?php echo $departamento->idDepartamento?>" required /> 
            <div class="control-group">
              <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" type="text" id="campoNombre" name="nombre" maxlength="60" value="<?php echo $departamento->nombre?>" required/>
                <?php echo form_error('nombre')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarUsuario" title="El jefe de departamento debe estar registrado previamente en el sistema.">Jefe de Departamento: </label>
              <div class="controls buscador">
                <input class="input-block-level" type="text" id="buscarUsuario" name="buscarUsuario" data-provide="typeahead" autocomplete="off" value="<?php echo trim($jefeDepartamento->nombre.' '.$jefeDepartamento->apellido)?>"/><i class="icon-search"></i>
                <?php echo form_error('idJefeDepartamento')?>
                <input type="hidden" name="idJefeDepartamento" value="<?php echo $departamento->idJefeDepartamento?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Opciones: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="publicarInformes" value="1" <?php echo ($departamento->publicarInformes==RESPUESTA_SI)?'checked="checked"':''?> /> Los informes por Departamento son Públicos</label>
                <?php echo form_error('publicarInformes')?>
                <label class="checkbox"><input type="checkbox" name="publicarHistoricos" value="1" <?php echo ($departamento->publicarHistoricos==RESPUESTA_SI)?'checked="checked"':''?> /> Los informes Históricos por Departamento son Públicos</label>
                <?php echo form_error('publicarHistoricos')?>
              </div>
            </div>
            <!-- Botones -->
            <div class="control-group">
              <div class="controls">
                <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br/>
  </div>
  <?php include 'templates/footer.php'?>
  
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.min.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script>
    autocompletar_usuario($('#buscarUsuario'), "<?php echo site_url('usuarios/buscarAJAX')?>");
  </script>
</body>
</html>