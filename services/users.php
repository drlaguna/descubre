<?php
	include_once("../common.php");

	function isValidMd5($md5 ='') {
	  return strlen($md5) == 32 && ctype_xdigit($md5);
	}		

	if ( !isset( $_POST['service'] ) ) {
		return;
	}
	if ( $_POST['service'] == "loged" ) {
		$res = $_SESSION['user']->getID() != -1;
		echo '{ "id" : "'.$res.'", "msg" : "" }';
	}
	
	if ( $_POST['service'] == "login" ) {
		$_POST['username'] = utf8_decode( $_POST['username'] );
		$_POST['username'] = mysql_real_escape_string($_POST['username']);

		if ( ! isValidMd5($_POST['password']) ) {
			echo "Acceso incorrecto.";
			return;
		}	

		$user = User::login( $_POST['username'], $_POST['password'] );
		if ( $user ) {
			if ( ! $user->isActive() ) {
				echo "Debes activar tu usuario antes de poder entrar al sistema. Revisa el correo que indicaste al darte de alta porque allí se envió el mensaje con la clave de activación.";
				return;
			}
			$_SESSION['user'] = $user;
			registerEvent( $user->getID(), -1, "login" );
			echo "ok";
		} else {
			echo "El usuario o el password son incorrectos.";
		}
		return;
	}
	
	if ( $_POST['service'] == "logout" ) {
		registerEvent( $_SESSION['user']->getID(), -1, "logout" );
		$_SESSION['user'] = new User();
		if ( isset( $_POST['origin']) ) header( "location:".$_POST['origin'] );
		else header("location:index.php");
	}
	
	if ( $_POST['service'] == "add/edit" && isset( $_POST['id'] ) ) {
		if ( !is_numeric($_POST['id']) ) {
			echo '{ "id" : "-1", "msg" : "Acceso incorrecto 1." }';
			return;
		}
		$_POST['login'] = utf8_decode( $_POST['login'] );
		$_POST['name'] = utf8_decode( $_POST['name'] );
		$_POST['centre'] = utf8_decode( $_POST['centre'] );
		
		if ( $_POST['id'] == -1 ) {

			if ( ! isValidMd5($_POST['password']) ) {
				echo '{ "id" : "-1", "msg" : "Acceso incorrecto 2." }';
				return;
			}	
	
			$aid = User::add( $_POST['login'], $_POST['name'], $_POST['email'], $_POST['password'], $_POST['birthyear'], $_POST['centre'], $_POST['gender'], $_POST['role'] );
			if ( $aid != -1 ) {
				// Registro de activación
				$amd5 = $_SESSION['bbdd']->queryValue("SELECT MD5_KEY FROM ACTIVATION_2 WHERE ID='".$aid."'");
				
				$body = 'Hola,
				haz click en <a href="'.DESCUBRE_URL.'/activa.php?id='.$amd5.'">Activar el usuario con identificador '.$_POST['login'].'</a> para terminar el proceso.';
				sendHTMLmail( $_POST['email'], "[Descubre la programación] Usuario registrado", formatBody( $body ), "noreply@um.es");			
				echo '{ "id" : "1", "msg" : "Usuario creado" }';
			} else {
				echo '{ "id" : "-1", "msg" : "El identificador de usuario '.$_POST['login'].' ya está siendo utilizado por otra persona." }';
				return;
			}		
		} else {
			$user = User::loadFromID( $_POST['id'] );
			if ( !$user ) {
				echo '{ "id" : "-1", "msg" : "No existe ningún usario con ese identificador." }';
				echo "No existe ningún usuario con ese identificador.";
			}
			if ( !$user->setUsername( $_POST['login'] ) ) {
				echo '{ "id" : "-1", "msg" : "El identificador de usuario '.$_POST['login'].' ya está siendo utilizado por otra persona." }';
				return;
			}
			$user->setName( $_POST['name'] );
			$user->setEmail( $_POST['email'] );
			if ( isset ( $_POST['password'] ) ) {
				if ( ! isValidMd5($_POST['password']) ) {
					echo '{ "id" : "-1", "msg" : "Acceso incorrecto 3." }';
					return;
				}	
		
				$user->setPassword( $_POST['password'] );
			}
			if ( isset ( $_POST['birthyear'] ) ) $user->setBirthyear( $_POST['birthyear'] );
			if ( isset ( $_POST['gender'] ) ) $user->setGender( $_POST['gender'] );
			if ( isset ( $_POST['centre'] ) ) $user->setCentre( $_POST['centre'] );
			if ( isset ( $_POST['role'] ) ) $user->setRole( $_POST['role'] );
			if ( $_SESSION['user']->getID() == $user->getID() ) {
				$_SESSION['user'] = User::loadFromID( $user->getID() );
			}
			echo '{ "id" : "1", "msg" : "Datos de usuario modificados" }';
		}
		return;
	}

	if ( $_POST['service'] == "workload" ) {
		if ( !isset($_POST['user'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe indicar el usuario" }';
			return;
		}
		if ( !isset($_POST['from'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe indicar el from" }';
			return;
		}
		if ( !isset($_POST['to'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe indicar el to" }';
			return;
		}
		$program = null;
		if ( isset( $_POST['program'] ) ) {
			$program = Program::loadFromID( $_POST['program'] );
		}
		$dates = workload($_POST['from'], $_POST['to'], $_POST['user'], $program ? $program->getID() : null );
		$total = 0;
		$cadena = "[";
		foreach ($dates as $day=>$time) {
				$total += $time;
				$cadena .= '{ "day" :"'.$day.'", "time" : "'.intval($time/60).'" },';
		}
		if ( strlen( $cadena ) > 1) {		
			$cadena = substr($cadena, 0, -1);
		}
		$cadena .= "]";
		echo '{ "id" : "1", "dates" : '.$cadena.' }';
//		echo "[".json_encode($dates, JSON_FORCE_OBJECT)."]";
		return;
	}
	
	if ( $_POST['service'] == "delete" ) {
		if ( !isset($_SESSION['user'] ) || !$_SESSION['user']->isRegistered() || !$_SESSION['user']->hasAccess( Administrador, PER_ESCRITURA ) ) {
			echo '{ "id" : "-1", "msg" : "Usuario no registrado o sin permiso. Es posible que haya expirado el tiempo de la sesión. No cierre la ventana de edición, use una ventana distinta del navegador para registrarse y vuelva a intentar guardar la noticia." }';
			return;
		}

		if ( isset( $_POST['id'] ) ) {
			$user = User::loadFromID( $_POST['id'] );			
			if ( $user ) {
				if ( $user->hasCreatedSomething() ) {
					echo '{ "id" : "-1", "msg" : "En la BBDD hay recursos, páginas o noticias creadas por el usuario. No es posible borrarlo, pero puede desactivarlo." }';
				} else {
					User::remove( $user );
					echo '{ "id" : "1", "msg" : "Usuario borrado" }';
				}
			} else {
				echo '{ "id" : "-1", "msg" : "El usuario no existe" }';
			}
		} else {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del usuario." }';
		}		
	}

?>