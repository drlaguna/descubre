<?php
	include_once("../common.php");

	function isValidMd5($md5 ='') {
	  return strlen($md5) == 32 && ctype_xdigit($md5);
	}		

	if ( !isset( $_POST['service'] ) ) {
		return;
	}
	if ( $_POST['service'] == "agreed" ) {
		if ( ! isValidMd5($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}	
		$user = User::agreed( $_POST['id'] );
		echo '{ "id" : "1", "msg" : "Datos de usuario modificados" }';
	}
	

?>