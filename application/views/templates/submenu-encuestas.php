<div class="well well-small">
  <h4>Navegación</h4>
  <ul class="nav nav-pills nav-stacked">      
    <li <?php if($item_submenu==1) echo'class="active"'?>><a href="<?php echo site_url("encuestas/listar")?>">Encuestas realizadas</a></li>
    <li <?php if($item_submenu==2) echo'class="active"'?>><a href="<?php echo site_url("claves/ver")?>">Claves de acceso</a></li>
  </ul>
</div>
