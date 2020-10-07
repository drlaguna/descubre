<?php
	define("DESCUBRE_URL", "http://descubre.inf.um.es");
//  Modo debug
//	error_reporting(E_ALL); 
//	ini_set('display_errors', 1); 

	include_once("bbdd.php");
	include_once("classes.php");
	include_once("admin/utils.php");
//	ini_set('session.cookie_lifetime', 7200);
	session_start();
	if ( !isset( $_SESSION['bbdd'] ) || !$_SESSION['bbdd']->connected() ) {
		$_SESSION['bbdd'] = new BBDD( );
	}
	if (!isset($_SESSION['user'])) {
		$_SESSION['user'] = new User();
	}

	// http://codegolf.stackexchange.com/questions/20363/shortest-way-to-generate-uuids-version-3-4-and-5-in-php
	function uuid($v=4,$d=null,$s=false)//$v-> version|$data-> data for version 3 and 5|$s-> add salt and pepper
	{
	    switch($v.($x=''))
	    {
	        case 3:
	            $x=md5($d.($s?md5(microtime(true).uniqid($d,true)):''));break;
	        case 4:default:
	            $v=4;for($i=0;$i<=30;++$i)$x.=substr('1234567890abcdef',mt_rand(0,15),1);break;
	        case 5:
	            $x=sha1($d.($s?sha1(microtime(true).uniqid($d,true)):''));break;
	    }
	    return preg_replace('@^(.{8})(.{4})(.{3})(.{3})(.{12}).*@','$1-$2-'.$v.'$3-'.substr('89ab',rand(0,3),1).'$4-$5',$x);
	}

	function currentURL() {
		return strlen($_SERVER['QUERY_STRING']) ? basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'] : basename($_SERVER['PHP_SELF']);
	}	

	function loginAndBack() {
		return "<a href='index.php?seccion=login&origin=".urlencode( currentURL() )."'>formulario de acceso</a>";
	}
	
	function logoutAndBack() {
		return "<a href='index.php?seccion=logout&origin=".urlencode( currentURL() )."'>formulario de acceso</a>";
	}
	
	function makeHeader($title, $description, $active, $ijava = false) {
		include "admin/header.php";		
		include "admin/topmenu.php";
	}
	
	function makeFooter() {
	?>
		&nbsp;
		<div class="container-fluid footer">
			<div class="row">
				<div class="col-md-6">
					<a href="http://www.um.es/"><img src="images/logo_umu.png" height="54"  title="Universidad de Murcia"/></a>              
					<a href="http://www.um.es/informatica/"><img src="images/logo_fi.png" height="54"  title="Facultad de Inform&aacute;tica de la Universidad de Murcia"/> </a>
					<a href="http://olimpiada.inf.um.es"><img src="images/logo_oirm.png" height="54" title="Olimpiada Inform&aacute;tica de la Regi&oacute;n de Murcia"/></a>
				</div>		
				<div class="col-md-6">
					<p class="pull-right">
						<a href="explora.php">Explora</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="aprende.php">Aprende</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="editor-codigo.php">Crea</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="retos.php">Retos</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="el-proyecto.php">El Proyecto</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="terminos-y-condiciones.php">T&eacute;rminos y condiciones</a>&nbsp;&nbsp;&Iota;&nbsp;&nbsp;
						<a href="politica-privacidad.php">Pol&iacute;tica de privacidad</a>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<p class="pull-right">&#169; 2015 Universidad de Murcia - Facultad de Inform&aacute;tica</p>				
				</div>
			</div>
		</div>
	    <script>
		$(document).ready(
		function() {
			$('[data-toggle="tooltip"]').tooltip()
		});
	    </script>
	<?php
	}
	
	function ErrorPage($description, $msg) {
		makeHeader("Facultad de Informatica :: Error", $description);
		?>
		<div class="contenido">
		<?php
		echo "<h1>".$description."</h1>";
		echo "<h2>".$msg."</h2>";
		?>
		</div>
		<?php
		include "admin/footer.php";
	}
	
	function makePage($title, $body, $description = "", $metas = "") {
		makeHeader($title, $description, $metas);
		echo $body;
		include "admin/footer.php";		
	}
	
	function workload($from, $to, $user = null, $program = null) {
		$ofrom = $from;
		$from = new DateTime('@'.$from);
		$to = new DateTime('@'.$to);
//		echo "From: ".$from->format("Y-m-d H:i:s")."<br/>";
//		echo "To: ".$to->format("Y-m-d H:i:s")."<br/>";
		$anex = "event.MOMENT >= '".($from->format("Y-m-d H:i:s"))."' AND event.MOMENT <= '".($to->format("Y-m-d H:i:s"))."'";
		if ($user != null) {
			$anex .= " AND USER='".$user."' ";
		}
		if ($program != null) {
			$anex .= " AND PROGRAM = '".$program."' ";
		}					
//		$query = "SELECT user.NAME as name, user.EMAIL as email, program.MD5_KEY as program_md5key, event.WHAT as what, program.TITLE as title, event.MOMENT as moment FROM EVENT_2 as event, PROGRAM_2 as program, USER_2 as user WHERE event.USER=user.ID ".$anex." ORDER BY event.MOMENT ASC LIMIT 300";
		$query = "SELECT * FROM EVENT_2 as event WHERE ".$anex." ORDER BY event.MOMENT ASC";
//		echo $query;
		$rows = $_SESSION['bbdd']->queryArray($query);
//		echo "Filas: ".sizeof($rows)."<br/>";
		$dates = array();
		if ( sizeof($rows ) == 0 ) return $dates;
		$total = 0;
		$start = 0;
		$end = 0;
		$obj = DateTime::createFromFormat("Y-m-d H:i:s", $rows[0]['MOMENT']);


		$moment = $obj->getTimestamp();
//intval( ( strtotime( $overcome['DATE'] ) - strtotime( $contest['START'] ) ) / 60 );		
		$start = $moment;
		$end = $start;
		$day = date("d-m-Y", $moment);
		$curdate =$ofrom;
		$nextdate = mktime(0,0,0, date("n", $moment), date("j", $moment), date("Y", $moment));
		while ($curdate < $nextdate) {
			$dates[date("d-m-Y", $curdate)] = 0;
			$curdate += 86400;
		}
		$dates[$day] = 0;
		$i = 1;
		foreach ( $rows as $row ) {
			$obj = DateTime::createFromFormat("Y-m-d H:i:s", $row['MOMENT']);
			$moment = $obj->getTimestamp();
			if ($moment-$end <= 600) {
				$end = $moment;
			} else {
				$inc = ($end-$start)+300;
				$total += $inc;
				
				$start = $moment;
				$end = $start;
				
				$dates[$day] += $inc;
			}
			$day = date("d-m-Y", $moment);
			if (!array_key_exists($day, $dates)) {
				$curdate = $curdate+86400;
				$nextdate = mktime(0,0,0, date("n", $moment), date("j", $moment), date("Y", $moment));
				while ($curdate < $nextdate) {
					$dates[date("d-m-Y", $curdate)] = 0;
					$curdate += 86400;
				}
				
				$dates[$day] = 0;
			}	
			$i++;	
		}
		$inc =($end-$start)+300;
		$dates[$day] += $inc;
		
		return $dates;
	}
	
	// Recibe cadena con fecha en formato yyyy-mm-dd hh:mm:ss y devuelve cadena con fecha en formato dd/mm/yyyy hh:mm
	function date2Human( $date ) {
		$timestamp = strtotime( $date );
		if ($timestamp <= 0) return "";
		return date("d/m/Y H:i", $timestamp);
	}
	
	// Recibe cadena con fecha en formato dd/mm/yyyy hh:mm y devuelve cadena con fecha en formato yyyy-mm-dd hh:mm:ss
	function human2Date( $date ) {
		$partes = explode( " ", $date);
		$fecha = explode("/", $partes[0]);
		$hora = explode(":", $partes[1]);
		$timestamp = strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]." ".$partes[1]);
		return date("Y-m-d H:i:s", $timestamp);
	}

	function registerEvent( $userid, $programid, $what ) {
		if ( $what == "" ) return;
		if ( $userid == -1 ) return;
		$query = "INSERT INTO EVENT_2 (USER, PROGRAM, MOMENT, WHAT) VALUES ('".$userid."', '".$programid."', '".date("Y-m-d H:i:s")."', '".$what."')";
		$_SESSION['bbdd']->exec($query);
	}
	
?>