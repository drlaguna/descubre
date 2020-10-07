<?php
include_once("common.php");

makeHeader("Descubre la Programación", "Explora, aprende y crea tus propios programas", "aprende", false);
?>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="apage-header">
			<h1>Aprende <small>a programar desde cero, nuestro curso está organizado en sencillas lecciones que te irán enseñando la programación paso a paso.</small></h1>
		</div>
		<div class="list-group col-md-6">
			<a href="curso.php#tema0" class="list-group-item"> 0. Introducción</a>
			<a href="curso.php#tema1" class="list-group-item"> 1. Haciendo dibujos</a>
			<a href="curso.php#tema2" class="list-group-item"> 2. Moviendo los dibujos por la pantalla</a>
			<a href="curso.php#tema3" class="list-group-item"> 3. Simplificando las tareas repetitivas</a>
		</div>
		<div class="list-group col-md-6">
			<a href="curso.php#tema4" class="list-group-item"> 4. Haciendo que el ordenador decida</a>	
			<a href="curso.php#tema5" class="list-group-item"> 5. Usando listas y tablas</a>	
			<a href="curso.php#tema6" class="list-group-item"> 6. Controla tus personajes</a>	
			<a href="curso.php#tema7" class="list-group-item"> 7. Jugando con el texto</a>	
		</div>
	</div>

	<div class="col-md-12">
		<div class="apage-header">
		  <h1>Encuentra un tutor <small>y únete a sus grupos.</small></h1>
		</div>
		
		<div class="list-group col-md-12">
			<div class="list-group-item active">
				<h4 class="list-group-item-heading">Listado de tutores</h4>
			</div>
<?php
	$ids = $_SESSION['bbdd']->queryArray("SELECT ID FROM USER_2 WHERE ROLE='Profesor' AND ACTIVE='1' ORDER BY NAME ASC");		
	foreach ( $ids as $id ) {
		$tutor = User::loadFromID( $id['ID'] );
?>		
			<a href="perfil.php?id=<?php echo $tutor->getMD5Key(); ?>" class="list-group-item"><?php echo $tutor->getName()." ( ".$tutor->getCentre()." ) "; ?></a>	
<?php		
	}
?>
		</div>
	</div>

<!--
	<div class="jumbotron">
	  <h1>Aprende</h1>
	  <p>Nuestro curso está organizado en sencillas lecciones que te irán enseñando la programación paso a paso</p>
	  <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
	</div>
-->
			
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
