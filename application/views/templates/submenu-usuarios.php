<div class="well well-small">
  <h4>Grupos</h4>
  <ul class="nav nav-pills nav-stacked">      
    <li class="<?php if (!isset($grupo))echo 'active'?>"><a href="<?php echo site_url("usuarios")?>" href="#">Todos los usuarios</a></li>
    <li class="<?php if (isset($grupo))echo($grupo->name=="decanos")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDecanos")?>">Decano</a></li>
    <li class="<?php if (isset($grupo))echo($grupo->name=="jefes_departamentos")?'active':''?>"><a href="<?php echo site_url("usuarios/listarJefesDepartamentos")?>">Jefes de departamento</a></li>
    <li class="<?php if (isset($grupo))echo($grupo->name=="directores")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDirectores")?>">Directores de carrera</a></li>
    <li class="<?php if (isset($grupo))echo($grupo->name=="docentes")?'active':''?>"><a href="<?php echo site_url("usuarios/listarDocentes")?>">Docentes</a></li>
  </ul>
</div>