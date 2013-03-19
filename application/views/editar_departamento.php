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
            <?php include 'templates/descripcion-departamentos.php'?>
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
              <label class="control-label" for="buscarUsuario">Jefe de Departamento: </label>
              <div class="controls">
                <input class="input-block-level" type="text" id="buscarUsuario" data-provide="typeahead" autocomplete="off" value="<?php echo trim($jefeDepartamento->nombre.' '.$jefeDepartamento->apellido)?>"/>
                <?php echo form_error('idJefeDepartamento')?>
                <input type="hidden" name="idJefeDepartamento" value="<?php echo $departamento->idJefeDepartamento?>"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Opciones: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="publicarInformes" value="1" <?php echo ($departamento->publicarInformes=='S')?'checked="checked"':''?> /> Los informes por Departamento son Públicos</label>
                <?php echo form_error('publicarInformes')?>
                <label class="checkbox"><input type="checkbox" name="publicarHistoricos" value="1" <?php echo ($departamento->publicarHistoricos=='S')?'checked="checked"':''?> /> Los informes Históricos por Departamento son Públicos</label>
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
  
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <script src="<?php echo base_url('js/formularios.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script>
    autocompletar_usuario("<?php echo site_url('usuarios/buscarAJAX')?>");
  </script>
</body>
</html>