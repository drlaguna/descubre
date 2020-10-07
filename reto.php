<?php
include_once("common.php");

$contest = null;

if ( !$_SESSION['user']->isRegistered() ) {
	header("location:login.php?origin=".urlencode( currentURL() ) );
}

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

if ( isset( $_GET['id'] ) ) {	
	$_GET['id'] = mysql_real_escape_string($_GET['id']);
	$contest = Contest::loadFromMD5Key( $_GET['id'] ); 
	if ( !$contest ) {
		header("location:index.php");
		return;
	}
}

$anex = "";

if ( $contest->hasPassword() ) {
	if ( !$contest->hasUserRegistered( $_SESSION['user'] ) ) {
		header("location:registro-reto.php?id=".$contest->getMD5Key()."&origin=".urlencode( currentURL() ) );
		return;
	}
	// Sólo podemos abandonar el reto si aún no hemos hecho nada
	if ( ! $contest->hasParticipant( $_SESSION['user'] ) ) {
		$anex = ' <button class="btn btn-danger pull-right" id="buttonResign" data-toggle="tooltip" data-placement="bottom" title="Pulsa para abandonar este reto"><span class="glyphicon glyphicon-log-out"></span></button>';
	}
}

if ( ! $contest->isOpen() && $_SESSION['user']->getID() != $contest->getCreator() ) {
	header("location:retos.php");	
	return;
}


function ranking( $contest ) {
	// Penalización 20minutos por fallo si finalmente se resuelve
	$users = $_SESSION['bbdd']->queryArray("SELECT DISTINCT(USER) FROM CC_TRY_2 WHERE CONTEST='".$contest->getID()."' AND SUCCESS=1");	
	$novercomes = array();
	$time = array();
	$name = array();
	$id = array();
	$i = 0;
//	echo "Concurso ".$contest['ID']."<br/>";
	foreach ( $users as $user ) {
		$overcomes = $_SESSION['bbdd']->queryArray("SELECT * FROM CC_TRY_2 WHERE CONTEST='".$contest->getID()."' AND USER='".$user['USER']."' AND SUCCESS=1");	
		$novercomes[$i] = 0;
		$nfails = 0;
		$time[$i] = 0;
		$userObj[$i] = User::loadFromID( $user['USER'] );
//		echo $userObj[$i]->getName()."<br/>";
		$nsuccesses = 0;
		foreach ( $overcomes as $overcome ) {
			$nsuccesses++;
			$novercomes[$i]++;
			// Tiempo transcurrido desde comienzo de la prueba hasta primera solución en minutos
			$elapsed = intval( ( strtotime( $overcome['DATE'] ) - strtotime( $contest->getStart() ) ) / 60 );			
//			echo "Ejercicio ".$overcome['CONTEST_CHALLENGE']." ".$elapsed." minutos<br/>";
			$time[$i] += $elapsed;
			// Penalización de 20 minutos por fallo
			$nfails += $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM CC_TRY_2 WHERE CONTEST='".$contest->getID()."' AND USER='".$user['USER']."' AND CONTEST_CHALLENGE='".$overcome['CONTEST_CHALLENGE']."' AND DATE < '".$overcome['DATE']."' AND SUCCESS=0");	
		}
//		echo "fallos: ".$nfails." aciertos=".$nsuccesses;
		$time[$i] += $nfails*20;
		$i++;
	}	
	if ( $i > 0 ) {
		// Ordena los arrays en paralelo, los tres últimos los ordena según va haciendo los movimientos de los primeros
		array_multisort($novercomes, SORT_DESC, $time, SORT_ASC, $userObj);
		for ( $i = 0 ; $i < sizeof( $userObj ) ; $i++ ) {
			?>
			<tr><td><?php echo ($i+1); ?></td><td><?php echo $userObj[$i]->getProfileLink(); ?></td><td><?php echo $novercomes[$i]; ?></td><td><?php echo $time[$i]; ?></td></tr>		
			<?php
		}
	}
}

