<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Docentes y Autoridades</h3>
          <p>---Descripción---</p>
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
            <div class="control-group">
              <label class="control-label" for="buscarDepartamento">Departamento: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="buscarDepartamento" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo $departamento->nombre?>" required>
                <input type="hidden" name="idDepartamento" value="<?php echo $carrera->idDepartamento?>" required/>
                <?php echo form_error('idDepartamento')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" value="<?php echo (set_value('nombre'))?set_value('nombre'):$carrera->nombre?>" required />
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoPlan">Plan: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoPlan" type="number" min="1900" max="2100" name="plan" value="<?php echo (set_value('plan'))?set_value('plan'):$carrera->plan?>" required />
                <?php echo form_error('plan'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="buscarUsuario">Director de carrera: </label>
              <div class="controls">
                <input class="input-block-level" id="buscarUsuario" type="text" data-provide="typeahead" autocomplete="off" value="<?php echo trim($director->nombre.' '.$director->apellido)?>">
                <input type="hidden" name="idDirectorCarrera" value="<?php echo $carrera->idDirectorCarrera?>"/>
                <?php echo form_error('idDirectorCarrera')?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Opciones: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="publicarInforme" <?php echo (isset($_POST['publicarInforme']) || $carrera->publicarInformes=='S')?'checked="checked"':''?> /> Los informes por Carrera son Públicos</label>
                <?php echo form_error('publicarInforme')?>
                <label class="checkbox"><input type="checkbox" name="publicarHistorico" <?php echo (isset($_POST['publicarHistorico']) || $carrera->publicarHistoricos=='S')?'checked="checked"':''?> /> Los informes Históricos por Carrera son Públicos</label>
                <?php echo form_error('publicarHistorico')?>
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
  
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_usuario("<?php echo site_url('usuarios/buscarAJAX')?>");
    autocompletar_departamento("<?php echo site_url('departamentos/buscarAJAX')?>");
  </script>
</body>
</html>