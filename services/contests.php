<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	include_once("../common.php");

	if ( !isset($_SESSION['user'] ) || !$_SESSION['user']->isRegistered() ) {
		echo '{ "id" : "-1", "msg" : "Usuario no registrado. Es posible que la sesión haya caducado. Abre otra ventana del navegador y accede al sistema.  Después vuelve a intentarlo." }';
		return;
	}

	if ( !isset( $_POST['service'] ) ) {
		echo '{ "id" : "-1", "msg" : "Servicio no especificado" }';
		return;
	}

	if ( $_POST['service'] == "save" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		if ( !isset( $_POST['title'] ) || $_POST['title'] == "" ) {
			echo '{ "id" : "-1", "msg" : "Por favor, pon un título al reto" }';
			return;
		}
		$contest = null;
		if ( $_POST['id'] == "-1" ) {
			$contest = new Contest( $_SESSION['user']->getID() );
			if ( !$contest ) {
				echo '{ "id" : "-1", "msg" : "Error al crear el reto" }';
			}
			$contest->save();
		} else {
			$contest = Contest::loadFromID( $_POST['id'] );
			if ( !$contest ) {
				echo '{ "id" : "-1", "msg" : "El reto no existe" }';
			}
		}
		// http://api.jquery.com/jquery.ajax/
		// JQuery about ajax: Data will always be transmitted to the server using UTF-8 charset; you must decode this appropriately on the server side."

		$contest->setTitle( htmlspecialchars( addslashes( utf8_decode($_POST['title']) ) ) );
		$contest->setDescription( htmlspecialchars( addslashes( utf8_decode($_POST['description']) ) ) );
		$contest->setGroup( $_POST['group'] );
		$contest->setVisibility( $_POST['visibility'] );
		$contest->setRanking( $_POST['ranking'] );
		$contest->setPassword( htmlspecialchars( addslashes( utf8_decode($_POST['password'] ) ) ) );

		$contest->setStart( $_POST['start'] );
		$contest->setEnd( $_POST['end'] );

		echo '{ "id" : "'.$contest->getID().'", "msg" : "Reto guardado" }';
		return;
	}

	if ( $_POST['service'] == "get-challenges" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		$contest = Contest::loadFromID( $_POST['id'] );
		if ( !$contest ) {
			echo '{ "id" : "-1", "msg" : "El reto '.$_POST['id'].' no existe" }';
			return;
		}
		$challenges = $contest->getChallenges();
		$cadena = "[";
		foreach ( $challenges as $challenge ) {
			$charray = array( "id" => $challenge->getID(), "title" => $challenge->getTitle());
			$cadena .= json_encode($charray).",";
		}
		if ( sizeof( $cadena > 1 ) ) {			
			$cadena = substr($cadena, 0, -1);
		}
		$cadena .= "]";
		echo '{ "id" : "1", "challenges" : '.$cadena.' }';
		return;
	}

	if ( $_POST['service'] == "update-challenges" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		$newChallenges = array();
		if ( isset( $_POST['challenges'] ) ) {
			$newChallenges = $_POST['challenges'];
		}
		$contest = Contest::loadFromID( $_POST['id'] );
		if ( !$contest ) {
			echo '{ "id" : "-1", "msg" : "El reto '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $contest->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "El reto sólo puedo ser modificado por su creador o el administrador" }';
			return;
		}
		// Borrar las asociaciones challenge-contest actuales que no tengan ya algún participante
		$challenges = $contest->getChallenges();
		foreach( $challenges as $challenge ) {
			$cc_id = $_SESSION['bbdd']->queryValue("SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$contest->getID()."' AND CHALLENGE='".$challenge->getID()."'");
			$nusers = $_SESSION['bbdd']->queryValue("SELECT COUNT(DISTINCT USER) FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE='".$cc_id."'");
			if ( $nusers == 0) {
				$_SESSION['bbdd']->exec("DELETE FROM CONTEST_CHALLENGE_2 WHERE ID='".$cc_id."'");
			}
		}
		// Hacer las nuevas asociaciones y modificar la posición de las actuales
		$i = 1;		
		foreach ( $newChallenges as $challenge ) {
			$cc = $_SESSION['bbdd']->query("SELECT * FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$contest->getID()."' AND CHALLENGE='".$challenge['id']."'");
			if ( $cc ) {
				// Actualizar posición
//				echo "Ya existe la asociación Reto ".$contest->getID()." -  Prueba ".$challenge['id']." y tiene asignada la posición ".$cc['POSITION'].". Ahora se le quiere asignar la posición ".$i.".";
				$_SESSION['bbdd']->query("UPDATE CONTEST_CHALLENGE_2 SET POSITION='".$i."' WHERE ID='".$cc['ID']."'");
			} else {
//				echo "Aún no existe la asociación Reto ".$contest->getID()." -  Prueba ".$challenge['id'].". Ahora se le quiere asignar la posición ".$i.".";			
				$ccid = $_SESSION['bbdd']->exec("INSERT INTO CONTEST_CHALLENGE_2 (CONTEST, CHALLENGE, POSITION) VALUES ('".$contest->getID()."', '".$challenge['id']."', '".$i."')");
				$_SESSION['bbdd']->query("UPDATE CONTEST_CHALLENGE_2 SET MD5_KEY=MD5( CONCAT(ID, CONTEST, CHALLENGE, NOW()) ) WHERE ID='".$ccid."'");
			}
			
			$i++;
		}
			
		/*
		$query = "DELETE FROM TESTCASE_2 WHERE ID='".$_POST['testid']."'";
		$_SESSION['bbdd']->exec($query);
		*/
		echo '{ "id" : "1", "msg" : "Reto actualizado" }';
		return;
	}

	if ( $_POST['service'] == "join" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		if ( !isset( $_POST['password'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el password del reto" }';
			return;
		}
		$contest = Contest::loadFromID( $_POST['id'] );
		if ( !$contest ) {
			echo '{ "id" : "-1", "msg" : "El reto '.$_POST['id'].' no existe" }';
			return;
		}
		if ( ! $contest->hasPassword() ) {
			echo '{ "id" : "1", "msg" : "El reto no requiere password" }';
			return;
		}
		if ( $_POST['password'] == md5( $contest->getPassword() ) ) {
			$contest->registerUser( $_SESSION['user'] );
			echo '{ "id" : "1", "msg" : "Usuario registrado con éxito" }';
		} else {
			echo '{ "id" : "-1", "msg" : "Password incorrecto" }';
		}
		return;
	}

	if ( $_POST['service'] == "leave" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		$contest = Contest::loadFromID( $_POST['id'] );
		if ( !$contest ) {
			echo '{ "id" : "-1", "msg" : "El reto '.$_POST['id'].' no existe" }';
			return;
		}
		if ( ! $contest->hasPassword() ) {
			echo '{ "id" : "1", "msg" : "El reto no requiere password" }';
			return;
		}
		$contest->deregisterUser( $_SESSION['user'] );
		echo '{ "id" : "1", "msg" : "Usuario deregistrado con éxito" }';
		return;
	}

	if ( $_POST['service'] == "delete" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del reto" }';
			return;
		}
		$contest = Contest::loadFromID( $_POST['id'] );
		if ( !$contest ) {
			echo '{ "id" : "-1", "msg" : "El reto '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $contest->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "El reto sólo puedo ser borrado por su creador o el administrador" }';
			return;
		}
		$registered_users = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CONTEST_USER_2 WHERE CONTEST='".$_POST['id']."'");
		if ( $registered_users ) {
			echo '{ "id" : "-1", "msg" : "No se puede borrar el reto porque ya tiene usuarios inscritos. Puede modificar su visibilidad haciéndolo privado para que no aparezca en la pestaña Retos." }';
			return;
		}
		$programs = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE IN ( SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$_POST['id']."' )");
		if ( $programs ) {
			echo '{ "id" : "-1", "msg" : "No se puede borrar el reto porque '.$programs.' usuarios ya han empezado a resolver alguna de las pruebas. Puede modificar su visibilidad haciéndolo privado para que no aparezca en la pestaña Retos." }';
			return;
		}
		Contest::remove( $contest );
		echo '{ "id" : "1", "msg" : "Reto borrado" }';
		return;
	}


?>