function listado( $contest ) {

	$challenges = $contest->getContestChallenges();
	$i = 1;
	foreach ( $challenges as $cc ) {
		$challenge = $cc->getChallenge();
		$link = 'prueba.php?id='.$cc->getMD5Key();
		$title = $challenge->getTitle();
		$statement = str_replace( "\n", "<br/>", $challenge->getStatement() );
		$statement = html_entity_decode( $statement );
		$state = $contest->challengeOvercome( $challenge, $_SESSION['user'] );
		$label = "";
		if ( $state == 1 ) {
			$label = '<span class="glyphicon glyphicon-unchecked pull-right" aria-hidden="true" > Prueba iniciada</span>';
		}
		if ( $state == 2 ) {
			$label = '<span class="glyphicon glyphicon-check pull-right" aria-hidden="true" style="color: #907b32;" > Prueba superada</span>';
		}		
?>
		<div class="list-group">
			<a href="<?php echo $link; ?>" class="list-group-item active">
				<h4 class="list-group-item-heading"><?php echo $i.". ".$title; ?></h4>
			</a>
			<p class="list-group-item">
				<?php echo $statement; ?>
				<div class="form-group text-info list-group-item">
					<span class="glyphicon glyphicon-signal" aria-hidden="true" title="dificultad"></span> <?php echo $challenge->getDifficulty(); ?>
					<?php echo $label; ?>
				</div>
			</p>
		</div>
<?php
	$i++;
	}
}

makeHeader("Descubre la Programación : Reto ", "Resuelve todos los problemas de este reto", "retos", false);

?>
<div class="container-fluid">
	<div class="col-md-12">
		<h2><?php echo $contest->getTitle().$anex; ?></h2>
		<div class="panel panel-primary">
		  <div class="panel-body">
			<?php echo str_replace( "\n", "<br/>", $contest->getDescription()); ?>
		  </div>
		</div>
	</div>
	<div class="col-md-6">
		<h3>Problemas</h3>
	<?php
		listado( $contest );
	?>
	</div>
	<?php
		if ( $contest->getRanking() ) {
	?>
	<div class="col-md-6">
		<h3>Ranking</h3>
	
		<div class="panel panel-primary">
		
		  <table class="table">
			<tr><th>Posición</th><th>Usuario</th><th>Pruebas superadas</th><th>Tiempo empleado (min)</th></tr>
			<?php
			ranking( $contest );
			?>
		  </table>
		</div>

		<div class="panel panel-primary">
		  <div class="panel-heading"><strong>Notas aclaratoria</strong></div>
		  <div class="panel-body">
				Se clasifican en primer lugar los usuarios que hayan resuelto correctamente más problemas. A igualdad de problemas resueltos lo que cuenta es el menor tiempo para resolverlos. El tiempo para cada problema resuelto es el trascurrido desde el inicio del concurso hasta que se evalúa correctamente por primera vez. Pero cada vez que se evalúa de forma incorrecta se suma una penalización de 20 minutos.
		  </div>
		</div>

	</div>
	<?php
		}
	?>
</div>
<?php
	makeFooter();
?>
</body>
<script>
var cid = <?php echo $contest->getID(); ?>;

$(document).ready(
function() {
	$( "#buttonResign" ).on("click",  
		function ( event ) {
			$.post( "services/contests.php", { service: "leave", id: cid},
				function( res ) {
					console.log(res);
			    	try {
				    	var response = JSON.parse( res );
				    	if ( response.id != -1 ) {
				    		console.log(response);
							window.location = "retos.php";
				    	}
			    	} catch ( e ) {
			    		console.log(e, res);
			    		alert(  "Error al comprobar los tests:" + res );
			    	}
				}
			);
		}
	);

	setTimeout(function(){
   		window.location.reload(1);
	}, 60000);
});
</script>
</html>
