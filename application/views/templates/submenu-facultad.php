<div class="well well-small">
  <h4>Navegación</h4>
  <ul class="nav nav-pills nav-stacked">      
    <li <?php if($item_submenu==1) echo'class="active"'?>><a href="<?php echo site_url("departamentos")?>">Departamentos</a></li>
    <li <?php if($item_submenu==2) echo'class="active"'?>><a href="<?php echo site_url("carreras")?>">Carreras</a></li>
    <li <?php if($item_submenu==3) echo'class="active"'?>><a href="<?php echo site_url("materias")?>">Materias</a></li>
  </ul>
</div>