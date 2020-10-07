<?php
include_once("common.php");

function listado( ) {
	$contests = $_SESSION['bbdd']->queryArray("SELECT * FROM CONTEST_2 WHERE (END > NOW() OR END = '0000-00-00') AND VISIBILITY='".PUBLICO."' AND ( GROUP_ID=1 OR (GROUP_ID IN (SELECT GROUP_ID FROM GROUP_USER_2 WHERE USER='".$_SESSION['user']->getID()."'))) ORDER BY END ASC");
	foreach ( $contests as $c ) {
		$contest = Contest::loadFromID( $c['ID'] );
		$title = $contest->getTitle();
		$description = str_replace( "\n", "<br/>", $contest->getDescription());
		if ( $contest->hasPassword() ) {
			$title .= '<span class="glyphicon glyphicon-lock pull-right" data-toggle="tooltip" data-placement="bottom" title="Este reto requiere inscripción previa"></span>';
		}
		$start = strtotime( $contest->getStart() );
		$end = strtotime( $contest->getEnd() );
//		$users = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE IN ( SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$contest->getID()."' )");
		$users = $_SESSION['bbdd']->queryValue("SELECT COUNT(DISTINCT USER) FROM CC_TRY_2 WHERE CONTEST='".$contest->getID()."'");
		$color = "list-group-item-warning";
		$link = 'reto.php?id='.$contest->getMD5Key();
		if ( $contest->isOpen() ) {
			$color = "list-group-item-primary";
		} else {
			if ( $end > 0 && $end < strtotime( date("Y-m-d H:i:s") ) ) {
				$color = "list-group-item-danger";
			}
		}
		
?>
		<div class="list-group">
			<a href="<?php echo $link; ?>" class="list-group-item active <?php echo $color; ?>">
				<h4 class="list-group-item-heading"><?php echo $title; ?></h4>
			</a>
			<p class="list-group-item">
				<?php echo $description; ?>
				<div class="form-group text-info list-group-item">
<?php	if ( $start > 0 ) {	?>
					<span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Fecha de inicio del reto"> <?php echo date2Human( $contest->getStart() ); ?></span>
<?php	}					?>
<?php	if ( $end > 0 ) {	?>
					<span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Fecha de fin del reto"> <?php echo date2Human( $contest->getEnd() ); ?></span>
<?php	}					?>
					&nbsp;

<?php	if ( $users > 0 ) {	?>					
					<span class="glyphicon glyphicon-user pull-right" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="participantes"> <?php echo $users; ?></span> 
<?php	}					?>
				</div>
			</p>
		</div>
<?php
	}
}

makeHeader("Descubre la Programación : Retos ", "Tenemos muchos retos esperándote, resuélvelos todos", "retos", false);
?>
<div class="container-fluid">
	<div class="page-header">
	  <h1>Resuelve <small> los retos planteados y participa en los concursos organizados por la Facultad de Informática de la Universidad de Murcia</small></h1>
	</div>
	<div class="col-md-12">
		<?php
			listado( PUBLICO );
		?>
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
