<html>
<body>
	<h1>Reiniciar contraseña para <?php echo $identity;?></h1>
	<p>Por favor haga click en este link para <?php echo anchor('usuarios/resetearContrasena/'. $forgotten_password_code, 'Resetear su Contraseña');?>.</p>
</body>
</html>