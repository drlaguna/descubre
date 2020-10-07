<?php
include_once("common.php");

function listado($programs) {
	foreach ( $programs as $p ) {
		$program = Program::loadFromMD5Key( $p['MD5_KEY'] );
		if ( !$program ) {
			echo "Fallo al cargar id ".$p['MD5_KEY']."<br/>";
			continue;
		}
		if ( $program->getVisibility() == USABLE ) {
			$link = 'muestra.php?id='.$program->getMD5Key();
		} else {
			$link = 'editor-codigo.php?id='.$program->getMD5Key();
		}
		$fechac = explode(" ", $program->getCreation());
		$fecham = explode(" ", $program->getVersionCreation());
		
?>
		<div class="list-group">
			<a href="<?php echo $link; ?>" class="list-group-item active">
				<h4 class="list-group-item-heading"><?php echo $program->getTitle(); ?></h4>
			</a>
			<p class="list-group-item">
				<strong>
				<?php echo $program->getCreatorLink(); ?>
				</strong>

					
					<span class="glyphicon glyphicon-refresh pull-right" aria-hidden="true" title="modificado">&nbsp;<?php echo $fecham[0]; ?></span>
					&nbsp;
				<div class="form-group text-info list-group-item">
					<span class="glyphicon glyphicon-ok" aria-hidden="true" title="votos"></span> <?php echo $program->getVotes(); ?> votos
					&nbsp;
					<span class="glyphicon glyphicon-download" aria-hidden="true" title="descargas"></span> <?php echo $program->getViews(); ?> descargas
					&nbsp;
					<span class="glyphicon glyphicon-play" aria-hidden="true" title="usos"></span> <?php echo $program->getRuns(); ?> usos
				</div>
			</p>
		</div>
<?php
	}
}

makeHeader("Descubre la Programación : Explora ", "Explora los programas escritos por los demás usuarios", "explora", false);
$ammount = 25;
$start = 0;
$end = $ammount;
$total = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM PROGRAM_2 WHERE VISIBILITY IN (".PUBLICO.", ".USABLE.")");

if ( isset( $_GET['start'] ) && is_numeric( $_GET['start'] ) ) {
	$start = $_GET['start'];
}
if ( isset( $_GET['end'] ) && is_numeric( $_GET['end'] ) ) {
	$end = $_GET['end'];
}
$prev = $start-$ammount;
$post = $end + $ammount;
if ( $prev < 0 ) $prev = 0;
if ( $post > $total ) $post = $total;

$prevlink = "?start=".$prev."&end=".($prev+$ammount);
$nextlink = "?start=".$end."&end=".$post;

?>
<div class="container-fluid">
	<div class="page-header">
	  <h1>Explora <small>los programas escritos por los demás usuarios. Inspírate explorando el código y viendo sus resultados</small></h1>
	</div>
	<div class="row">
	<div class="col-md-6">
	<h3>Los más recientes</h3>
	<?php
		$programs = $_SESSION['bbdd']->queryArray("SELECT p.*, s.DATE FROM PROGRAM_2 as p, SOURCE_CODE_2 as s WHERE p.CURRENT_VERSION=s.ID AND VISIBILITY in (".PUBLICO.", ".USABLE.") ORDER BY s.DATE DESC LIMIT ".$start.",".$end);

		listado($programs);
	?>
	</div>
	<div class="col-md-6">
	<h3>Los más votados</h3>
	<?php
		$programs = $_SESSION['bbdd']->queryArray("SELECT * FROM PROGRAM_2 WHERE VISIBILITY in (".PUBLICO.", ".USABLE.") ORDER BY VOTES DESC LIMIT ".$start.",".$end);
		listado($programs);
	?>
	</div>
	<!--
	<div class="col-md-4">
	<h3>Los más descargados</h3>
	<?php
		$programs = $_SESSION['bbdd']->queryArray("SELECT * FROM PROGRAM_2 WHERE VISIBILITY in (".PUBLICO.", ".USABLE.") ORDER BY VIEWS DESC LIMIT ".$start.",".$end);
		listado($programs);
	?>
	</div>
	-->
	</div>
	<div class="row">
	<nav>
	  <ul class="pager">	  
	    <li><a href="<?php echo $prevlink; ?>">Anteriores</a></li>
	    <li><a href="<?php echo $nextlink; ?>">Siguientes</a></li>
	  </ul>
	</nav>
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
