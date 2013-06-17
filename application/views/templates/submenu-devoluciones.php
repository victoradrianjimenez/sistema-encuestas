<div class="well well-small">
  <h4>Opciones</h4>
  <ul class="nav nav-pills nav-stacked">      
    <li <?php if($item_submenu==3) echo'class="active"'?>><a href="<?php echo site_url("devoluciones/nueva")?>">Nuevo Plan de Mejoras</a></li>
    <li <?php if($item_submenu==1) echo'class="active"'?>><a href="<?php echo site_url("devoluciones/ver")?>">Ver Planes de Mejoras</a></li>
    <li <?php if($item_submenu==2) echo'class="active"'?>><a href="<?php echo site_url("devoluciones/listar")?>">Listar Planes de una Carrera</a></li>
  </ul>
</div>