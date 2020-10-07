<?php
include_once("bbdd.php");

class Group {
	var $id;
	var $name;
	var $md5key;
	var $creator;
	var $password;
	
	var $saved;

	function Group( $creatorid, $name = "" ) {
		$this->id = -1;		$this->name = $name; 
		$this->creator = $creatorid;
		$this->password = "";
		$this->saved = false;	}
	
	function createFromRow( $row ) {
		$group = new Group( $row['OWNER'], $row['NAME'] );
		$group->id = $row['ID'];		$group->password = $row['PASSWORD'];		$group->md5key = $row['MD5_KEY'];		$group->saved = true;		return $group;	}	function loadFromID( $id ) {		$query = "SELECT * FROM GROUP_2 WHERE ID='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Group::createFromRow( $row );
	}	function loadFromMD5Key( $md5key ) {		$query = "SELECT * FROM GROUP_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Group::createFromRow( $row );
	}	function save() {
		$consulta = "INSERT INTO GROUP_2 ( NAME, OWNER, PASSWORD ) VALUES ('".$this->name."', '".$this->creator."', '".$this->password."')";		$this->id = $_SESSION['bbdd']->exec($consulta);
		if ( $this->id == -1 ) {
			echo "Error al insertar grupo";
			echo $consulta;
			return -1;
		}
		$query = "UPDATE GROUP_2 SET MD5_KEY=MD5(CONCAT(ID, NAME, OWNER, PASSWORD, NOW())) WHERE ID='".$this->id."'";
		$_SESSION['bbdd']->exec($query);
		$this->saved = true;	}
	
	function erase( $group ) {
		$_SESSION['bbdd']->exec("DELETE FROM GROUP_2 WHERE ID='".$group->getID()."'");
	}
	
	function getID() {		return $this->id;	}		function getName() {		return $this->name;	}	function getCreator() {		return $this->creator;	}
	function getMD5Key() {		return $this->md5key;	}	function hasPassword() {
		return $this->password != "";
	}
	
	function getPassword() {		return $this->password;	}	function isSaved() {		return $this->saved;	}
	
