<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Claves de acceso - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    h2, h4{text-align:center}
    p{text-align:justify; font-size:9pt;}
    .clave{border-bottom: 1px dashed #000000;}
    .codigo{text-align:right;}
  </style>
</head>
<body>
<table cellpadding="5">
<?php foreach ($lista as $clave):?>
  <tr nobr="true"><td>
    <table cellspacing="2" class="clave">
      <tr><td colspan="2"><h2>Encuesta para mejorar la calidad de la enseñanza</h2></td></tr>
      <tr><td colspan="2"><h4><?php echo $carrera->nombre.' - '.$departamento->nombre?></h4></td></tr>
      <tr>
        <td class="materia"><b><?php echo $materia->nombre?></b></td>
        <td class="codigo"><b><?php echo $clave->clave?></b></td>
      </tr>
      <tr>
        <td colspan="2"><p>Esta clave es necesaria para completar la encuesta desarrollada por la <?php echo NOMBRE_FACULTAD?> - <?php echo NOMBRE_UNIVERSIDAD?>, destinada a mejorar la calidad de la enseñanza. 
          Para utilizarla deberá acceder a la dirección web <?php echo base_url()?>. 
          Esta clave sirve para responder sobre una asignatura en particular y dejará de tener validez al completar el formulario.
          Usted debería solicitar una clave distinta por cada asignatura que esté cursando en forma regular.</p>
        </td>
      </tr>
    </table></td>
  </tr>
<?php endforeach?>
</table>
</body>
</html>