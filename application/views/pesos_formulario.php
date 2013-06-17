<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title><?php echo $tituloFormulario.' - '.NOMBRE_SISTEMA?></title>
  <style>
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
          <h4><?php echo $tituloFormulario?></h4>
          <form action="<?php echo $urlFormulario?>" method="post">
            <input type="hidden" name="idFormulario" value="<?php echo $formulario->idFormulario?>" />
            <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
            <input type="hidden" name="pesos" value="1" />
            <div style="margin:10px">
              <h6>Nombre del formulario: <?php echo $formulario->nombre?></h6>
              <h6>Título del formulario: <?php echo $formulario->titulo?></h6>
              <h6>Carrera: <?php echo "$carrera->nombre (Plan: $carrera->plan)"?></h6>
              <h6>Preguntas adicionales: <?php echo $formulario->preguntasAdicionales?></h6>
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
                        <div class="row-fluid">
                          <div class="span9">
                            <p>%s</p>
                          </div>
                          <div class="span3">
                            <input class="input-block-level" type="number" name="peso_%d_%d" min="0" max="1" step="0.01" value="%s" required %s />
                          </div>
                        </div>
                      ', $item->texto, $item->idItem, $item->idSeccion, (($item->importancia || $item->modoIndice==MODO_INDICE_NULO)?round($item->importancia,2):'1'), ($item->modoIndice==MODO_INDICE_NULO)?'disabled':'');
                    }//foreach items
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
  
  <!-- Le javascript -->
  <script src="<?php echo base_url('js/bootstrap-transition.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-modal.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-collapse.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-dropdown.min.js')?>"></script>
  <script src="<?php echo base_url('js/bootstrap-alert.min.js')?>"></script>
</body>
</html>