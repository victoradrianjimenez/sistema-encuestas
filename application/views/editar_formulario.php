<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <style>
    .Secciones{list-style-type: none; margin: 0;}
    .Secciones li{border: 1px solid #2BA6CB; padding: 5px; margin: 5px 0;}
    .Preguntas{list-style-type: none; margin: 5px;}
    .Preguntas li{border: 1px solid #CCCCCC;}
    .Seccion .btn-group, .Pregunta .btn-group{float:right; line-height:0;}
    #botonAgregarSeccion{float:right; margin:0 6px;}
  </style>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Formularios</h3>
          <p>Esta sección contiene las funcionalidades necesarias para la gestión de los formularios utilizados para la toma de encuestas.</p>
        </div>
      </div>
  
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <?php $item_submenu = 1;
            include 'templates/submenu-formularios.php';
          ?>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4>Formulario</h4>
          <form action="<?php echo $urlFormulario?>" method="post">
            <label for="campoNombre">Nombre: <span class="opcional">*</span></label>
            <input class="input-block-level" id="campoNombre" type="text" name="nombre" value="<?php echo set_value('nombre')?>" required />
            <label for="campoTitulo">Título: <span class="opcional">*</span></label>
            <input class="input-block-level" id="campoTitulo" type="text" name="titulo" value="<?php echo set_value('titulo')?>" required />
            <div class="row-fluid">
              <div class="span8">
                <label for="campoDescripcion">Descripción: </label>
                <input class="input-block-level" id="campoDescripcion" type="text" name="descripcion" value="<?php echo set_value('descripcion')?>" />
              </div>
              <div class="span4">
                <label for="campoAdicionales">Preguntas adicionales: <span class="opcional">*</span></label>
                <input class="input-block-level" id="campoAdicionales" type="number" name="preguntasAdicionales" min="0" max="255" step="1" value="10" value="<?php echo set_value('preguntasAdicionales')?>"/>
              </div>
            </div>
            <div class="Formularios">
              <legend>Secciones
                <a id="botonAgregarSeccion" href="#modalAgregarSeccion" role="button" data-toggle="modal" title="Agregar sección..."><i class="icon-circle-plus"></i></a>
              </legend>
            </div>
            <ul class="Secciones"></ul>
            
            <!-- Botones -->
            <div>
              <input id="Aceptar" class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
  
  <div id="HTMLSeccion" class="hide">
    <li class="Seccion">
      <div class="btn-group">
        <a class="subirSeccion" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarSeccion" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarSeccion" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
        <a class="nuevaPregunta" title="Agregar pregunta..." href="#"><i class="icon-circle-plus"></i></a>
      </div>
      <input type="hidden" name="textoSeccion" value="" />
      <input type="hidden" name="descripcionSeccion" value="" />
      <input type="hidden" name="tipoSeccion" value="" />
      <h4 class="texto"></h4>
      <h6 class="descripcion"></h6>
      <ul class="Preguntas"></ul>
    </li>
  </div>
  
  <div id="HTMLPregunta" class="hide">
    <li class="Pregunta">
      <div class="btn-group">
        <a class="subirPregunta" title="Subir" href="#"><i class="icon-circle-arrow-top"></i></a>
        <a class="bajarPregunta" title="Bajar" href="#"><i class="icon-circle-arrow-down"></i></a>
        <a class="eliminarPregunta" title="Eliminar" href="#"><i class="icon-circle-remove"></i></a>
      </div>
      <input type="hidden" name="idPregunta" value="" />
      <p class="texto"></p>
    </li>
  </div>
  
  <!-- ventana modal para agregar una seccion -->
  <div id="modalAgregarSeccion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar sección</h3>
    </div>
    <div class="modal-body">
      <label>Texto: <span class="opcional">*</span></label>
      <input class="input-block-level" type="text" name="textoSeccion" required/>
      <label>Descripción: </label>
      <input class="input-block-level" type="text" name="descripcionSeccion" />
      <label>Tipo de Sección: <span class="opcional">*</span></label>
      <select name="tipoSeccion">
        <option value="<?php echo SECCION_TIPO_NORMAL?>">Normal</option>
        <option value="<?php echo SECCION_TIPO_DOCENTE?>">Docente</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarSeccion">Agregar</button>
    </div>
  </div>
  
  <!-- ventana modal para agregar una pregunta -->
  <div id="modalAgregarPregunta" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar pregunta</h3>
    </div>
    <div class="modal-body buscador">
      <label>Buscar pregunta: <span class="opcional">*</span></label>
      <input class="input-block-level" id="buscarPregunta" name="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" ><i class="icon-search"></i>
      <input type="hidden" name="idMateria" value=""/>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarPregunta">Agregar</button>
    </div>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.min.js')?>"></script>
  <script src="<?php echo base_url('js/edicion-formularios.js')?>"></script>
  <script>
    autocompletar_pregunta($('#buscarPregunta'), "<?php echo site_url('preguntas/buscarAjax')?>");
  </script>
</body>
</html>