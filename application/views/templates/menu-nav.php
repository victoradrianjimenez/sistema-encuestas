<div class="navbar navbar-inverse navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
      <a type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo site_url()?>">Sistema Encuestas</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Facultad <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo site_url("departamentos")?>">Departamentos, Carreras y Materias</a></li>
              <li><a href="<?php echo site_url("usuarios")?>">Docentes y Autoridades</a></li>               
            </ul>
          </li>
          <li><a href="<?php echo site_url("encuestas/listar")?>">Encuestas</a></li>
          <li><a href="<?php echo site_url("formularios")?>">Formularios</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Resultados <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="nav-header">Informes</li>
              <li><a href="<?php echo site_url('informes/materia')?>">Encuestas realizadas</a></li>
              <li><a href="<?php echo site_url('historicos/materia')?>">Informes históricos</a></li>
              <li><a href="<?php echo site_url('informes/clave')?>">Respuestas individuales</a></li>
              <li class="divider"></li>
              <li><a href="<?php echo site_url('devoluciones')?>">Planes de mejora</a></li>
            </ul>
          </li>
          <!-- <li><a href="#">Enlaces</a></li> -->
        </ul>
        <ul class="nav pull-right">
          <?php if(!(isset($usuarioLogin) && is_object($usuarioLogin))): ?>
            <li><a href="#LoginModal" role="button" data-toggle="modal">Iniciar Sesión</a></li>
          <?php else: ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $usuarioLogin->username?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('usuarios/modificarCuenta')?>">Datos de usuario</a></li>
                <li class="divider"></li>
                <li>
                  <a class="form"><form id="logout" action="<?php echo site_url("usuarios/logout")?>" method="post">
                    <input class="btn btn-link" type="submit" name="submit" value="Cerrar sesión"/>
                  </form></a>
                </li>
              </ul>
            </li>
          <?php endif ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
<?php 
if($resultadoOperacion){
  echo '
  <div class="alert '.$resultadoTipo.'">
    <button type="button" class="close" data-dismiss="alert">×</button>
    '.$resultadoOperacion.'
  </div>
  ';
}
if(!(isset($usuarioLogin) && is_object($usuarioLogin)))
  include 'modal-login.php';
?>