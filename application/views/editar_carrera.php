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
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de las carreras pertenecientes a la facultad para la toma de encuestas.</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 2;
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
            <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>"/>
            <div class="control-group" title="El departamento debe estar previamente cargado en el sistema.">
              <label class="control-label" for="buscarDepartamento">Departamento: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarDepartamento" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo $departamento->nombre?>" required><i class="icon-search"></i>
                <input type="hidden" name="idDepartamento" value="<?php echo $carrera->idDepartamento?>" required/>
                <?php echo form_error('idDepartamento')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoNombre">Nombre: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" maxlength="60" value="<?php echo $carrera->nombre?>" required />
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoPlan">Plan: <span class="opcional" title="Campo obligatorio.">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoPlan" type="number" min="1900" max="2100" name="plan" value="<?php echo $carrera->plan?>" required />
                <?php echo form_error('plan'); ?>
              </div>
            </div>
            <div class="control-group" title="El director de carrera debe estar registrado previamente como usuario del sistema.">
              <label class="control-label" for="buscarUsuario">Director de carrera: </label>
              <div class="controls buscador">
                <input class="input-block-level" id="buscarUsuario" name="buscarUsuario" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo trim($director->nombre.' '.$director->apellido)?>"><i class="icon-search"></i>
                <input type="hidden" name="idDirectorCarrera" value="<?php echo $carrera->idDirectorCarrera?>"/>
                <?php echo form_error('idDirectorCarrera')?>
              </div>
            </div>
            <div class="control-group" title="El organizador es aquel usuario encargado de generar claves de acceso y actualizar datos de las materias de la carrera.">
              <label class="control-label">Organizador: </label>
              <div class="controls buscador">
                <input class="input-block-level" name="buscarOrganizador" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo trim($organizador->nombre.' '.$organizador->apellido)?>"><i class="icon-search"></i>
                <input type="hidden" name="idOrganizador" value="<?php echo $carrera->idOrganizador?>"/>
                <?php echo form_error('idOrganizador')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Opciones: </label>
              <div class="controls">
                <label class="checkbox" title="Si se establece los informes como públicos, cualquier usuario podrá solicitarlos."><input type="checkbox" name="publicarInformes" value="1" <?php echo ($carrera->publicarInformes==RESPUESTA_SI)?'checked="checked"':''?> /> Los informes por Carrera son Públicos</label>
                <?php echo form_error('publicarInformes')?>
                <label class="checkbox" title="Si se establece los históricos como públicos, cualquier usuario podrá solicitarlos."><input type="checkbox" name="publicarHistoricos" value="1" <?php echo ($carrera->publicarHistoricos==RESPUESTA_SI)?'checked="checked"':''?> /> Los informes Históricos por Carrera son Públicos</label>
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
    <div id="push"></div><br />
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
    autocompletar_usuario($('input[name="buscarUsuario"]'), "<?php echo site_url('usuarios/buscarAJAX')?>");
    autocompletar_usuario($('input[name="buscarOrganizador"]'), "<?php echo site_url('usuarios/buscarAJAX')?>");
    autocompletar_departamento($('#buscarDepartamento'), "<?php echo site_url('departamentos/buscarAJAX')?>");
  </script>
</body>
</html>