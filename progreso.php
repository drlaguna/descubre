<?php
include_once("common.php");
$user = null;

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

if ( isset( $_GET['id'] ) ) {
	$_GET['id'] = mysql_real_escape_string($_GET['id']);
	$user = User::loadFromMD5Key( $_GET['id'] );
	if ( !$user ) {
		header("location:index.php");
		return;
	}
}

if ( $user->getID() != $_SESSION['user']->getID() && ! ($_SESSION['user']->isAdmin()) && !($_SESSION['user']->isTutorOf( $user ) )) {
	header("location:index.php");
	return;
}

if ( $user->getID() == $_SESSION['user']->getID() && !$publicProfile && !$user->hasAgreed() ) {
	header("location:rgpd.php?id=".$user->getMD5Key());
	return;
}

makeHeader("Descubre la Programación : Perfil ", "Consulta tus programas, tus datos y tu progreso", "perfil", false);
?>
<div class="container-fluid">
	<div class="col-md-4">
		<h4>Progreso por temas</h4>
		<?php $user->getKeyPoints(); ?>
	</div>
</div>

<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
