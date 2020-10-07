<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	include_once("../common.php");

	function isValidMd5($md5 ='') {
	  return strlen($md5) == 32 && ctype_xdigit($md5);
	}		

	// Servicio que puede ejecutar cualquier aunque no esté registrado
	if ( $_POST['service'] == "get-code" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}
		$program = Program::loadFromID( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa no existe" }';
			return;
		}
		$cadena = array( "id" => "1", "msg" => "Here you are", "code" => utf8_encode($program->getCode())); 
//		echo '{ "id" : "1", "msg" : "Here you are", "code" : "'.$program->getCode().'" }';
		echo json_encode($cadena);
		return;
	}

	if ( !isset($_SESSION['user'] ) || !$_SESSION['user']->isRegistered() ) {
		echo '{ "id" : "-1", "msg" : "Usuario no registrado. Es posible que la sesión haya caducado. Abre otra ventana del navegador y accede al sistema. Después vuelve a intentarlo." }';
		return;
	}

	if ( !isset( $_POST['service'] ) ) {
		echo '{ "id" : "-1", "msg" : "Servicio no especificado" }';
		return;
	}

	if ( $_POST['service'] == "save" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}		
		if ( !isset( $_POST['title'] ) || $_POST['title'] == "" ) {
			echo '{ "id" : "-1", "msg" : "Por favor, pon un título al programa antes de guardarlo." }';
			return;
		}
		$user = $_SESSION['user'];
		if ( isset( $_POST['user'] ) ) {
			$user = User::loadFromID( $_POST['user'] );
		}
		$program = null;
		$version = false;
		if ( $_POST['id'] == "-1" ) {
			// Si es un programa nuevo se crea y guarda en BBDD
			$program = new Program( $user->getID() );
			if ( !$program ) {
				echo '{ "id" : "-1", "msg" : "Error al crear el programa" }';
			}
			$program->save();
		} else {
			// Si es un programa que ya existe se carga desde la BBDD
			$program = Program::loadFromID( $_POST['id'] );
			if ( !$program ) {
				echo '{ "id" : "-1", "msg" : "Error al crear la versión del programa" }';
			}
			// Si el autor del programa no es el usuario actual se trata de un versionado
			if ( $program->getCreator() != $user->getID() ) {
				$program = new Program( $user->getID(), $_POST['id'] );
				$program->save();
				$version = true;
			}
		}
		// http://api.jquery.com/jquery.ajax/
		// JQuery about ajax: Data will always be transmitted to the server using UTF-8 charset; you must decode this appropriately on the server side."

		// En cualquier caso se actualizan los datos según lo recibido
		$program->setTitle( htmlspecialchars( addslashes( utf8_decode($_POST['title']) ) ) );
		$program->setCode( addslashes( utf8_decode($_POST['code']) ), $version ? null : json_decode($_POST["keypoints"], true) );
		$program->setVisibility( $version ? PRIVADO : $_POST['visibility'] );
		// Si el programa es una solución a un desafío se actualiza la tabla CC_PROGRAM_2
		if ( isset( $_POST['ccid'] ) ) {
			$pid = $_SESSION['bbdd']->queryValue("SELECT PROGRAM FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE='".$_POST['ccid']."' AND USER='".$user->getID()."'");
			if ( !$pid ) {
				$_SESSION['bbdd']->exec("INSERT INTO CC_PROGRAM_2 ( CONTEST_CHALLENGE, USER, PROGRAM ) VALUES ('".$_POST['ccid']."', '".$user->getID()."', '".$program->getID()."');");
			}
		}
		// Se devuelve el ID del programa para, en caso de que sea haya creado uno nuevo, el GUI use este dato como referencia en posteriores llamadas
		registerEvent( $_SESSION['user']->getID(), $program->getID(), "save" );
		echo '{ "id" : "'.$program->getID().'", "msg" : "Programa guardado" }';
		return;
	}

	if ( $_POST['service'] == "vote" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}
		$program = Program::loadFromID( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa no existe" }';
			return;
		}
		// Buscar si usuario actual ya había votado antes y en ese caso se quita su voto, si no se añade su voto
		$program->vote( $_SESSION['user'] );
		echo '{ "id" : "1", "msg" : "Programa votado", "votes" : "'.$program->getVotes().'" }';
		return;
	}

	if ( $_POST['service'] == "run" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}	
		$program = Program::loadFromID( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $program->getCreator() != $_SESSION['user']->getID() && ! $_SESSION['user']->isAdmin() ) {
			$program->incRuns();
		}
		echo '{ "id" : "1", "msg" : "Programa ejecutado" }';
		return;
	}

	if ( $_POST['service'] == "type" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}	
		if ( !isset( $_POST['ms'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el número de ms" }';
			return;
		}
		$program = Program::loadFromID( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa no existe" }';
			return;
		}
		if ( $_SESSION['user']->getID() == $program->getCreator() ) {
			$program->incTyping( $_POST['ms'] );
		}
		echo '{ "id" : "1", "msg" : "Programa ejecutado" }';
		return;
	}

	if ( $_POST['service'] == "set-visibility" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !isset( $_POST['visibility'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la visibilidad" }';
			return;
		}
		if ( ! isValidMd5($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}	
		$program = Program::loadFromMD5Key( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa no existe" }';
			return;
		}
		if ( $program->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "El programa sólo puedo ser modificado por su creador o el administrador" }';
			return;
		}
		$program->setVisibility( $_POST['visibility'] );
		echo '{ "id" : "1", "msg" : "Programa visto" }';
		return;
	}

	if ( $_POST['service'] == "delete" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( ! isValidMd5($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto." }';
			return;
		}	
		$program = Program::loadFromMD5Key( $_POST['id'] );
		if ( !$program ) {
			echo '{ "id" : "-1", "msg" : "El programa no existe" }';
			return;
		}
		if ( $program->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "El programa sólo puedo ser borrado por su creador o el administrador" }';
			return;
		}
		Program::remove( $program) ;
		echo '{ "id" : "1", "msg" : "Programa borrado" }';
		return;
	}

?>