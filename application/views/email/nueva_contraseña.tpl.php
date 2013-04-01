<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambio de contraseña - <?php echo NOMBRE_SISTEMA?></title>
  <style>
    body{padding:10px;}
    html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}
    a:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;}
    a:hover,a:active{outline:0;}
    @media print{*{text-shadow:none !important;color:#000 !important;background:transparent !important;box-shadow:none !important;} a,a:visited{text-decoration:underline;} a[href]:after{content:" (" attr(href) ")";} abbr[title]:after{content:" (" attr(title) ")";} .ir a:after,a[href^="javascript:"]:after,a[href^="#"]:after{content:"";} pre,blockquote{border:1px solid #999;page-break-inside:avoid;} thead{display:table-header-group;} tr,img{page-break-inside:avoid;} img{max-width:100% !important;} @page {margin:0.5cm;}p,h2,h3{orphans:3;widows:3;} h2,h3{page-break-after:avoid;}}body{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;color:#333333;background-color:#ffffff;}
    a{color:#0088cc;text-decoration:none;}
    a:hover,a:focus{color:#005580;text-decoration:underline;}
    .container{margin-right:auto;margin-left:auto;*zoom:1;}.container:before,.container:after{display:table;content:"";line-height:0;}
    .container{width:auto;}
    p{margin:0 0 10px;}
    .text-left{text-align:left;}
    .text-right{text-align:right;}
    .text-center{text-align:center;}
    h1,h2,h3,h4,h5,h6{margin:10px 0;font-family:inherit;font-weight:bold;line-height:20px;color:inherit;text-rendering:optimizelegibility;}h1 small,h2 small,h3 small,h4 small,h5 small,h6 small{font-weight:normal;line-height:1;color:#999999;}
    h1,h2,h3{line-height:40px;}
    h1{font-size:38.5px;}
    h2{font-size:31.5px;}
    h3{font-size:24.5px;}
    h4{font-size:17.5px;}
    h5{font-size:14px;}
    h6{font-size:11.9px;}
    ul,ol{padding:0;margin:0 0 10px 25px;}
    li{line-height:20px;}
    .well{min-height:20px;padding:19px;margin-bottom:20px;background-color:#f5f5f5;border:1px solid #e3e3e3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);-moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);}.well blockquote{border-color:#ddd;border-color:rgba(0, 0, 0, 0.15);} 
  </style>
</head>
<body>
  <div class="container">
    <div class="well">  
      <h3>Cambio de contraseña para <?php echo NOMBRE_SISTEMA?></h3>
      <p>Estimado(a) usuario(a):</p>
      <p>Este mensaje le llega porque <b>se cambió su contraseña</b> para ingresar al <?php echo NOMBRE_SISTEMA?> de la <?php echo NOMBRE_FACULTAD?>.</p>
      <p>Sus nuevos datos son:</p>
      <ul>
        <li>Nombre de usuario: <?php echo $identity?></li>
        <li>Nueva contreseña: <?php echo $new_password?></li>
      </ul>
      <p>Nota: <b>no responda este mensaje.</b> Se ha enviado desde una dirección de correo no supervisada. No se responderá al correo enviado a esta dirección.</p>
    </div>
  </div>    
</body>
</html>