<?php
include_once("common.php");

$group = null;

if ( !$_SESSION['user']->isRegistered() ) {
	header("location:login.php?origin=".urlencode( currentURL() ) );
}

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

if ( isset( $_GET['id'] ) ) {	
	$group = Group::loadFromMD5Key( $_GET['id'] ); 
	if ( !$group ) {
		header("location:index.php");
		return;
	}
}

$anex = "";

if ( $_SESSION['user']->getID() != $group->getCreator() && ! $_SESSION['user']->isAdmin() ) {
	header("location:index.php");	
	return;
}


function miembros( $group ) {
	$users = $group->getMembers();
	if ( sizeof( $users )  == 0 ) return;
?>
		<div class="list-group">
			<span class="list-group-item active" >
			<h4 class="list-group-item-heading">Miembros</h4>
			</span>
<?php	
	foreach ( $users as $user ) {
		$linkstats = "trabajo.php?user=".$user->getMD5Key();
		$linkprofile = "perfil.php?id=".$user->getMD5Key();
		?>
				<div class="list-group-item">
					<div class="form-group text-info">
						<a href="perfil.php?id=<?php echo $user->getMD5Key(); ?>"><?php echo $user->getName(); ?></a>
						<div class="btn-group pull-right">
							<a class="btn btn-default" href="<?php echo $linkprofile; ?>"><span class="glyphicon glyphicon-user" data-toggle="tooltip" data-placement="bottom" title="Perfil de usuario del miembro"></span></a>
							<a class="btn btn-default" href="<?php echo $linkstats; ?>"><span class="glyphicon glyphicon-stats" data-toggle="tooltip" data-placement="bottom" title="Estadísticas del miembro"></span></a>
						</div>
					</div>
				</div>
		<?php
	}
	?>
		</div>
		<?php
}


makeHeader("Descubre la Programación : Grupos ", "Gestiona grupos de alumnos", "grupos", false);

?>
<div class="container-fluid">
	<div class="col-md-12">
		<h2><?php echo $group->getName().$anex; ?></h2>
	</div>
	<div class="col-md-12">
	
		<div class="panel panel-primary">
		
			<?php
			miembros( $group );
			?>
		</div>
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
