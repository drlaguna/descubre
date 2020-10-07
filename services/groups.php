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
		$_POST['name'] = htmlspecialchars( addslashes( utf8_decode( $_POST['name'] ) ) );
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del grupo" }';
			return;
		}
		$group = null;
		if ( $_POST['id'] == "-1" ) {
			$group = new Group( $_SESSION['user']->getID(), $_POST['name'] );
			if ( !$group ) {
				echo '{ "id" : "-1", "msg" : "Error al crear el grupo" }';
			}
			$group->save();
			$group->add( $_SESSION['user'] );
		} else {
			$group = Group::loadFromID( $_POST['id'] );
			if ( !$group ) {
				echo '{ "id" : "-1", "msg" : "El grupo no existe" }';
			}
		}
		// http://api.jquery.com/jquery.ajax/
		// JQuery about ajax: Data will always be transmitted to the server using UTF-8 charset; you must decode this appropriately on the server side."

		$group->setName( $_POST['name'] );
		$group->setPassword( htmlspecialchars( addslashes( utf8_decode($_POST['password'] ) ) ) );

		echo '{ "id" : "'.$group->getID().'", "msg" : "Grupo guardado" }';
		return;
	}
	
	if ( $_POST['service'] == "join" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del grupo" }';
			return;
		}
		if ( !isset( $_POST['password'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el password del grupo" }';
			return;
		}
		$group = Group::loadFromID( $_POST['id'] );
		if ( !$group ) {
			echo '{ "id" : "-1", "msg" : "El grupo '.$_POST['id'].' no existe" }';
			return;
		}
		if ( ! $group->hasPassword() ) {
			echo '{ "id" : "1", "msg" : "El grupo no requiere password" }';
			return;
		}
		if ( $_POST['password'] == md5( $group->getPassword() ) ) {
			$group->add( $_SESSION['user'] );
			echo '{ "id" : "1", "msg" : "Usuario registrado con éxito" }';
		} else {
			echo '{ "id" : "-1", "msg" : "Password incorrecto" }';
		}
		return;
	}

	if ( $_POST['service'] == "leave" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del grupo" }';
			return;
		}
		$group = Group::loadFromID( $_POST['id'] );
		if ( !$group ) {
			echo '{ "id" : "-1", "msg" : "El grupo '.$_POST['id'].' no existe" }';
			return;
		}
		if ( ! $group->hasPassword() ) {
			echo '{ "id" : "1", "msg" : "El grupo no requiere password" }';
			return;
		}
		if ( ! $group->includes( $_SESSION['user'] ) ) {
			echo '{ "id" : "1", "msg" : "El grupo no incluye al usuario" }';
			return;
		}
		$group->remove( $_SESSION['user'] );
		echo '{ "id" : "1", "msg" : "Usuario deregistrado con éxito" }';
		return;
	}
	
	if ( $_POST['service'] == "delete" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del grupo" }';
			return;
		}
		$group = Group::loadFromID( $_POST['id'] );
		if ( !$group ) {
			echo '{ "id" : "-1", "msg" : "El grupo '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $group->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "El grupo sólo puedo ser borrado por su creador o el administrador" }';
			return;
		}
		$members = $group->getMembers(); 
		if ( sizeof( $members ) > 1 ) {
			echo '{ "id" : "-1", "msg" : "No se puede borrar el grupo porque tiene usuarios inscritos." }';
			return;
		}
		$contests = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CONTEST_2 WHERE GROUP_ID='".$group->getID()."'");
		if ( $contests > 0 ) {
			echo '{ "id" : "-1", "msg" : "No se puede borrar el grupo porque tiene retos asociados. Borra los retos antes de borrar el grupo." }';
			return;
		}
		$group->remove( $_SESSION['user'] );
		Group::erase( $group );
		echo '{ "id" : "1", "msg" : "Grupo borrado" }';
		return;
	}

?>