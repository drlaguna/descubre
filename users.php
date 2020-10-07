<?php
include_once("common.php");

if ( ! $_SESSION['user']->isAdmin() ) {
	header("location:index.php" );
	return;
}

makeHeader("Descubre la Programación : Status ", "", "log", false);

function users() {
	$list = $_SESSION['bbdd']->queryArray("SELECT * FROM USER_2");
	$users = array();
	foreach ( $list as $row ) {
		$user = User::loadFromId( $row['ID'] );
		if ( ! $user ) continue;
		$users[] = $user;
	}
	return $users;
}

$users = users();

?>

<div class="container-fluid">
	<div class="col-md-12">
	</div>
	<div class="col-md-12">
	<ol>
	<?php 
		foreach ( $users as $user ) {
?>
		<li><?php echo $user->getName()." ".$user->getProfileLink()." ".trim($user->getEmail())." ".$user->getSignupdate(); if ( !$user->isActive() ) echo " <a href='activa.php?id=".$user->getLastActivationTicket()."'>activar</a>"; ?></li>
<?php			
		}
	 ?>
	 </ol>
	</div>

	<div class="col-md-4">
	</div>

	<div class="col-md-4">
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
