
<?php
	include_once("common.php");
	
	if ( !isset( $_GET['id'] ) ) {
		header("location:index.php");
	}
	
	$res = User::activate( $_GET['id'] );
	if ( $res == -1 ) {
		header("location:index.php");
	}

	makeHeader("Descubre la Programación : Activación de usuario ", "", "usuario", false);
?>

<div class="container-fluid">
	<div class="col-md-6 col-md-offset-3">

<?php		if ( $res == -2 ) { ?>
<div class="well">
		Error, identificador de activación ya utilizado. Por favor, vuelve a intentar activar el usuario.
</div>
<?php 	}										?>

<?php		if ( $res == 1 ) { ?>
<div class="well">
		Usuario activado, accede al sistema pinchando aquí <a class="btn btn-default" href="login.php">Acceder</a>
</div>
		
<?php 	}										?>

<?php
	makeFooter();
?>
	</div>
</div>
</body>
<script>
</script>
</html>
