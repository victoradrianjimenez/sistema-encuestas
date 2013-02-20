<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Lista Preguntas</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
</head>
<body>
  <div id="wrapper">
    <?php include 'templates/menu-nav.php'?>
    <div class="container">
      <div class="row">
        <!-- Titulo -->
        <div class="span12">
          <h3>Gestión de Formularios</h3>
          <p>---Descripción---</p>
        </div>
      </div>
      
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <h4>Navegación</h4>
          <ul class="nav nav-pills nav-stacked">      
            <li><a href="<?php echo site_url("formularios")?>">Formularios</a></li>
            <li class="active"><a href="<?php echo site_url("preguntas")?>">Preguntas</a></li>
          </ul>
        </div>
  
        <!-- Main -->
        <div class="span9">
          <h4>Preguntas</h4>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron preguntas.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Texto</th>
                <th>Creacion</th>
                <th>Tipo</th>
                <th>Obligatoria</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td><a class="texto" href="<?php echo site_url('preguntas/ver/'.$item['pregunta']->idPregunta)?>"/><?php echo $item['pregunta']->texto?></a></td>
                  <td class="creacion"><?php echo $item['pregunta']->creacion?></td>
                  <td class="tipo"><?php echo $item['pregunta']->tipo?></td>
                  <td class="obligatoria"><?php echo $item['pregunta']->obligatoria?></td>
                  <td>
                    <a class="eliminar" href="#" value="<?php echo $item['pregunta']->idPregunta?>">Eliminar</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo site_url('preguntas/nueva')?>">Agregar pregunta</a>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>
  
  <!-- ventana modal para eliminar preguntas -->
  <div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Eliminar pregunta</h3>
    </div>
    <form action="<?php echo site_url('preguntas/eliminar')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idPregunta" value="" />
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
  <script>
    $('.eliminar').click(function(){
      idPregunta = $(this).attr('value');
      texto = $(this).parentsUntil('tr').parent().find('.texto').text();
      //cargo el id de la pregunta en el formulario
      $('#modalEliminar input[name="idPregunta"]').val(idPregunta);
      //pongo el texto de la pregunta en el dialogo
      $("#modalEliminar").find('.texto').html(texto);
      $("#modalEliminar").modal();
      return false;
    });
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>