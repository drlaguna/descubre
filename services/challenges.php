<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	include_once("../common.php");

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
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		if ( !isset( $_POST['title'] ) || $_POST['title'] == "" ) {
			echo '{ "id" : "-1", "msg" : "Por favor, pon un título a la prueba" }';
			return;
		}
		$challenge = null;
		if ( $_POST['id'] == "-1" ) {
			$challenge = new Challenge( $_SESSION['user']->getID() );
			if ( !$challenge ) {
				echo '{ "id" : "-1", "msg" : "Error al crear la prueba" }';
			}
			$challenge->save();
		} else {
			$challenge = Challenge::loadFromID( $_POST['id'] );
			if ( !$challenge ) {
				echo '{ "id" : "-1", "msg" : "La prueba no existe" }';
			}
		}
		// http://api.jquery.com/jquery.ajax/
		// JQuery about ajax: Data will always be transmitted to the server using UTF-8 charset; you must decode this appropriately on the server side."

		$challenge->setTitle( htmlspecialchars( addslashes( utf8_decode($_POST['title']) ) ) );
		$challenge->setStatement( htmlspecialchars( addslashes( utf8_decode($_POST['statement']) ) ) );
		$challenge->setCode(  addslashes( utf8_decode($_POST['code']) ) );
		$challenge->setTemplate(  addslashes( utf8_decode($_POST['template']) ) );
		$challenge->setDifficulty( $_POST['difficulty'] );

		echo '{ "id" : "'.$challenge->getID().'", "msg" : "Prueba guardada" }';
		return;
	}

	if ( $_POST['service'] == "get-tests" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		$challenge = Challenge::loadFromID( $_POST['id'] );
		if ( !$challenge ) {
			echo '{ "id" : "-1", "msg" : "La prueba '.$_POST['id'].' no existe" }';
			return;
		}
		$tests = $challenge->getTests();
		$cadena = "[";
		foreach ( $tests as $test ) {
//			$cadena .= '{ "id" : "'.$test['ID'].'", "input" :"'.$test['INPUT'].'", "output" : "'.$test['OUTPUT'].'", "image" : "'.$test['OUTPUT_IMAGE'].'" } ,';
//			$test['INPUT'] = $test['INPUT'];
			$testWithoutOutput = array( "id" => $test['ID'], "input" => utf8_encode($test['INPUT']) );
//			print_r($testWithoutOutput);
			$cadena .= json_encode($testWithoutOutput).",";
		}
		/*
		$test1 = array("input" => "hola", "output" => "1\n2\n3\n", "image" => "");
		$test2 = array("input" => "hola\nqué tal\n", "output" => "adios", "image" => "1\n2\n3\n");
		$cadena .= json_encode($test1).", ".json_encode($test2).",";
		*/
		if ( strlen( $cadena ) > 1 ) {			
			$cadena = substr($cadena, 0, -1);
		}
		$cadena .= "]";
		echo '{ "id" : "1", "tests" : '.$cadena.' }';
		return;
	}

	if ( $_POST['service'] == "add-test" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		if ( !isset( $_POST['input'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la entrada" }';
			return;
		}
		if ( !isset( $_POST['output'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la salida de consola" }';
			return;
		}
		if ( !isset( $_POST['hash'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la salida de consola" }';
			return;
		}
		$challenge = Challenge::loadFromID( $_POST['id'] );
		if ( !$challenge ) {
			echo '{ "id" : "-1", "msg" : "La prueba '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $challenge->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "La prueba sólo puede ser modificada por su creador o el administrador" }';
			return;
		}
		$input = htmlspecialchars( addslashes( utf8_decode( $_POST['input'] ) ) );
		$query = "INSERT INTO TESTCASE_2 (CHALLENGE, INPUT, OUTPUT, HASH) VALUES ('".$challenge->getID()."', '".$input."', '".$_POST['output']."', '".$_POST['hash']."')";
		$_SESSION['bbdd']->exec($query);
		echo '{ "id" : "'.$challenge->getID().'", "msg" : "Prueba guardada" }';
		return;
	}

	if ( $_POST['service'] == "remove-test" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		if ( !isset( $_POST['testid'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la entrada" }';
			return;
		}
		$challenge = Challenge::loadFromID( $_POST['id'] );
		if ( !$challenge ) {
			echo '{ "id" : "-1", "msg" : "La prueba '.$_POST['id'].' no existe" }';
			return;
		}
		if ( $challenge->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isAdmin() ) {
			echo '{ "id" : "-1", "msg" : "La prueba sólo puede ser borrada por su creador o el administrador" }';
			return;
		}
		$query = "DELETE FROM TESTCASE_2 WHERE ID='".$_POST['testid']."'";
		$_SESSION['bbdd']->exec($query);
		echo '{ "id" : "1", "msg" : "Prueba borrada" }';
		return;
	}

	/*
	if ( $_POST['service'] == "check-test" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		if ( !isset( $_POST['testid'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del caso de prueba" }';
			return;
		}
		if ( !isset( $_POST['output'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la salida de consola" }';
			return;
		}
		if ( !isset( $_POST['image'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar la salida del canvas" }';
			return;
		}
		$challenge = Challenge::loadFromID( $_POST['id'] );
		if ( !$challenge ) {
			echo '{ "id" : "-1", "msg" : "La prueba '.$_POST['id'].' no existe" }';
			return;
		}

		$tests = $challenge->getTests( $_POST['testid'] );
		if ( $_POST['output'] == $tests[0]['OUTPUT'] && $_POST['image'] == $tests[0]['OUTPUT_IMAGE'] ) {
			echo '{ "id" : "1", "msg" : "Prueba '.$_POST['id'].' test '.$tests[0]['ID'].' superado" }';
		} else {
			echo '{ "id" : "1", "msg" : "Prueba '.$_POST['id'].' NO superada. Se esperaba: ['.$tests[0]['OUTPUT'].']" }';
		}
		return;
	}
	*/
	if ( $_POST['service'] == "check-tests" ) {
		if ( !isset( $_POST['id'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la prueba" }';
			return;
		}
		if ( !isset( $_POST['pid'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador del programa" }';
			return;
		}
		if ( !isset( $_POST['ccid'] ) ) {
			echo '{ "id" : "-1", "msg" : "Se debe especificar el identificador de la asociación reto-prueba" }';
			return;
		}
		$results = array();
		if ( isset( $_POST['tests'] ) ) {
			$results = $_POST['tests'];
		}
		$challenge = Challenge::loadFromID( $_POST['id'] );
		if ( !$challenge ) {
			echo '{ "id" : "-1", "msg" : "La prueba '.$_POST['id'].' no existe" }';
			return;
		}
		$tests = $challenge->getTests( );
		$positives = 0;
		$total = sizeof( $tests );
		
		foreach ( $results as $try ) {
			$tests = $challenge->getTests( $try['id'] );
//			if ( $try['output'] == $tests[0]['OUTPUT'] && $try['image'] == $tests[0]['OUTPUT_IMAGE'] ) {
			if ( $try['hash'] == $tests[0]['HASH'] ) {
				$positives++;
			}
		}
		// Comprobar si se está dentro de los límites temporales del reto
		$cc = ContestChallenge::loadFromID( $_POST['ccid'] );
		$contest = $cc->getContest();
		if ( !$contest->isOpen() ) {
			echo '{ "id" : "1", "success" : "0", "msg" : "Prueba superada fuera de tiempo." }';
			return;
		}
		$result = 0;
		if ( $positives == $total ) {
			echo '{ "id" : "1", "success" : "1", "msg" : "Prueba '.$_POST['id'].', tests superados" }';
			$result = 1;
		} else {
			echo '{ "id" : "1", "success" : "0", "msg" : "('.$positives.'/'.$total.')" }';
		}
		$program = Program::loadFromID($_POST['pid']);
		if (!$program) {
			echo '{ "id" : "-1", "msg" : "Fallo al cargar programa con id: '.$_POST['pid'].'" }';
			return;
		}
		if ( $program->getCreator() != $_SESSION['user']->getID() ) {
			// El tutor está evaluando la prueba del alumno y no hay que dejar constancia de este intento
			return;
		}
		$contest_challenge = $_SESSION['bbdd']->query("SELECT * FROM CONTEST_CHALLENGE_2 WHERE ID='".$_POST['ccid']."'");
		// Comprobar si ya se ha superado el reto con el programa actual (incluida versión??? no para que sólo quede reflejada la primera superación)
		$overcom = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CC_TRY_2 WHERE CONTEST_CHALLENGE='".$_POST['ccid']."' AND USER='".$_SESSION['user']->getID()."' AND PROGRAM='".$program->getID()."' AND SUCCESS='1'"); // AND SOURCE_CODE='".$program->getVersion()."' 
		if ( $overcom == 1 ) {
			return;
		}
		
		$query = "INSERT INTO CC_TRY_2 (CONTEST_CHALLENGE, CONTEST, CHALLENGE, USER, PROGRAM, SOURCE_CODE, DATE, SUCCESS) VALUES ('".$contest_challenge['ID']."', '".$contest_challenge['CONTEST']."', '".$contest_challenge['CHALLENGE']."', '".$_SESSION['user']->getID()."', '".$program->getID()."', '".$program->getVersion()."', '".date("Y-m-d H:i:s")."', '".$result."')";
		$_SESSION['bbdd']->exec($query);
	}
?>