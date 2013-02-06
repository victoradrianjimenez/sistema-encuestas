<div class="panel">
  
  <!-- Menú de navegación -->
  <ul class="nav-bar vertical">
    <li class="has-flyout">
      <a href="#">Alumnos</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("claves/ingresar")?>">Encuestas</a></li>
        <li><a href="#"><i>Consultar estado</i></a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Gestión Facultad</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("departamentos")?>">Departamentos</a></li>
        <li><a href="<?php echo site_url("carreras")?>">Carreras</a></li>
        <li><a href="<?php echo site_url("materias")?>">Materias</a></li>
        <li><a href="<?php echo site_url("usuarios")?>">Docentes y Autoridades</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Gestión Encuestas</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("encuestas")?>">Encuestas realizadas</a></li>
        <li><a href="#"><i>Claves de acceso</i></a></li>
        <li><a href="<?php echo site_url("devoluciones")?>">Devoluciones</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Formularios</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("formularios")?>">Formularios</a></li>
        <li><a href="<?php echo site_url("preguntas")?>">Preguntas</a></li>
      </ul>      
    </li>
    <li class="has-flyout">
      <a href="#">Informes Encuestas</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#"><i>Facultad</i></a></li>
        <li><a href="#"><i>Departamentos</i></a></li>
        <li><a href="#"><i>Carreras</i></a></li>
        <li><a href="<?php echo site_url('encuestas/informeMateria')?>"><i>Materias</i></a></li>
      </ul>
    </li>
    <li><a href="<?php echo site_url("usuarios/modificar")?>">Datos de usuario</a></li>
    <li><a href="#">Enlaces</a></li>
    <li><a href="#">Contacto</a></li>
  </ul>
  
  <!-- Formulario de Login -->
  <?php include 'login-form.php'?>
  
</div>
