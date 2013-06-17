<div class="well well-small">
  <h4>Tipo de informe</h4>
  <ul class="nav nav-pills nav-stacked">      
    <li <?php if($item_submenu==1) echo'class="active"'?>><a href="<?php echo site_url("informes/materia")?>">Materia</a></li>
    <li <?php if($item_submenu==2) echo'class="active"'?>><a href="<?php echo site_url("informes/carrera")?>">Carrera</a></li>
    <li <?php if($item_submenu==3) echo'class="active"'?>><a href="<?php echo site_url("informes/departamento")?>">Departamento</a></li>
    <li <?php if($item_submenu==4) echo'class="active"'?>><a href="<?php echo site_url("informes/facultad")?>">Facultad</a></li>
  </ul>
</div>