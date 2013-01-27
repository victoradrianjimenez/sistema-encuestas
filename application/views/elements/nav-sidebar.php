<div class="panel">
  
  <!-- Menú de navegación -->
  <ul class="nav-bar vertical">
    <li class="has-flyout">
      <a href="#">Alumnos</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("claves/ingresar")?>">Encuestas</a></li>
        <li><a href="#">Consultar estado</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Gestión Facultad</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("departamentos")?>">Departamentos</a></li>
        <li><a href="<?php echo site_url("carreras")?>">Carreras</a></li>
        <li><a href="<?php echo site_url("materias")?>">Materias</a></li>
        <li><a href="<?php echo site_url("personas")?>">Docentes y Autoridades</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Gestión Encuestas</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("encuestas")?>">Encuestas realizadas</a></li>
        <li><a href="#"><i>Claves de acceso</i></a></li>
        <li><a href="#"><i>Devoluciones</i></a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Formularios</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#"><i>Formularios</i></a></li>
        <li><a href="#"><i>Preguntas</i></a></li>
      </ul>      
    </li>
    <li class="has-flyout">
      <a href="#">Resultados</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#"><i>Facultad</i></a></li>
        <li><a href="#"><i>Departamentos</i></a></li>
        <li><a href="#"><i>Carreras</i></a></li>
        <li><a href="#"><i>Materias</i></a></li>
      </ul>
    </li>
    <li><a href="#">Enlaces</a></li>
    <li><a href="#">Contacto</a></li>
  </ul>
  
  <!-- Formulario de Login -->
  <?php include 'login-form.php'?>
  
</div>
