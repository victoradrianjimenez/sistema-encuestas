<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <!--<script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>-->
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Departamentos, Carreras y Materias</h3>
          <p>---Descripción---</p>
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
          <form action="<?php echo $urlFormulario?>" method="post">
            <div class="control-group">
              <div class="controls">
                <h4><?php echo $tituloFormulario?></h4>
              </div>
            </div>
            <input type="hidden" name="idMateria" value="<?php echo $materia->idMateria?>" required />
            <div class="control-group">
              <label class="control-label" for="campoNombre">Nombre: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoNombre" type="text" name="nombre" value="<?php echo (set_value('nombre'))?set_value('nombre'):$materia->nombre?>" required />
                <?php echo form_error('nombre'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="campoCodigo">Código: <span class="opcional">*</span></label>
              <div class="controls">
                <input class="input-block-level" id="campoCodigo" type="text" name="codigo" value="<?php echo (set_value('codigo'))?set_value('codigo'):$materia->codigo?>" required />
                <?php echo form_error('codigo'); ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Opciones: </label>
              <div class="controls">
                <label class="checkbox"><input type="checkbox" name="publicarInforme" <?php echo (isset($_POST['publicarInforme']) || $materia->publicarInformes=='S')?'checked="checked"':''?> /> Los informes por Materia son Públicos</label>
                <?php echo form_error('publicarInforme')?>
                <label class="checkbox"><input type="checkbox" name="publicarHistorico" <?php echo (isset($_POST['publicarHistorico']) || $materia->publicarHistoricos=='S')?'checked="checked"':''?> /> Los informes Históricos por Materia son Públicos</label>
                <?php echo form_error('publicarHistorico')?>
                <label class="checkbox"><input type="checkbox" name="publicarDevoluciones" <?php echo (isset($_POST['publicarDevoluciones']) || $materia->publicarDevoluciones=='S')?'checked="checked"':''?> /> Los Planes de Mejoras de la Materia son Públicos</label>
                <?php echo form_error('publicarDevoluciones')?>
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
  <!--<script src="<?php echo base_url('js/autocompletar.js')?>"></script>-->
</body>
</html>
