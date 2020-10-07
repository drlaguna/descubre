<?php
	include_once("../common.php");

	if ( !isset( $_POST['service'] ) ) {
		return;
	}
	if ( $_POST['service'] == "register" ) {		if ( !isset($_POST['program'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe indicar el identificador del programa" }';
			return;
		}
		if ( !isset($_POST['what'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe indicar el identificador el evento" }';
			return;
		}
		registerEvent( $_SESSION['user']->getID(), $_POST['program'], $_POST['what'] );
		echo '{ "id" : "1", "msg" : "Evento registrado" }';
		return;
	}

?>