	function getMembers() {
		$ids = $_SESSION['bbdd']->queryArray( "SELECT USER FROM GROUP_USER_2 WHERE GROUP_ID='".$this->id."'" );
		$result = array();
		foreach ( $ids as $id ) {
			$result[] = User::loadFromID($id['USER']);
		}
		return $result;
	}	function setName( $name ) {
		if ( $this->name != $name ) {
			$this->name = $name;
		} else {
			return;
		}
		if ( $this->saved ) {			$query = "UPDATE GROUP_2 SET NAME='".$this->name."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setPassword( $password ) {
		if ( $this->password != $password ) {
			$this->password = $password;
		} else {
			return;
		}
		if ( $this->saved ) {			$query = "UPDATE GROUP_2 SET PASSWORD='".$this->password."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}
	
	function includes( $user ) {
		$query = "SELECT COUNT(*) FROM GROUP_USER_2 WHERE GROUP_ID='".$this->id."' AND USER='".$user->getID()."'";
		$n = $_SESSION['bbdd']->queryValue( $query );
		return $n == 1;
	}
	
	function add( $user ) {
		// Inserción que si falla porque ya está insertado no pasa nada
		$_SESSION['bbdd']->exec("INSERT INTO GROUP_USER_2 (GROUP_ID, USER) VALUES ('".$this->id."', '".$user->getID()."')");
		// Comprobar si el usuario incluido es ya alumno del creador del grupo y si no ponerlo
		$query = "SELECT COUNT(*) FROM COACHING_2 WHERE COACH='".$this->creator."' AND STUDENT='".$user->getID()."';";				if ( $_SESSION['bbdd']->queryValue($query) == 1 ) return;		
		$_SESSION['bbdd']->exec("INSERT INTO COACHING_2 ( COACH, STUDENT ) VALUES ('".$this->creator."', '".$user->getID()."')");
	}
	
	function remove( $user ) {
		$_SESSION['bbdd']->exec("DELETE FROM GROUP_USER_2 WHERE GROUP_ID='".$this->id."' AND USER='".$user->getID()."'");
		// Comprobar si el usuario sigue siendo alumno del creador de este grupo a través de otro grupo y si no quitarlo de la tabla de alumnos
		$query = "SELECT COUNT(*) FROM GROUP_USER_2 as gu, GROUP_2 as g WHERE gu.GROUP_ID=g.ID AND g.OWNER='".$this->creator."' AND gu.USER='".$user->getID()."';";				if ( $_SESSION['bbdd']->queryValue($query) > 0 ) return;						
		$_SESSION['bbdd']->exec("DELETE FROM COACHING_2 ( COACH, STUDENT ) VALUES ('".$this->creator."', '".$user->getID()."')");
	}}

class User {	var $id; /* usu_id */
	var $username; // Antiguo USER_ID	var $email; /* usu_email */	var $name; /* usu_nombre */
	var $md5key;
	var $experience;
	var $signupdate;
	var $birthyear;
	var $gender;
	var $centre;
	var $role;	var $active;
	var $agreed;

	var $registered;
		function User() {
		$this->id = -1; // USUARIO ANONIMO		$this->username = ""; 
		$this->email = "";		$this->name = "";
		$this->md5key = "";
		$this->experience = 0;
		$this->birthyear = 2000;
		$this->gender = "M";
		$this->centre = "";
		$this->active = 1;
		$this->role = "Estudiante";
		$this->agreed = 1; // USUARIO ANONIMO NO NECESITA ACEPTAR NADA
				$this->registered = false;	}		
	function createFromRow( $row ) {
		$user = new User();
		$user->id = $row['ID'];		$user->username = $row['USER_ID'];		$user->email = $row['EMAIL'];		$user->name = $row['NAME'];
		$user->md5key = $row['MD5_KEY'];		$user->experience = $row['EXPERIENCE'];		$user->signupdate = $row['SIGNUPDATE'];		$user->birthyear = $row['BIRTHYEAR'];
		$user->gender = $row['GENDER'];		$user->centre = $row['CENTRE'];		$user->role = $row['ROLE'];		$user->active = $row['ACTIVE'];
		$user->agreed = $row['AGREED'];
		$user->registered = true;		return $user;	}	function loadFromID( $id ) {		$query = "SELECT * FROM USER_2 WHERE ID='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return User::createFromRow( $row );
	}	function loadFromMD5Key( $md5key ) {		$query = "SELECT * FROM USER_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return User::createFromRow( $row );
	}	function login( $username, $md5key ) {		$query = "SELECT * FROM USER_2 WHERE EMAIL='".$username."' AND PASSWORD='".$md5key."';";
		$row = $_SESSION['bbdd']->query($query);
		if (!$row) {			$query = "SELECT * FROM USER_2 WHERE USER_ID='".$username."' AND PASSWORD='".$md5key."';";			$row = $_SESSION['bbdd']->query($query);			if (!$row) {				return null;
			}		}
		$_SESSION['bbdd']->exec("UPDATE USER_2 SET LASTLOGIN=NOW() WHERE ID='".$row['ID']."'");
		return User::createFromRow( $row );
	}	function agreed( $md5key ) {
		$_SESSION['bbdd']->exec("UPDATE USER_2 SET AGREED=1 WHERE MD5_KEY='".$md5key."'");
	}		function add( $login, $name, $email, $md5password, $birthyear, $centre, $gender, $role) {		$user = $_SESSION['bbdd']->query("SELECT * FROM USER_2 WHERE USER_ID='".$login."';");							if ($user != null) {
			return -1;
		}		$consulta = "INSERT INTO USER_2 ( USER_ID, NAME, EMAIL, PASSWORD, BIRTHYEAR, CENTRE, GENDER, ROLE, SIGNUPDATE, ACTIVE, AGREED ) VALUES ('".$login."', '".$name."', '".$email."', '".$md5password."', '".$birthyear."', '".$centre."', '".$gender."', '".$role."', '".date("Y-m-d")."', '0', '1')";		$id = $_SESSION['bbdd']->exec($consulta);
		if ( $id == -1 ) {
			return -2;
		}
		$_SESSION['bbdd']->exec("UPDATE USER_2 SET MD5_KEY=MD5(CONCAT(ID, USER_ID, NAME, PASSWORD, NOW())) WHERE ID='".$id."'");
		// Insertar registro activación
		
		$aid = $_SESSION['bbdd']->exec("INSERT INTO ACTIVATION_2 (USER, DEADLINE, USED) VALUES ('".$id."', DATE_ADD(NOW(),INTERVAL 2 DAY), 0  )" );
		if ( $aid == -1 ) {
			return -3;
		}
		$_SESSION['bbdd']->exec("UPDATE ACTIVATION_2 SET MD5_KEY=MD5(CONCAT(ID, USER, DEADLINE, NOW())) WHERE ID='".$aid."'");
				return $aid;	}	function remove( $user ) {
//		$_SESSION['bbdd']->query ("DELETE FROM usuarios_2 WHERE usu_id = '".$user->getID()."'; " );	}	
	
	function getLastActivationTicket() {
		$row = $_SESSION['bbdd']->query("SELECT * FROM ACTIVATION_2 WHERE USER='".$this->id."' AND USED=0");
		return $row['MD5_KEY'];
	}
	
	function activate( $aidmd5 ) {
		$row = $_SESSION['bbdd']->query("SELECT * FROM ACTIVATION_2 WHERE MD5_KEY='".$aidmd5."'");
		if ( !$row ) return -1;
		if ( $row['USED'] == 1 ) return -2;
		$_SESSION['bbdd']->exec("UPDATE USER_2 SET ACTIVE='1' WHERE ID='".$row['USER']."'");		
		$_SESSION['bbdd']->exec("UPDATE ACTIVATION_2 SET USED='1' WHERE ID='".$row['ID']."'");
		return 1;
	}
			function getID() {		return $this->id;	}		function getUsername() {		return $this->username;	}	function getName() {		return $this->name;	}	function getMD5Key() {		return $this->md5key;	}	function getemail() {		return $this->email;	}		function getExperience() {
		$times = $_SESSION['bbdd']->queryArray("SELECT TYPING FROM PROGRAM_2 WHERE OWNER='".$this->id."' AND VISIBILITY<>".BORRADO."");
		$time = 0;
		foreach ( $times as $t ) $time += $t['TYPING'];		return round($this->experience + ($time/1000));	}		function getLevel() {
		$xp = $this->experience;
		$msg = "";
		$xp = intval($xp);
		if ($xp < 3600) return "<h4>Nivel: Novato </h4>".(3600-$xp)." para pasar a <em>Iniciado</em>";
		if ($xp < 7200) return "<h4>Nivel: Iniciado </h4>".(7200-$xp)." para pasar a <em>Aprendiz</em>";
		if ($xp < 12600) return "<h4>Nivel: Aprendiz </h4>".(12600-$xp)." para pasar a <em>Intermedio</em>";
		if ($xp < 18000) return "<h4>Nivel: Intermedio </h4>".(18000-$xp)." para pasar a <em>Avanzado</em>";
		if ($xp < 25200) return "<h4>Nivel: Avanzado </h4>".(25200-$xp)." para pasar a <em>Experto</em>";
		if ($xp < 32400) return "<h4>Nivel: Experto </h4>".(32400-$xp)." para pasar a <em>Maestro</em>";
		if ($xp < 43200) return "<h4>Nivel: Maestro </h4>".(43200-$xp)." para pasar a <em>Leyenda</em>";
		if ($xp < 54000) return "<h4>Nivel: Leyenda </h4>".(54000-$xp)." para pasar a <em>Sobrenatural</em>";
		if ($xp < 64800) return "<h4>Nivel: Sobrenatural </h4>".(64800-$xp)." para pasar a <em>Profesor</em>";
		if ($xp < 79200) return "<h4>Nivel: Profesor </h4>".(79200-$xp)." para pasar a <em>Catedrático</em>";
		if ($xp < 93600) return "<h4>Nivel: Catedrático </h4>".(93600-$xp)." para pasar a <em>Decano</em>";
		if ($xp < 108000) return "<h4>Nivel: Decano </h4>".(108000-$xp)." para pasar a <em>Divinidad</em>";
		return "<h4>Nivel: Divinidad</h4>";
	}
	
	function getKeypoints() {
		// Buscar todos sus programas
		$pids = $_SESSION['bbdd']->queryArray("SELECT ID FROM PROGRAM_2 WHERE OWNER='".$this->id."' AND VISIBILITY <> '".BORRADO	."'");
		$sum = array();
		foreach ( $pids as $pid ) {
			$program = Program::loadFromID( $pid['ID'] );
			if ( !$program ) continue;
			// Para cada código fuente de cada programa comprobar sus keypoints y obtener la unión
			$kp = $program->getKeypoints();
			// Sumar los keypoints de todos los programas
			foreach ( $kp as $key => $value ) {
				if ( ! isset($sum[$key]) ) $sum[$key] = 0;
				$sum[$key] += $value;
			}
		}
		$kp = array("Expresiones" => 0, "Variables" => 0, "Funciones" => 0, "Bucles" => 0, "Bifurcaciones" => 0, "Arrays" => 0);//, "Objetos" => 0);
		if ( sizeof( $sum ) > 0 ) {
		$kp['Expresiones'] = $sum['USE_LITERAL'] + $sum['HAS_ARITHMETIC_EXPRESSION'] + $sum['HAS_BOOLEAN_EXPRESSION'];
		$kp['Variables'] = $sum['HAS_DECLARATION'] + $sum['HAS_CONSTANT'] + $sum['HAS_ASSIGMENT'];
		$kp['Funciones'] = $sum['USE_FUNCTION'] + $sum['CREATE_FUNCTION'] + $sum['HAS_RECURSIVE_FUNCTION'];
		$kp['Bucles'] = $sum['HAS_FOR'] + $sum['HAS_WHILE'] + $sum['HAS_DO_WHILE'];
		$kp['Bifurcaciones'] = $sum['HAS_IF'] + $sum['HAS_ELSE'] + $sum['HAS_SWITCH'];
		$kp['Arrays'] = $sum['USE_ARRAY'] + $sum['USE_ARRAY_AS_PARAMETER'] + $sum['RETURN_ARRAY'];
		}
//		$kp['Objetos'] = $sum['CREATE_OBJECTS'] + $sum['DEFINE_CLASSES'] + $sum['DEFINE_METHODS'];

		// Calcular % por apartado con un valor de 50 repeticiones como 100%
		foreach ( $kp as $key => $value ) {
			$kp[$key] = ($kp[$key]*100)/50;
			$percent = min( 100, $kp[$key]);
?>			
			<div class="col-md-4">
			<label><?php echo $key; ?></label>
			</div>
			
			<div class="col-md-8">
			<div class="progress">
			  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="color: #333; width: <?php echo $percent; ?>%;">
			    <?php echo $percent; ?>%
			  </div>
			</div>		
			</div>		
<?php			
		}
		
		return $sum;
	}

	function getGender() {		return $this->gender;	}		function getCentre() {		return $this->centre;	}
	
	function getBirthyear() {
		return $this->birthyear;
	}
	
	function getSignupdate() {
		return $this->signupdate;
	}		function getrole() {		return $this->role;	}
	
	function getProfileLink() {
		return '<a href="perfil.php?id='.$this->md5key.'">'.$this->username.'</a>';
	}
	
	function isActive() {
		return $this->active;
	}		function hasAgreed() {
		return $this->agreed;
	}		function isRegistered() {		return $this->registered;	}	
	function hasTutor() {
		$query = "SELECT COUNT(*) FROM COACHING_2 WHERE STUDENT='".$this->id."';";				return $_SESSION['bbdd']->queryValue($query) > 0;		
	}
	
	function isTutorOf( $student ) {
		if ( is_object( $student ) ) {
			$student = $student->getID();
		}
		$query = "SELECT COUNT(*) FROM COACHING_2 WHERE COACH='".$this->id."' AND STUDENT='".$student."';";
		return $_SESSION['bbdd']->queryValue($query) == 1;		
	}
		function isAdmin() {		$query = "SELECT COUNT(*) FROM ADMIN_2 WHERE USER='".$this->id."';";				return $_SESSION['bbdd']->queryValue($query) == 1;
	}		function setName( $name ) {
		if ( $this->name != $name ) {
			$this->name = $name;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET NAME='".$this->name."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setUsername( $username ) {
		if ( $this->username != $username ) {
			$user = $_SESSION['bbdd']->queryValue("SELECT * FROM USER_2 WHERE USER_ID='".$username."';");								if ($user != null) return false;				$this->username = $username;
		} else {
			return true;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET USER_ID='".$this->username."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}
		return true;	}	function setEmail( $email ) {		if ( $this->email != $email ) {
			$this->email = $email;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET EMAIL='".$this->email."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setBirthyear( $birthyear ) {		if ( $this->birthyear != $birthyear ) {
			$this->birthyear = $birthyear;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET BIRTHYEAR='".$this->birthyear."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setGender( $gender ) {		if ( $this->gender != $gender ) {
			$this->gender = $gender;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET GENDER='".$this->gender."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setCentre( $centre ) {		if ( $this->centre != $centre ) {
			$this->centre = $centre;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET CENTRE='".$this->centre."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setRole( $role ) {		if ( $this->role != $role ) {
			$this->role = $role;
		} else {
			return;
		}
		if ( $this->registered ) {			$query = "UPDATE USER_2 SET ROLE='".$this->role."' WHERE ID='".$this->id."';";					$_SESSION['bbdd']->query($query);
		}	}	function setPassword( $password ) {
		if ( !$this->registered ) return;		$user = $_SESSION['bbdd']->query("SELECT * FROM USER_2 WHERE ID='".$this->id."'");		if ( $password == $user['PASSWORD']) return false;		$_SESSION['bbdd']->exec("UPDATE USER_2 SET PASSWORD='".$password."' WHERE ID ='".$this->id."'");	}}define ("PUBLICO", 0);
define ("PRIVADO", 1);
define ("EJEMPLO", 2);
define ("SOLUCION", 3);
define ("USABLE", 4);
define ("EVALUABLE", 5);
define ("BORRADO", 100);
class Program {	var $id; 
	var $md5key;	var $version;
	var $creation;
	var $title; 	var $views;	var $visibility;
	var $creator;

	var $root; 	var $icon; 	var $votes; 	var $runs;
	var $typing;
		// For the current version		var $code;
	var $versionCreation;
		var $saved;		function Program($creatorid, $root = -1) {
		$this->id = -1;
		$this->md5key = "";		$this->version = -1;		$this->creation = date("Y-m-d H:i:s");
		$this->title = "";		$this->views = 0;		$this->visibility = PRIVADO;		$this->creator = $creatorid;		$this->root = $root;				$this->icon = 0;
		$this->votes = 0;
		$this->runs = 0;
		$this->typing = 0;
		$this->code = null;
		$this->versionCreation = $this->creation;
		$this->saved = false;				
	}		
	
	function createFromRow( $row, $version ) {
		$program = new Program($row['OWNER']);
		$program->id = $row['ID'];
		$program->md5key = $row['MD5_KEY'];		$program->version = $version ? $version : $row['CURRENT_VERSION'];		$program->creation = $row['DATE'];		$program->title = $row['TITLE'];		$program->views = $row['VIEWS'];		$program->visibility = $row['VISIBILITY'];
		$program->creator = $row['OWNER'];		$program->root = $row['ROOT'];		$program->icon = $row['ICON'];		$program->votes = $row['VOTES'];		$program->runs = $row['RUNS'];		$program->typing = $row['TYPING'];		$program->saved = true;		// selected or current version 		$query = "SELECT * FROM SOURCE_CODE_2 WHERE ID='".$program->version."'";
		$row = $_SESSION['bbdd']->query($query);
		if (!$row) {
			return null;
		}		$program->code = $row['CODE']; // iconv( "ISO-8859-1", "UTF-8", $row['CODE'] ); 
		$program->versionCreation = $row['DATE'];
		return $program;	}		function loadFromMD5Key($md5key, $version = null) {		$query = "SELECT * FROM PROGRAM_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Program::createFromRow( $row, $version );
	}		function loadFromID($id, $version = null) {		$query = "SELECT * FROM PROGRAM_2 WHERE ID ='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Program::createFromRow( $row, $version );
	}		function save( ) {		
		// Crear el md5
		if ($this->md5key != "") {
			die("Program::save - md5 recalculado");
		}
		// Crear programa si no existe aún		if ( !$this->saved ) {
			$query = "INSERT INTO PROGRAM_2 
								( CURRENT_VERSION, DATE, TITLE, VIEWS, VISIBILITY, OWNER, ICON, VOTES, RUNS, TYPING, ROOT ) 
						VALUES 	( '".$this->version."', '".$this->creation."', '".$this->title."', '".$this->views."', '".$this->visibility."', '".$this->creator."', '".$this->icon."', '".$this->votes."', '".$this->runs."', '".$this->typing."', '".$this->root."' );";
			$this->id = $_SESSION['bbdd']->exec( $query );
			if ( !$this->id ) {
				die("Program::save() - Error al insertar programa.");			
			}
			$this->md5key = md5($this->id.$this->creation.$this->title.$this->creator.time().$_SERVER['REMOTE_ADDR'].time());
			$query = "UPDATE PROGRAM_2 SET MD5_KEY = '".$this->md5key."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
			$this->saved = true;			registerEvent( $_SESSION['user']->getID(), $this->id, "create" );
		}
	}
	
	function remove( $program ) {
		if ( ! $program->isSaved() ) return;
		registerEvent( $_SESSION['user']->getID(), $program->id, "delete" );
		$query = "UPDATE PROGRAM_2 SET VISIBILITY = '".BORRADO."' WHERE ID = '".$program->id."';";
		$_SESSION['bbdd']->query( $query );	}	function getID() {		return $this->id;	}		function getMD5Key() {		return $this->md5key;	}		function getVersion() {		return $this->version;	}
	
	function getVersionList() {
		$versions = $_SESSION['bbdd']->queryArray( "SELECT ID, DATE as fecha FROM SOURCE_CODE_2 WHERE PROGRAM='".$this->id."';" );
		$v = 1;
		foreach ( $versions as $version ) {
			echo "<a href='editor-codigo.php?id=".$this->md5key."&version=".$version['ID']."' title='".$version['fecha']."'>".$v."</a>&nbsp;";
			$v++;
		} 
	}		function getCreation() {		return $this->creation;	}		function getTitle() {		return $this->title;	}		function getViews() {		return $this->views;	}		function getVisibility() {		return $this->visibility;	}		function getVisibilityPhrase() {
		// 0 public, 1 private, 2 example, 3 reto, 4 run without code
		$texts = array("público", "privado", "ejemplo", "solución", "visible con código oculto");
		return $texts[$this->visibility];
	}

	function getCreator() {		return $this->creator;	}
	
	function getCreatorLink() {
		if ( $this->creator == "" ) return "";
		$creator = User::loadFromID( $this->creator );
		return $creator->getProfileLink();
	}	
	function getRoot( ) {
		return $this->root;
	}	function getIcon( ) {
		return $this->icon;
	}	function getVotes( ) {
		return $this->votes;
	}	function getRuns( ) {
		return $this->runs;
	}	function getTyping( ) {
		return $this->typing;
	}	function isSaved() {		return $this->saved;	}		function getCode() {//		return stripslashes($this->code);		return $this->code;	}		function getVersionCreation( ) {
		return $this->versionCreation;
	}	function getKeypoints() {
		$keypoints_list = $_SESSION['bbdd']->queryArray("SELECT KP.* FROM KEY_POINTS AS KP, SOURCE_CODE_2 AS SC2 WHERE SC2.PROGRAM='".$this->id."' AND KP.ID=SC2.KEYPOINTS");
		$sum = array();
		foreach ( $keypoints_list as $keypoints ) {
			$sum = $sum + $keypoints;
		}
		$result = array();
		foreach( $sum as $key => $value ) {
			if ( $value > 0 ) {
				$result[$key] = 1;
			} else {
				$result[$key] = 0;
			}
		}
		return $result;	}
	
	function isVotedBy( $user ) {
		$res = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM VOTES_2 WHERE USER='".$user->getID()."' AND PROGRAM='".$this->id."'");
		return $res == 1;
	}		function getURL( $version = null ) {
		// TODO: Según visibilidad devolver usa, crea o nada
		if ( !$this->saved ) return "";
		if ( !$version ) return "pagina/".$this->name;
		return "pagina/".$this->name."/".$version;
	}
		function setTitle( $title ) {		if ($this->title != $title) {			$this->title = $title;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE PROGRAM_2 SET TITLE = '".$this->title."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}

	function incViews() {
		$this->views++;
		if ( $this->saved ) {
			$query = "UPDATE PROGRAM_2 SET VIEWS = '".$this->views."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );			registerEvent( $_SESSION['user']->getID(), $this->id, "load" );
		}	}

	function vote( $user ) {
		if ( $this->saved ) {
			if ( $this->isVotedBy( $user ) ) {
				$query = "DELETE FROM VOTES_2 WHERE USER='".$user->getID()."' AND PROGRAM='".$this->getID()."'";
				$this->votes--;
				$diff = -1;
			} else {
				$query = "INSERT INTO VOTES_2 (USER, PROGRAM) VALUES ('".$user->getID()."', '".$this->getID()."')";
				$this->votes++;
				$diff = 1;
			}			$_SESSION['bbdd']->query( $query );			$query = "UPDATE PROGRAM_2 SET VOTES = VOTES + ".$diff." WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}
	}

	function incRuns() {
		$this->runs++;
		if ( $this->saved ) {
			$query = "UPDATE PROGRAM_2 SET RUNS = '".$this->runs."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );			registerEvent( $_SESSION['user']->getID(), $this->id, "run" );
		}	}

	function incTyping( $ms ) {
		$this->typing += $ms;
		if ( $this->saved ) {
			$query = "UPDATE PROGRAM_2 SET TYPING = '".$this->typing."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}

	function setVisibility( $visibility ) {		if ($this->visibility != $visibility) {			$this->visibility = $visibility;
		} else {
			return;
		}
		if ( $this->saved ) {
			$what = array( PUBLICO => "publish", PRIVADO => "hide", EVALUABLE => "protect", USABLE => "demo", SOLUCION => "test", EJEMPLO => "ejemplo");
			registerEvent( $_SESSION['user']->getID(), $this->id, $what[$this->visibility] );
			$query = "UPDATE PROGRAM_2 SET VISIBILITY = '".$this->visibility."' WHERE ID = '".$this->id."';";
			$_SESSION['bbdd']->query( $query );		}	}

	function setIcon( $icon ) {		if ($this->icon != $icon) {			$this->icon = $icon;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE PROGRAM_2 SET ICON = '".$this->icon."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}
	
	function setKeypoints( $keypoints ) {
		$kp = array(
		"CREATE_OBJECTS" => $keypoints['createObjects'] ? 1 : 0,		"DEFINE_CLASSES" => $keypoints['defineClasses'] ? 1 : 0,		"DEFINE_METHODS" => $keypoints['defineMethods'] ? 1 : 0,		"HAS_ARITHMETIC_EXPRESSION" => $keypoints['hasArithmeticExpression'] ? 1 : 0,		"HAS_ASSIGMENT" => $keypoints['hasAssignment'] ? 1 : 0,		"HAS_BOOLEAN_EXPRESSION" => $keypoints['hasBooleanExpression'] ? 1 : 0,		"HAS_CONSTANT" => $keypoints['hasConstant'] ? 1 : 0,		"HAS_DECLARATION" => $keypoints['hasDeclaration'] ? 1 : 0,		"HAS_DO_WHILE" => $keypoints['hasDoWhile'] ? 1 : 0,		"HAS_ELSE" => $keypoints['hasElse'] ? 1 : 0,		"HAS_FOR" => $keypoints['hasFor'] ? 1 : 0,		"HAS_IF" => $keypoints['hasIf'] ? 1 : 0,		"HAS_RECURSIVE_FUNCTION" => $keypoints['hasRecursiveFunction'] ? 1 : 0,		"HAS_SWITCH" => $keypoints['hasSwitch'] ? 1 : 0,		"HAS_WHILE" => $keypoints['hasWhile'] ? 1 : 0,		"RETURN_ARRAY" => $keypoints['returnArray'] ? 1 : 0,		"USE_ARRAY" => $keypoints['useArray'] ? 1 : 0,		"USE_ARRAY_AS_PARAMETER" => $keypoints['useArrayAsParameter'] ? 1 : 0,		"USE_FUNCTION" => $keypoints['useFunction'] ? 1 : 0,		"USE_LITERAL" => $keypoints['useLiteral'] ? 1 : 0);
		$query = "INSERT INTO KEY_POINTS ( 
										CREATE_OBJECTS,
										DEFINE_CLASSES,										DEFINE_METHODS,										HAS_ARITHMETIC_EXPRESSION,										HAS_ASSIGMENT,										HAS_BOOLEAN_EXPRESSION,										HAS_CONSTANT,										HAS_DECLARATION,										HAS_DO_WHILE,										HAS_ELSE,										HAS_FOR,										HAS_IF,										HAS_RECURSIVE_FUNCTION,										HAS_SWITCH,										HAS_WHILE,										RETURN_ARRAY,										USE_ARRAY,										USE_ARRAY_AS_PARAMETER,										USE_FUNCTION,										USE_LITERAL ) VALUES ('".($kp["CREATE_OBJECTS"])."', '".($kp["DEFINE_CLASSES"])."', '".($kp["DEFINE_METHODS"])."', '".($kp["HAS_ARITHMETIC_EXPRESSION"])."', '".($kp["HAS_ASSIGMENT"])."', '".($kp["HAS_BOOLEAN_EXPRESSION"])."', '".($kp["HAS_CONSTANT"])."', '".($kp["HAS_DECLARATION"])."', '".($kp["HAS_DO_WHILE"])."', '".($kp["HAS_ELSE"])."', '".($kp["HAS_FOR"])."', '".($kp["HAS_IF"])."', '".($kp["HAS_RECURSIVE_FUNCTION"])."', '".($kp["HAS_SWITCH"])."', '".($kp["HAS_WHILE"])."', '".($kp["RETURN_ARRAY"])."', '".($kp["USE_ARRAY"])."', '".($kp["USE_ARRAY_AS_PARAMETER"])."', '".($kp["USE_FUNCTION"])."', '".($kp["USE_LITERAL"])."');";
		$keypoints_id = $_SESSION['bbdd']->exec($query);

		if ( $keypoints_id == -1 ) 		{
			die("Program::setKeypoints() - Error al insertar keypoints. ".$query);					}
		return $keypoints_id;	}

	function setCode( $code, $keypoints ) {
//		$code = addslashes( $code );		if ($this->code != $code || $this->version == -1 ) {			$this->code = $code;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Crear entrada en keypoints
			$keypoints_id =	$this->setKeypoints( $keypoints );											
			// Crear nueva versión
			$this->versionCreation = date("Y-m-d H:i:s");
			
			$query = "INSERT INTO SOURCE_CODE_2 ( CODE, DATE, PROGRAM, KEYPOINTS ) VALUES ('".$this->code."', '".$this->versionCreation."', '".$this->id."', '".$keypoints_id."' );";
			$this->version = $_SESSION['bbdd']->exec($query);
			if ( !$this->version ) {
				die("Program::setCode - Fallo al insertar codigo");
			}			// Actualizar versión actual			$query = "UPDATE PROGRAM_2 SET CURRENT_VERSION = '".$this->version."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}
	
	function getVersions() {
		$query = "SELECT ID FROM SOURCE_CODE_2 WHERE PROGRAM='".$this->id."' ORDER BY DATE";
		$values = $_SESSION['bbdd']->queryArray($query);
		$result = array();
		foreach ( $values as $value ) {
			$result[] = $value['ID'];
		}
		return $result;
	}
}
class Challenge {	var $id; 
	var $md5key;	var $title; 	var $statement;	var $code;
	var $template;
	var $creation;
	var $difficulty;	var $creator;	
	var $image;

	var $saved;		function Challenge( $creatorid ) {
		$this->id = -1;
		$this->md5key = "";		$this->title = "";		$this->statement = "";
		$this->code = "";
		$this->template = "";				$this->creation = date("Y-m-d H:i:s");
		$this->difficulty = 1;
		$this->creator = $creatorid;		$this->image = null;
		$this->saved = false;				
	}			
	function createFromRow($row) {
		$challenge = new Challenge( $row['OWNER'] );
		$challenge->id = $row['ID'];
		$challenge->md5key = $row['MD5_KEY'];		$challenge->title = $row['TITLE'];		$challenge->statement = $row['STATEMENT'];		$challenge->code = $row['CODE']; 
		$challenge->template = $row['TEMPLATE']; 
		$challenge->creation = $row['DATE'];		$challenge->difficulty = $row['DIFFICULTY'];
		$challenge->creator = $row['OWNER'];		$challenge->image = $row['IMAGE'];		$challenge->saved = true;		return $challenge;	}
	
	function loadFromID( $id ) {		$query = "SELECT * FROM CHALLENGE_2 WHERE ID='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Challenge::createFromRow( $row );
	}	function loadFromMD5Key( $md5key ) {		$query = "SELECT * FROM CHALLENGE_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Challenge::createFromRow( $row );	}		function save( ) {		
		// Crear el md5
		if ($this->md5key != "") {
			die("Challenge::save - md5 recalculado");
		}
		// Crear programa si no existe aún		if ( !$this->saved ) {
			$query = "INSERT INTO CHALLENGE_2 
								( TITLE, STATEMENT, CODE, TEMPLATE, DATE, DIFFICULTY, OWNER, IMAGE ) 
						VALUES 	( '".$this->title."', '".$this->statement."', '".$this->code."', '".$this->template."', '".$this->creation."', '".$this->difficulty."', '".$this->creator."', '".$this->image."' );";
			$this->id = $_SESSION['bbdd']->exec( $query );
			if ( !$this->id ) {
				die("Challenge::save() - Error al insertar desafío.");			
			}
			$this->md5key = md5($this->id.$this->creation.$this->title.$this->statement.$this->code.$this->difficulty.	$this->creator.time().$_SERVER['REMOTE_ADDR'].time());
			$query = "UPDATE CHALLENGE_2 SET MD5_KEY = '".$this->md5key."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
			$this->saved = true;		}
	}
	
	function remove( $challenge ) {
		if ( ! $challenge->isSaved() ) return;
	}	function getID() {		return $this->id;	}		function getMD5Key() {		return $this->md5key;	}		function getTitle() {		return $this->title;	}		function getStatement() {		return $this->statement;	}		function getCode() {//		return stripslashes($this->code);		return $this->code;	}		function getTemplate() {		return $this->template;	}		function getCreation() {		return $this->creation;	}		function getDifficulty() {		return $this->difficulty;	}		function getCreator() {		return $this->creator;	}
	
	function getImage( ) {
		return $this->image;
	}
	
	function getTests( $testid = null ) {
		$anex = "";
		if ( $testid ) $anex = "AND ID='".$testid."'";
		$query = "SELECT ID, INPUT, HASH, OUTPUT, OUTPUT_IMAGE FROM TESTCASE_2 WHERE CHALLENGE = '".$this->id."' ".$anex;		return $_SESSION['bbdd']->queryArray( $query );				
	}	function isSaved() {		return $this->saved;	}		function setTitle( $title ) {		if ($this->title != $title) {			$this->title = $title;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CHALLENGE_2 SET TITLE = '".$this->title."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}

	function setStatement( $statement ) {//		$code = addslashes( $code );		if ($this->statement != $statement) {			$this->statement = $statement;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CHALLENGE_2 SET STATEMENT = '".$this->statement."' WHERE ID = '".$this->id."';";
			$_SESSION['bbdd']->query( $query );		}	}

	function setCode( $code ) {
//		$code = addslashes( $code );		if ($this->code != $code ) {			$this->code = $code;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CHALLENGE_2 SET CODE = '".$this->code."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

	function setTemplate( $template ) {
//		$template = addslashes( $template );		if ($this->template != $template ) {			$this->template = $template;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CHALLENGE_2 SET TEMPLATE = '".$this->template."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

	function setDifficulty( $difficulty ) {		if ($this->difficulty != $difficulty) {			$this->difficulty = $difficulty;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CHALLENGE_2 SET DIFFICULTY = '".$this->difficulty."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}
	
}

class ContestChallenge {
	var $id;
	var $md5key;
	var $contest;
	var $challenge;
	var $position;
	
	var $saved;
	
	function ContestChallenge( $contest, $challenge, $position = 0 ) {
		$this->id = -1;
		$this->md5key = "";
		$this->contest = $contest;
		$this->challenge = $challenge;
		$this->position = $position;
		$this->saved = false;
	}
	
	function createFromRow($row) {
		$cc = new ContestChallenge( Contest::loadFromID( $row['CONTEST']), Challenge::loadFromID( $row['CHALLENGE']), $row['POSITION'] );
		$cc->id = $row['ID'];
		$cc->md5key = $row['MD5_KEY'];		$cc->saved = true;		return $cc;	}
	
	function loadFromID( $id ) {		$query = "SELECT * FROM CONTEST_CHALLENGE_2 WHERE ID='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return ContestChallenge::createFromRow( $row );
	}	function loadFromMD5Key( $md5key ) {		$query = "SELECT * FROM CONTEST_CHALLENGE_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return ContestChallenge::createFromRow( $row );
	}
	
	function getID() {
		return $this->id;
	}
	
	function getMD5Key() {
		return $this->md5key;
	}
	
	function getContest() {
		return $this->contest;
	}
	
	function getChallenge() {
		return $this->challenge;
	}
	
	function getPosition() {
		return $this->position;
	}
	
	function getProgramBy( $user, $version = null ) {
		$query = "SELECT PROGRAM FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE='".$this->id."' AND USER='".$user->getID()."';";
		$programID = $_SESSION['bbdd']->queryValue($query);
		if ( ! $programID ) {
			return null;
		}		
		return Program::loadFromID( $programID, $version );
	}
	
	function getTries( $user ) {
		return $_SESSION['bbdd']->queryArray("SELECT * FROM CC_TRY_2 WHERE CONTEST_CHALLENGE='".$this->id."' AND USER='".$user."' ORDER BY DATE;");		
	}}class Contest {	var $id; 
	var $md5key;	var $title; 	var $description;	var $start;
	var $end;
	var $visibility;
	var $ranking;
	var $creator;	
	var $password;	var $reward;
	var $group;

	var $saved;		function Contest( $creatorid ) {
		$this->id = -1;
		$this->md5key = "";		$this->title = "";		$this->description = "";
		$this->start = "";
		$this->end = "";				$this->visibility = PRIVADO;
		$this->ranking = 0;
		$this->creator = $creatorid;		$this->password = "";
		$this->reward = -1;
		$this->group = 0;
				$this->saved = false;				
	}			
	function createFromRow($row) {
		$contest = new Contest( $row['OWNER'] );
		$contest->id = $row['ID'];
		$contest->md5key = $row['MD5_KEY'];		$contest->title = $row['TITLE'];		$contest->description = $row['DESCRIPTION'];		$contest->start = $row['START']; 
		$contest->end = $row['END']; 
		$contest->visibility = $row['VISIBILITY'];
		$contest->ranking = $row['RANKING'];
		$contest->creator = $row['OWNER'];		$contest->password = $row['PASSWORD'];		$contest->reward = $row['REWARD'];
		$contest->group = $row['GROUP_ID'];		$contest->saved = true;		return $contest;	}
	
	function loadFromID( $id ) {		$query = "SELECT * FROM CONTEST_2 WHERE ID='".$id."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Contest::createFromRow( $row );
	}	function loadFromMD5Key( $md5key ) {		$query = "SELECT * FROM CONTEST_2 WHERE MD5_KEY='".$md5key."';";		$row = $_SESSION['bbdd']->query($query);		if (!$row) {			return null;		}
		return Contest::createFromRow( $row );
	}		function save( ) {		
		// Crear el md5
		if ($this->md5key != "") {
			die("Contest::save - md5 recalculado");
		}
		// Crear reto si no existe aún		if ( !$this->saved ) {
			$query = "INSERT INTO CONTEST_2 
								( TITLE, DESCRIPTION, START, END, VISIBILITY, OWNER, PASSWORD, REWARD, GROUP_ID ) 
						VALUES 	( '".$this->title."', '".$this->description."', '".$this->start."', '".$this->end."', '".$this->visibility."', '".$this->creator."', '".$this->password."', '".$this->reward."', '".$this->group."' );";
			$this->id = $_SESSION['bbdd']->exec( $query );
			if ( !$this->id ) {
				die("Contest::save() - Error al insertar desafío.");			
			}
			$this->md5key = md5($this->id.$this->title.$this->description.$this->creator.$this->visibility.$_SERVER['REMOTE_ADDR'].time());
			$query = "UPDATE CONTEST_2 SET MD5_KEY = '".$this->md5key."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
			$this->saved = true;		}
	}
	
	function remove( $contest ) {
		if ( ! $contest->isSaved() ) return;
		// borrar de CONTEST_CHALLENGE_2
		$_SESSION['bbdd']->exec( "DELETE FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$contest->id."'" );
		// borrar de CONTEST_2
		$_SESSION['bbdd']->exec( "DELETE FROM CONTEST_2 WHERE ID='".$contest->id."'" );
	}		function getID() {		return $this->id;	}		function getMD5Key() {		return $this->md5key;	}		function getTitle() {		return $this->title;	}		function getDescription() {		return $this->description;	}		function getStart() {		return $this->start;	}		function getEnd() {		return $this->end;	}		function getVisibility() {		return $this->visibility;	}		function getRanking() {		return $this->ranking == 1;	}		function getCreator() {		return $this->creator;	}
	
	function getPassword() {		return $this->password;	}	
	function hasPassword() {
		return $this->password != "";
	}
	
	function hasUserRegistered( $user ) {
		// Buscar en BBDD
		$row = $_SESSION['bbdd']->query("SELECT * FROM CONTEST_USER_2 WHERE CONTEST='".$this->id."' AND USER='".$user->getID()."'");
		return $row != null;
	}
	
	function hasParticipant( $user ) {
		$n = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CC_PROGRAM_2 WHERE USER='".$user->getID()."' AND CONTEST_CHALLENGE IN ( SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$this->id."' )");
		return $n > 0;
	}
		function getReward( ) {
		return $this->reward;
	}	function getGroup() {
		return $this->group;
	}
	
	function isSaved() {		return $this->saved;	}
	
	function isOpen( ) {
		$start = strtotime( $this->getStart() );
		$end = strtotime( $this->getEnd() );
		$now = strtotime( date("Y-m-d H:i:s") );
		return ( $start <= $now && ( $end > $now || $end <= 0 ) );
	}
	
	function getParticipants() {
		$userids = $_SESSION['bbdd']->queryArray("SELECT DISTINCT(USER) FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE IN ( SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$this->id."' )");
		$result = array();
		foreach ($userids as $userid) {
			$result[] = User::loadFromID($userid['USER']);
		}
		return $result;
	}
	
	function getContestChallenges() {
		$chids = $_SESSION['bbdd']->queryArray("SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$this->id."' ORDER BY POSITION ASC");
		$result = array();
		foreach ($chids as $chid) {
			$result[] = ContestChallenge::loadFromID( $chid['ID'] );
		}
		return $result;
	}
	
	function getChallenges() {
		$chids = $_SESSION['bbdd']->queryArray("SELECT CHALLENGE FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$this->id."' ORDER BY POSITION ASC");
		$result = array();
		foreach ($chids as $chid) {
			$result[] = Challenge::loadFromID($chid['CHALLENGE']);
		}
		return $result;
	}
	
	function getNotIncludedChallenges() {
		$chids = $_SESSION['bbdd']->queryArray("SELECT ID FROM CHALLENGE_2 WHERE ID NOT IN (SELECT CHALLENGE FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$this->id."') AND OWNER='".$this->creator."' ORDER BY TITLE ASC");
		$result = array();
		foreach ($chids as $chid) {
			$result[] = Challenge::loadFromID($chid['ID']);
		}
		return $result;
	}
	
	// 0 No iniciada, 1 iniciada y fallada, 2 superada
	function challengeOvercome( $challenge, $user ) {
		$success = $_SESSION['bbdd']->queryValue( "SELECT COUNT(*) FROM CC_TRY_2 WHERE CONTEST='".$this->id."' AND CHALLENGE='".$challenge->getID()."' AND USER='".$user->getID()."' AND SUCCESS=1;" );
		$fail = $_SESSION['bbdd']->queryValue( "SELECT COUNT(*) FROM CC_TRY_2 WHERE CONTEST='".$this->id."' AND CHALLENGE='".$challenge->getID()."' AND USER='".$user->getID()."' AND SUCCESS=0;" );
		if ( $success + $fail == 0 ) return 0;
		if ( $success == 0 && $fail > 0) return 1;
		return 2;
	}	
	function getChallengeOvercome( $challenge, $user ) {
		$success = $_SESSION['bbdd']->queryArray( "SELECT * FROM CC_TRY_2 WHERE CONTEST='".$this->id."' AND CHALLENGE='".$challenge->getID()."' AND USER='".$user->getID()."' AND SUCCESS=1;" );
		if ( sizeof( $success ) > 0 ) {
			$program = Program::loadFromID( $success[0]['PROGRAM'], $success[0]['SOURCE_CODE'] );
			return $program;
		}
		return null;
	}
		function setTitle( $title ) {		if ($this->title != $title) {			$this->title = $title;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CONTEST_2 SET TITLE = '".$this->title."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}

	function setDescription( $description ) {		if ($this->description != $description) {			$this->description = $description;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CONTEST_2 SET DESCRIPTION = '".$this->description."' WHERE ID = '".$this->id."';";
			$_SESSION['bbdd']->query( $query );		}	}

	function setStart( $start ) {
		if ($this->start != $start ) {			$this->start = $start;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CONTEST_2 SET START = '".$this->start."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

	function setEnd( $end ) {
		if ($this->end != $end ) {			$this->end = $end;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CONTEST_2 SET END = '".$this->end."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

	function setVisibility( $visibility ) {		if ($this->visibility != $visibility) {			$this->visibility = $visibility;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CONTEST_2 SET VISIBILITY = '".$this->visibility."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );		}	}
	
	function setRanking( $ranking ) {		if ($this->ranking != $ranking) {			$this->ranking = $ranking;
		} else {
			return;
		}
		if ( $this->saved ) {
			$query = "UPDATE CONTEST_2 SET RANKING = '".$this->ranking."' WHERE ID = '".$this->id."';";
			$_SESSION['bbdd']->query( $query );		}	}

	function setPassword( $password ) {
		if ($this->password != $password ) {			$this->password = $password;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CONTEST_2 SET PASSWORD = '".$this->password."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}
	
	function registerUser( $user ) {
		$_SESSION['bbdd']->exec("INSERT INTO CONTEST_USER_2 (CONTEST, USER) VALUES ('".$this->id."', '".$user->getID()."')");
	}

	function deregisterUser( $user ) {
		$_SESSION['bbdd']->exec("DELETE FROM CONTEST_USER_2 WHERE CONTEST='".$this->id."' AND USER='".$user->getID()."'");
	}

	function setReward( $reward ) {
		if ($this->reward != $reward ) {			$this->reward = $reward;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CONTEST_2 SET REWARD = '".$this->reward."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

	function setGroup( $group ) {
		if ($this->group != $group ) {			$this->group = $group;
		} else {			
			return;
		}
		if ( $this->saved ) {
			// Actualizar versión actual			$query = "UPDATE CONTEST_2 SET GROUP_ID = '".$this->group."' WHERE ID = '".$this->id."';";			$_SESSION['bbdd']->query( $query );
		}	}

}?>