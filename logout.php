<?php
	include_once("common.php");
	
	registerEvent( $_SESSION['user']->getID(), -1, "logout" );
	$_SESSION['user'] = new User();

	if ( isset( $_GET['origin']) ) {
		header( "location:".urldecode( $_GET['origin'] ) );
	} else {
		header("location:index.php");
	}
?>