<html>
<body>
	<h1>Activar cuenta para <?php echo $identity;?></h1>
	<p>Por favor haga click en este link para <?php echo anchor('auth/activate/'. $id .'/'. $activation, 'Activar su Cuenta');?>.</p>
</body>
</html>