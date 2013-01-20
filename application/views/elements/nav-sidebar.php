<div class="panel">
  
  <!-- Menú de navegación -->
  <ul class="nav-bar vertical">
    <li class="has-flyout">
      <a href="#">Alumnos</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#">Encuestas</a></li>
        <li><a href="#">Consultar estado</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Facultad</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="<?php echo site_url("departamentos")?>">Departamentos</a></li>
        <li><a href="<?php echo site_url("carreras")?>">Carreras</a></li>
        <li><a href="<?php echo site_url("materias")?>">Materias</a></li>
        <li><a href="#">Docentes</a></li>
        <li><a href="#">Autoridades</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Encuestas</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#">Formularios</a></li>
        <li><a href="#">Preguntas</a></li>
        <li><a href="#">Claves de acceso</a></li>
        <li><a href="#">Devoluciones</a></li>
      </ul>
    </li>
    <li class="has-flyout">
      <a href="#">Resultados</a>
      <a href="#" class="flyout-toggle"><span> </span></a>
      <ul class="flyout">
        <li><a href="#">Facultad</a></li>
        <li><a href="#">Departamentos</a></li>
        <li><a href="#">Carreras</a></li>
        <li><a href="#">Materias</a></li>
      </ul>
    </li>
    <li><a href="#">Enlaces</a></li>
    <li><a href="#">Contacto</a></li>
  </ul>
  
  <!-- Formulario de Login -->
  <?php include 'login-form.php'?>
  
</div>
