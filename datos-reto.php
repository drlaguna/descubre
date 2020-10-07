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
	$contest = Contest::loadFromMD5Key( $_GET['id'] ); 
	if ( !$contest ) {
		header("location:index.php");
		return;
	}
}

$anex = "";

if ( $_SESSION['user']->getID() != $contest->getCreator() && ! $_SESSION['user']->isAdmin() ) {
	header("location:index.php");	
	return;
}


function resultados( $contest ) {
	$users = $contest->getParticipants();
	if ( sizeof( $users )  == 0 ) return;
	$ccs = $contest->getContestChallenges();
	$header = "";
	$nccs = sizeof( $ccs );
	for ( $i = 0 ; $i < $nccs ; $i++ ) {
		$header.="<th>Prueba ".($i+1)."</th>";
	}
	$header.="<th>Total</th>";
?>
			<table class="table">
				<tr><th>Usuario</th><?php echo $header; ?></tr>
<?php	
	foreach ( $users as $user ) {
		$row = "";
		$ok = 0;
		foreach ( $ccs as $cc ) {
			$program = $cc->getProgramBy( $user );
			if ( $program ) {
				$state = $contest->challengeOvercome( $cc->getChallenge(), $user );
				$icon = "";
				if ( $state == 0 ) {
					$row .= '<td>&nbsp;&nbsp;&nbsp;<a href="prueba.php?id='.$cc->getMD5Key().'&user='.$user->getMD5Key().'"><span class="glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="bottom" title="Pulsa para ver el código"></span></a></td>';
				}				
				if ( $state == 1 ) {
					$icon = '<span class="glyphicon glyphicon-unchecked" data-toggle="tooltip" data-placement="bottom" title="Prueba iniciada pero no superada" ></span>';
					$row .= '<td>'.$icon.'&nbsp;&nbsp;&nbsp;<a href="prueba.php?id='.$cc->getMD5Key().'&user='.$user->getMD5Key().'"><span class="glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="bottom" title="Pulsa para ver el código"></span></a></td>';
				}
				if ( $state == 2 ) {
					$icon = '<span class="glyphicon glyphicon-check" data-toggle="tooltip" data-placement="bottom" title="Prueba superada" ></span>';
					$row .= '<td>'.$icon.'&nbsp;&nbsp;&nbsp;<a href="prueba.php?id='.$cc->getMD5Key().'&user='.$user->getMD5Key().'&first-solution=true"><span class="glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="bottom" title="Pulsa para ver el código con el que superó la prueba"></span></a></td>';
					$ok++;
				}
			} else {
				$row .= '<td></td>';
			}
		}		
		$row .= "<td>".$ok."/".$nccs."</td>";
		?>
				<tr><td><?php echo $user->getName(); ?></td><?php echo $row; ?></tr>		
		<?php
	}
	?>
			</table>
		<?php
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
	<div class="col-md-12">
		<h3>Resultados</h3>
	
		<div class="panel panel-primary">
		
			<?php
			resultados( $contest );
			?>
		</div>
	</div>

	<div class="col-md-12">
		<h3>Problemas</h3>
	<?php
		listado( $contest );
	?>
	</div>
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
