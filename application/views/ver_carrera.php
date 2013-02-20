<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'templates/head.php'?>
  <title>Ver carrera</title>
  <script src="<?php echo base_url('js/bootstrap-typeahead.js')?>"></script>
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
      
      <!-- Main -->
      <div class="row">
        <!-- SideBar -->
        <div class="span3" id="menu">
          <h4>Navegación</h4>
          <ul class="nav nav-pills nav-stacked">      
            <li><a href="<?php echo site_url("departamentos")?>">Departamentos</a></li>
            <li class="active"><a href="<?php echo site_url("carreras")?>">Carreras</a></li>
            <li><a href="<?php echo site_url("materias")?>">Materias</a></li>
          </ul>
        </div>
        
        <!-- Main -->
        <div class="span9">
          <h4><?php echo $carrera->nombre?> - Plan <?php echo $carrera->plan?></h4>
          <h4>Director de carrera: <?php echo $director->nombre.' '.$director->apellido?></h4>
          <h5><?php echo $departamento->nombre?></h5>
          <?php if(count($lista)== 0):?>
            <p>No se encontraron materias.</p>
          <?php else:?>
            <table class="table table-bordered table-striped">
              <thead>
                <th>Materia</th>
                <th>Código</th>
                <th>Acciones</th>
              </thead>
              <?php foreach($lista as $item): ?>  
                <tr>
                  <td class="nombre"><?php echo $item->nombre?></a></td>
                  <td class="codigo"><?php echo $item->codigo?></td>
                  <td><a class="quitar" href="#" title="Quitar asociación de la materia con la carrera" value="<?php echo $item->idMateria?>">Quitar</a></td>
                </tr>
              <?php endforeach ?>
            </table>
          <?php endif ?>
          <?php echo $paginacion ?>
  
          <!-- Botones -->
          <div class="">
            <button class="btn btn-primary" href="#modalModificar" role="button" data-toggle="modal">Modificar carrera...</button>
            <button class="btn btn-primary" href="#modalAsociar" role="button" data-toggle="modal">Asociar materia...</button>
          </div>
        </div>
      </div>
    </div>
    <div id="push"></div><br />
  </div>
  <?php include 'templates/footer.php'?>  
  
  <!-- ventana modal para editar datos de la carrera -->
  <div id="modalModificar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Editar carrera</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('carreras/modificar')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-editar-carrera.php'?>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- ventana modal para asociar materias a la carrera -->
  <div id="modalAsociar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Asociar materia</h3>
    </div>
    <form class="form-horizontal" action="<?php echo site_url('carreras/asociarMateria')?>" method="post">
      <div class="modal-body">
        <?php include 'templates/form-asociar-materia.php'?>      
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
        <input class="btn btn-primary" type="submit" name="submit" value="Aceptar" />
      </div>
    </form>
  </div>
  
  <!-- ventana modal para desasociar materias a la carrera -->
  <div id="modalDesasociar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Desasociar materia</h3>
    </div>
    <form action="<?php echo site_url('carreras/desasociarMateria')?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="idCarrera" value="<?php echo $carrera->idCarrera?>" />
        <input type="hidden" name="idMateria" value="" />
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
    $('.quitar').click(function(){
      idMateria = $(this).attr('value');
      nombre = $(this).parentsUntil('tr').parent().find('.nombre').text();
      codigo = $(this).parentsUntil('tr').parent().find('.codigo').text();
      //cargo el id de la materia en el formulario
      $('#modalDesasociar input[name="idMateria"]').val(idMateria);
      //pongo el nombre de la materia en el dialogo
      $("#modalDesasociar").find('.nombre').html(nombre+" ("+codigo+")");
      $("#modalDesasociar").modal();
      return false;
    });
    //abrir automaticamente la ventana modal que contenga entradas con errores
    $('span.label-important').parentsUntil('.modal').parent().first().modal();
  </script>
</body>
</html>