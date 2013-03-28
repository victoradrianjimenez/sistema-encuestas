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
    h5.separador{border-bottom: 3px solid #2BA6CB;}
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
          <h4>Adicionar preguntas al Formulario</h4>
          <form action="<?php echo $urlFormulario?>" method="post">
            <input type="hidden" name="idFormulario" value="<?php echo $formulario->idFormulario?>" />
            <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
            <div style="margin:10px">
              <h5>Nombre: <?php echo $formulario->nombre?></h5>
              <h5>Título: <?php echo $formulario->titulo?></h5>
              <h5>Carrera: <?php echo "$carrera->nombre (Plan: $carrera->plan)"?></h5>
              <h5>Descripción: <?php echo $formulario->descripcion?></h5>
              <h5>Preguntas adicionales: <?php echo $formulario->preguntasAdicionales?></h5>
            </div>
            <?php foreach ($secciones as $itemSeccion): ?>
              <div class="row-fluid">
                <div class="span12 Secciones">
                  <h5 class="separador"><?php echo $itemSeccion['seccion']->texto?></h5>
                  <h6><?php echo $itemSeccion['seccion']->descripcion?></h6>
                  <?php 
                  foreach ($itemSeccion['subsecciones'] as $subseccion){
                    $docente = $subseccion['docente'];
                    printf('
                    <h3>%s %s</h3>', $docente->nombre, $docente->apellido);
                    if ($docente->idImagen != '') echo '<img src="'.site_url('usuarios/imagen/'.$docente->idImagen).'" width="100" height="100" alt="Imagen de docente"/>';

                    foreach ($subseccion['items'] as $k => $i){
                      $item = &$i['item'];
                      $opciones = &$i['opciones'];
                      printf('
                            <p>%s</p>
                      ', $item->texto, $item->idPregunta);
    
                    }//foreach items

                    echo '
                    <li class="Seccion">
                      <div class="btn-group">
                        <a class="nuevaPregunta" title="Agregar pregunta..." href="#"><i class="icon-circle-plus"></i></a>
                      </div>
                      <input type="hidden" name="idSeccion" value="'.$itemSeccion['seccion']->idSeccion.'" />
                      <h4 class="texto">Agregar preguntas</h4>
                      <ul class="Preguntas"></ul>
                    </li >
                    ';

                  }//foreach subsecciones
                  ?>
                </div>
              </div>
            <?php endforeach?>

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
  
  <!-- ventana modal para agregar una pregunta -->
  <div id="modalAgregarPregunta" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Agregar pregunta</h3>
    </div>
    <div class="modal-body">
      <label>Buscar pregunta: <span class="opcional">*</span></label>
      <input class="input-block-level" id="buscarPregunta" name="buscarPregunta" type="text" autocomplete="off" data-provide="typeahead" >
      <input type="hidden" name="idMateria" value=""/>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      <button class="btn btn-primary agregarPregunta">Agregar</button>
    </div>
  </div>
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
  <script src="<?php echo base_url('js/autocompletar.js')?>"></script>
  <script src="<?php echo base_url('js/personalizar-formularios.js')?>"></script>
  <script>
    autocompletar_pregunta($('#buscarPregunta'), "<?php echo site_url('preguntas/buscarAjax')?>");
    personalizar_formulario("<?php echo $formulario->preguntasAdicionales?>");
  </script>
</body>
</html>