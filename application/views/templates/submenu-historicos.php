<div class="well well-small">
  <h4>Navegación</h4>
  <ul class="nav nav-pills nav-stacked">
    <li <?php if($item_submenu==1) echo'class="active"'?>><a href="<?php echo site_url("historicos/materia")?>">Materia</a></li>
    <li <?php if($item_submenu==2) echo'class="active"'?>><a href="<?php echo site_url("historicos/carrera")?>">Carrera</a></li>
    <li <?php if($item_submenu==3) echo'class="active"'?>><a href="<?php echo site_url("historicos/departamento")?>">Departamento</a></li>
    <li <?php if($item_submenu==4) echo'class="active"'?>><a href="<?php echo site_url("historicos/facultad")?>">Facultad</a></li>
  </ul>
</div>