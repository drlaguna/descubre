<?php
include_once("common.php");

function listadoProgramas( $user, $programs, $publicProfile = true ) {
	foreach ( $programs as $p ) {
		$program = Program::loadFromMD5Key( $p['MD5_KEY'] );
		if ( !$program ) {
			echo "Fallo al cargar id ".$p['MD5_KEY']."<br/>";
			continue;
		}
		if ( $program->getVisibility() == 4 && $publicProfile ) {
			$link = 'muestra.php?id='.$program->getMD5Key();
		} else {
			$link = 'editor-codigo.php?id='.$program->getMD5Key();
		}
		$fechac = explode(" ", $program->getCreation());
		$fecham = explode(" ", $program->getVersionCreation());
		$typing = $program->getTyping();
		
?>
		<div class="list-group" id="lg<?php echo "-".$program->getMD5KEy(); ?>">
			<a href="<?php echo $link; ?>" class="list-group-item active">
				<h4 class="list-group-item-heading"><?php echo $program->getTitle(); ?></h4>
			</a>						
			<div class="form-group text-info list-group-item">
				<span class="glyphicon glyphicon-refresh" aria-hidden="true" title="modificado"></span> <?php echo $fecham[0]; ?>
				&nbsp;
				<span class="glyphicon glyphicon-ok" aria-hidden="true" title="votos"></span> <?php echo $program->getVotes(); ?> votos
				&nbsp;
				<span class="glyphicon glyphicon-download" aria-hidden="true" title="descargas"></span> <?php echo $program->getViews(); ?> descargas
				&nbsp;
				<span class="glyphicon glyphicon-play" aria-hidden="true" title="usos"></span> <?php echo $program->getRuns(); ?> usos
				&nbsp;
				<span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true" title="creado">&nbsp;<?php echo $fechac[0]; ?></span> 
<?php
//			if ( $_SESSION['user']->isTutorOf( $user ) || $_SESSION['user']->isAdmin( ) ) {
?>
<?php
		if ( !$publicProfile || $_SESSION['user']->isTutorOf( $user ) ) {
?>
					<a class="alert-link text-danger" role="button" href="trabajo.php?user=<?php echo $user->getMD5Key().'&program='.$program->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="Estadísticas del programa"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Estadísticas</a>
<?php
			}
?>
			</div>
<?php
		if ( !$publicProfile ) {
?>
			<div class="list-group-item " data-toggle="buttons" id="<?php echo $program->getMD5Key(); ?>">
				<label class="myrbtn btn btn-primary <?php if ( $program->getVisibility() == PUBLICO ) echo 'active'; ?>" data-toggle="tooltip" data-placement="left" title="Publica el programa en explora">
					<input type="radio" value=<?php echo PUBLICO; ?> autocomplete="off" <?php if ( $program->getVisibility() == PUBLICO ) echo 'checked'; ?> ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
				</label>
				<label class="myrbtn btn btn-primary <?php if ( $program->getVisibility() == PRIVADO ) echo 'active'; ?>" data-toggle="tooltip" data-placement="bottom" title="El programa no aparecerá en explora">
					<input type="radio" value=<?php echo PRIVADO; ?> autocomplete="off" <?php if ( $program->getVisibility() == PRIVADO ) echo 'checked'; ?>><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
				</label>
				<label class="myrbtn btn btn-primary <?php if ( $program->getVisibility() == USABLE ) echo 'active'; ?>" data-toggle="tooltip" data-placement="bottom" title="Publica el programa en explora ocultando el código">
					<input type="radio" value=<?php echo USABLE; ?> autocomplete="off" <?php if ( $program->getVisibility() == USABLE ) echo 'checked'; ?>><span class="glyphicon glyphicon-icono-play-code-lock" aria-hidden="true"></span>
				</label>
				
<?php
			if ( $_SESSION['user']->hasTutor() && false ) {
?>
				<label class="myrbtn btn btn-primary <?php if ( $program->getVisibility() == EVALUABLE ) echo 'active'; ?>" data-toggle="tooltip" data-placement="right" title="Visible sólo para el profesor">
					<input type="radio" value=<?php echo EVALUABLE; ?> autocomplete="off" <?php if ( $program->getVisibility() == EVALUABLE ) echo 'checked'; ?>><span class="glyphicon glyphicon-education" aria-hidden="true"></span>
				</label>
<?php
			}
?>				
<?php
			if ( $_SESSION['user']->isAdmin()) {
?>
				<label class="myrbtn btn btn-primary <?php if ( $program->getVisibility() == EJEMPLO ) echo 'active'; ?>" data-toggle="tooltip" data-placement="right" title="Programa de ejemplo, público pero no listado en explora">
					<input type="radio" value=<?php echo EJEMPLO; ?> autocomplete="off" <?php if ( $program->getVisibility() == EJEMPLO ) echo 'checked'; ?>><span class="glyphicon glyphicon-book" aria-hidden="true"></span>
				</label>
<?php
			}
?>				
				
				
			  	<button type="button" class="my-dlt-program-btn btn btn-default pull-right"  data-toggle="tooltip" data-placement="left" title="Borra el programa" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
				
			</div>
<?php
		}
?>
		</div>
<?php
	}
}


function listadoRetos( $user ) {
	$visibility = PRIVADO;
	$contests = $_SESSION['bbdd']->queryArray("SELECT * FROM CONTEST_2 WHERE (END <> NOW() OR END = '0000-00-00') AND OWNER='".$user->getID()."' ORDER BY END ASC");
	foreach ( $contests as $c ) {
		$contest = Contest::loadFromID( $c['ID'] );
		$title = $contest->getTitle();
		$description = str_replace( "\n", "<br/>", $contest->getDescription());
		if ( $contest->hasPassword() ) {
			$title .= '<span class="glyphicon glyphicon-lock pull-right" data-toggle="tooltip" data-placement="bottom" title="Este reto requiere inscripción previa">&nbsp;</span>';
		}
		if ( $contest->getVisibility() == PRIVADO ) {
			$title .= '&nbsp;<span class="glyphicon glyphicon-eye-close pull-right" data-toggle="tooltip" data-placement="bottom" title="Reto privado">&nbsp;</span>';			
		}
		$start = strtotime( $contest->getStart() );
		$end = strtotime( $contest->getEnd() );
		$userList = $contest->getParticipants();
		$users = sizeof($userList);
//		$users = $_SESSION['bbdd']->queryValue("SELECT COUNT(DISTINCT USER) FROM CC_TRY_2 WHERE CONTEST='".$contest->getID()."'");
		$color = "list-group-item-info";
		$link = 'reto.php?id='.$contest->getMD5Key();
		if ( $contest->isOpen() ) {
			$color = "list-group-item-primary";
		} else {
			if ( $end > 0 && $end < strtotime( date("Y-m-d H:i:s") ) ) {
				$color = "list-group-item-danger";
			}
		}
		$linkstats = 'datos-reto.php?id='.$contest->getMD5Key();
		$linkedit = 'editor-reto.php?id='.$contest->getMD5Key();
		
?>
		<div class="list-group" id="lg-reto-<?php echo $contest->getID(); ?>">
			<a href="<?php echo $link; ?>" class="list-group-item active <?php echo $color; ?>">
				<h4 class="list-group-item-heading"><?php echo $title; ?></h4>
			</a>
			<p class="list-group-item">
				<?php echo $description; ?>
				<div class="list-group-item">
					<div class="form-group text-info">
<?php	if ( $start > 0 ) {	?>
						<span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Fecha de inicio del reto"> <?php echo date2Human( $contest->getStart() ); ?></span>
						&nbsp;
<?php	}					?>
<?php	if ( $end > 0 ) {	?>
						<span class="glyphicon glyphicon-calendar" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Fecha de fin del reto"> <?php echo date2Human( $contest->getEnd() ); ?></span>
<?php	}					?>
						&nbsp;
<?php	if ( $users > 0 ) {	?>					
						<span class="glyphicon glyphicon-user " aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="participantes"> <?php echo $users; ?></span> 
<?php	}					?>
						<div class="btn-group pull-right">
							<a class="btn btn-default" href="<?php echo $linkstats; ?>"><span class="glyphicon glyphicon-stats" data-toggle="tooltip" data-placement="bottom" title="Resultados reto"></span></a>					
							<a class="btn btn-default" href="<?php echo $linkedit; ?>"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="bottom" title="Modificar reto"></span></a>
							<a class="my-dlt-contest-btn btn btn-default" href="#" data-contest-id="<?php echo $contest->getID(); ?>" ><span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Borrar reto"></span></a>
						</div>
					</div>
				</div>
			</p>
		</div>
<?php
	}
}

function listadoGrupos( $user, $in ) {
	$visibility = PRIVADO;
	$groups = null;
	$title = "";
	if ( $in ) {
		$groups = $_SESSION['bbdd']->queryArray("SELECT ID FROM GROUP_2 WHERE ID <> 1 AND ID IN (SELECT GROUP_ID FROM GROUP_USER_2 WHERE USER='".$user->getID()."') ORDER BY NAME ASC");
		$title = "Grupos a los que pertenezco";
		$listgroupid = "lg-group-i-belong";
	} else {
		$groups = $_SESSION['bbdd']->queryArray("SELECT ID FROM GROUP_2 WHERE ID <> 1 AND ID NOT IN (SELECT GROUP_ID FROM GROUP_USER_2 WHERE USER='".$user->getID()."') ORDER BY NAME ASC");
		$title = "Grupos a los que puedo unirme";
		$listgroupid = "lg-group-i-dont-belong";
	}
	if ( sizeof($groups) == 0 ) return false;	
?>
		<div class="list-group" id="<?php echo $listgroupid; ?>">
			<span class="list-group-item active" >
			<h4 class="list-group-item-heading"><?php echo $title; ?></h4>
			</span>
<?php
	foreach ( $groups as $gid ) {
		$group = Group::loadFromID( $gid['ID'] );

		$link = "";
		$anex = "";
		$tutor = User::loadFromID( $group->getCreator() );
		$name = $group->getName()." - Tutor '".$tutor->getName()."'";
		
		if ( $in ) {
			$link = 'registro-grupo.php?action=leave&id='.$group->getMD5Key();		
			$anex = '<span class="glyphicon glyphicon-log-out" data-toggle="tooltip" data-placement="bottom" title="Pulsa para abandonar este grupo"></span>';
		} else {
			if ( $group->hasPassword() ) {
				$name .= ' <span class="glyphicon glyphicon-lock pull-right" data-toggle="tooltip" data-placement="bottom" title="Para apuntarse a este grupo es necesario saber la contraseña"></span>';
			}
			$link = 'registro-grupo.php?action=join&id='.$group->getMD5Key();	
			$anex = '<span class="glyphicon glyphicon-log-in" data-toggle="tooltip" data-placement="bottom" title="Pulsa para unirte a este grupo"></span>';
		}
		$link .= "&origin=".urlencode( currentURL() );

		
?>
				<div class="list-group-item" id="lg-group-i-belong-<?php echo $group->getID(); ?>">
					<div class="form-group text-info">
						<?php echo $name; ?>
<?php			if ( $user->getID() != $group->getCreator() ) {			?>
						<div class="btn-group pull-right">
							<a class="btn btn-default" href="<?php echo $link; ?>"><?php echo $anex; ?></a>
						</div>
<?php			}														?>
					</div>
				</div>


<?php
	}
?>
		</div>
<?php
	return true;
}

function misGrupos( $user, $publicProfile = false ) {
	$groups = null;
	$title = "Mis grupos";
	if ( $publicProfile ) {
		$groups = $_SESSION['bbdd']->queryArray("SELECT ID FROM GROUP_2 WHERE ID <> 1 AND VISIBILITY=".PUBLICO." AND OWNER='".$user->getID()."' ORDER BY NAME ASC");
	} else {
		$groups = $_SESSION['bbdd']->queryArray("SELECT ID FROM GROUP_2 WHERE ID <> 1 AND OWNER='".$user->getID()."' ORDER BY NAME ASC");
	}
	if ( sizeof($groups) == 0 ) return;	
?>
		<div class="list-group" id="myGroups">
			<span class="list-group-item active" >
			<h4 class="list-group-item-heading"><?php echo $title; ?></h4>
			</span>
<?php
	foreach ( $groups as $gid ) {
		$group = Group::loadFromID( $gid['ID'] );

		$link = "datos-grupo.php?id=".$group->getMD5Key();
		$linkedit = "editor-grupo.php?id=".$group->getMD5Key();
		$linkstats = "datos-grupo.php?id=".$group->getMD5Key();
		$name = $group->getName();

		
?>
				<div class="list-group-item" id="lg-group-<?php echo $group->getID(); ?>">
					<div class="form-group text-info">
						<?php echo $name; ?>
						<div class="btn-group pull-right">
							<a class="btn btn-default" href="<?php echo $linkstats; ?>"><span class="glyphicon glyphicon-user" data-toggle="tooltip" data-placement="bottom" title="Componentes del grupo"></span></a>
							<a class="btn btn-default" href="<?php echo $linkedit; ?>"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="bottom" title="Modificar grupo"></span></a>
							<a class="my-dlt-group-btn btn btn-default" href="#" data-group-id="<?php echo $group->getID(); ?>" ><span class="glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" title="Borrar grupo"></span></a>
						</div>
					</div>
				</div>
<?php
	}
?>
		</div>
<?php
}

function tusGrupos( $user, $viewer ) {
	$groups = $_SESSION['bbdd']->queryArray("SELECT ID FROM GROUP_2 WHERE ID <> 1 AND OWNER='".$user->getID()."' ORDER BY NAME ASC");
	if ( sizeof($groups) == 0 ) return;	
?>
		<div class="list-group">
<?php
	foreach ( $groups as $gid ) {
		$group = Group::loadFromID( $gid['ID'] );

		$name = $group->getName();
		$link = "";
		$text = "";
		$icon = "";
		if ( $group->includes( $viewer ) ) {
			$link = 'registro-grupo.php?action=leave&id='.$group->getMD5Key();		
			$text = "Pulsa para abandonar este grupo";
			$icon = "glyphicon-log-out";
		} else {
			$link = 'registro-grupo.php?action=join&id='.$group->getMD5Key();		
			$text = "Pulsa para unirte este grupo";
			$icon = "glyphicon-log-in";
			if ( $group->hasPassword() ) {
				$name .= ' <span class="glyphicon glyphicon-lock " data-toggle="tooltip" data-placement="bottom" title="Para apuntarse a este grupo es necesario saber la contraseña"></span>';
			}
		}
		
?>
				<div class="list-group-item">
					<div class="form-group text-info">
						<?php echo $name; ?>
						<div class="btn-group pull-right">
							<a class="btn btn-default" href="<?php echo $link; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $text; ?>"><span class="glyphicon <?php echo $icon; ?>" ></span></a>
						</div>
					</div>
				</div>
<?php
	}
?>
		</div>
<?php
}


$user = null;

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

if ( isset( $_GET['id'] ) ) {
	$_GET['id'] = mysql_real_escape_string($_GET['id']);
	$user = User::loadFromMD5Key( $_GET['id'] );
	if ( !$user ) {
		header("location:index.php");
		return;
	}
}

$publicProfile = true;

if ( $user->getID() == $_SESSION['user']->getID() || $_SESSION['user']->isAdmin() ) {
	$publicProfile = false;
}

if ( !$publicProfile && !$user->hasAgreed() ) {
	header("location:rgpd.php?id=".$user->getMD5Key());
	return;
}

makeHeader("Descubre la Programación : Perfil ", "Consulta tus programas, tus datos y tu progreso", "perfil", false);
?>
<style>
.myrbtn, .my-dlt-program-btn {
	color: #000; /*286090; */
	background: #FFF;
}

.nav-tabs{
  background-color:#fff7e2;
  font-size:16px;
}
.nav-tabs > li > a {
  border: medium none;
  color: #403e38;
}
.nav-tabs > li > a:hover{
  background-color: #6a8599 !important;
    border: medium none;
    color:#fff;
}
.nav-tabs > li.active > a,
.nav-tabs > li.active > a:focus,
.nav-tabs > li.active > a:hover{
    border: medium none;
    background-color: #2e6ca2 !important;
    color: #fff7e2;
}
</style>

<?php 	if ( !$publicProfile ) {					?>
<div class="container-fluid">
	<div class="col-md-4">
		<h3><?php echo $user->getName(); ?></h3>
		<h4>Alias: <?php echo $user->getUsername(); ?></h4>
		<h4>e-mail: <a href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></h4>
		<h4>Experiencia: <?php echo $user->getExperience();?> puntos</h4>
		<div class="btn-group btn-group" role="group" aria-label="...">
			<a class="btn btn-primary" role="button" href="editor-usuario.php?id=<?php echo $user->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="Modifica tus datos personales o contraseña"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Perfil</a>
			<a class="btn btn-primary" role="button" href="progreso.php?id=<?php echo $user->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="progreso del usuario"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Progreso</a>
			<a class="btn btn-primary" role="button" href="trabajo.php?user=<?php echo $user->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="Estadísticas del usuario"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Estadísticas</a>
		</div>
	</div>
	<div class="col-md-8">
		<ul class="nav nav-tabs nav-perfil" role="tablist">
		    <li role="presentation" class="active"><a href="#programas" aria-controls="programas" role="tab" data-toggle="tab">Programas</a></li>
		    <li role="presentation"><a href="#grupos" aria-controls="grupos" role="tab" data-toggle="tab">Grupos</a></li>
<?php 		if ( $user->getRole() == "Profesor" || $user->isAdmin() ) { 			?>
		    <li role="presentation"><a href="#retos" aria-controls="retos" role="tab" data-toggle="tab">Retos</a></li>
		    <li role="presentation"><a href="#tutor" aria-controls="tutor" role="tab" data-toggle="tab">Ayuda tutores</a></li>
<?php		}													?>
	  	</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="programas">
				&nbsp;

				<?php 
				$visibility = $publicProfile ? "(".PUBLICO.", ".USABLE.")" : "(".PUBLICO.", ".USABLE.", ".PRIVADO.", ".EVALUABLE.", ".EJEMPLO.")";
				if ( $_SESSION['user']->isAdmin() ) {
					$visibility = "(".PUBLICO.", ".USABLE.", ".PRIVADO.", ".EVALUABLE.", ".EJEMPLO.", ".BORRADO.")";
				}
				$programs = $_SESSION['bbdd']->queryArray("SELECT * FROM PROGRAM_2 WHERE VISIBILITY in ".$visibility." AND OWNER='".$user->getID()."' ORDER BY DATE DESC");
				listadoProgramas( $user, $programs, $publicProfile );
				?>
			</div>
		    <div role="tabpanel" class="tab-pane" id="grupos">	    
				&nbsp;
<?php 		if ( $user->getRole() == "Profesor" ) { 			?>
				<div class="row">
					<div class="col-md-12">
						<a href="editor-grupo.php?id=-1" class="btn btn-primary pull-right" id="" data-toggle="tooltip" data-placement="bottom" title="Pulsa para crear un nuevo grupo"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
		
<?php		}													?>

				<div class="row">
					<div class="col-md-12">
<?php
				if ( $user->getRole() == "Profesor" ) { 
					misGrupos( $user );
				}
			    if ( ! listadoGrupos( $user, true ) ) {
?>			    	
			    	<p>Aún no estás en ningún grupo, para unirte a alguno busca a un tutor en la sección <a href="aprende.php">'aprende'</a> y mira los grupos que ofrece en su perfil.</p>
<?php
			    }
?>
	    			
<?php	    			
//    			listadoGrupos( $user, false );
?>	    			
	    			</div>
    			</div>

		   	</div>
		    <div role="tabpanel" class="tab-pane" id="retos">
				&nbsp;
<?php 		if ( $user->getRole() == "Profesor" ) { 			?>
				<div class="row">
					<div class="col-md-12">
						<a href="editor-reto.php" class="btn btn-primary pull-right" id="" data-toggle="tooltip" data-placement="bottom" title="Pulsa para crear un nuevo reto"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
<?php		}													?>

				<?php 
				if ( !$publicProfile ) {
					listadoRetos( $user );
				}
				?>

		   	</div>
<?php 		if ( $user->getRole() == "Profesor" ) { 			?>
		    <div role="tabpanel" class="tab-pane" id="tutor">
				&nbsp;
				<?php include("admin/tutores.php"); ?>
		   	</div>
<?php		}													?>
		</div>
	</div>
</div>
<?php	} else {									?>
<div class="container-fluid">
	<div class="col-md-4">
<?php if ( $user->getRole() == "Profesor" && $_SESSION['user']->isRegistered() ) { 			?>
		<h3><?php echo $user->getName(); ?></h3>
		<h4>Tutor en <?php echo $user->getCentre(); ?></h4>
		<h4>Alias: <?php echo $user->getUsername(); ?></h4>
		<h4>e-mail: <a href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></h4>
<?php		} else {											?>
	
		<h2><?php echo $user->getUsername(); ?></h2>
<?php		}													?>
<?php 		if ( $_SESSION['user']->isTutorOf( $user ) || $_SESSION['user']->isAdmin( ) ) {		?>	
		<div class="btn-group btn-group" role="group" aria-label="...">
			<a class="btn btn-primary" role="button" href="progreso.php?id=<?php echo $user->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="progreso del usuario"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Progreso</a>
			<a class="btn btn-primary" role="button" href="trabajo.php?user=<?php echo $user->getMD5Key(); ?>" data-toggle="tooltip" data-placement="bottom" title="Estadísticas del usuario"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Estadísticas</a>
		</div>
<?php 		}																					?>
	</div>
	<div class="col-md-8">
		<ul class="nav nav-tabs nav-perfil" role="tablist">
		    <li role="presentation" class="active"><a href="#programas" aria-controls="programas" role="tab" data-toggle="tab">Programas</a></li>
<?php 		if ( $user->getRole() == "Profesor" && $_SESSION['user']->isRegistered() ) { 			?>
		    <li role="presentation" class=""><a href="#grupos" aria-controls="grupos" role="tab" data-toggle="tab">Grupos</a></li>
<?php		}													?>
	  	</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="programas">
				&nbsp;

				<?php 
				$visibility = "(".PUBLICO.", ".USABLE.")";
				if ( ! $publicProfile ) $visibility = "(".PUBLICO.", ".USABLE.", ".PRIVADO.", ".EVALUABLE.")";
				if ( $_SESSION['user']->isTutorOf( $user ) ) $visibility = "(".PUBLICO.", ".USABLE.", ".EVALUABLE.")";
				$programs = $_SESSION['bbdd']->queryArray("SELECT * FROM PROGRAM_2 WHERE VISIBILITY in ".$visibility." AND OWNER='".$user->getID()."' ORDER BY DATE DESC");
				
				listadoProgramas( $user, $programs, $publicProfile );
				?>

			</div>
<?php 		if ( $user->getRole() == "Profesor" && $_SESSION['user']->isRegistered()) { 			?>
		    <div role="tabpanel" class="tab-pane" id="grupos">	    
				&nbsp;
				<?php 
				tusGrupos( $user, $_SESSION['user'] );
				?>
			</div>
<?php		}													?>
		</div>
	</div>
</div>

<?php	}											?>

<?php
	makeFooter();
?>
</body>
<script>
$(document).ready(
function() {
	
	// Botones visibilidad
	$('div[data-toggle="buttons"] .myrbtn').click(function(){
		var id = $(this).parent().attr('id');
	  	var visibility = $(this).children().val();
		$.post( "services/programs.php", { service: "set-visibility", id: id, visibility: visibility},
			function( res ) {			
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
				    	console.log("Programa " + response.id + " modificdo!");
			    	} else {
			    		alert( "Error al modificar el programa en el servidor: " + response.msg );
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al modificar el programa en el servidor." );
			    	console.log(res);
		    	}
			}
		);
	});

	// Botón borrar
	$(".my-dlt-program-btn").on("click", function( event ) {
		var response = confirm("¿Realmente deseas borrar el programa?");
		if ( !response ) return;

		var id = $(this).parent().attr('id');
		var self = $(this);
		console.log("delete " + id);

		$.post( "services/programs.php", { service: "delete", id: id }, // Borrado
			function( res ) {				
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
				    	console.log("Programa " + response.id + " borrado!");
						self.parent().parent().animate({opacity: 0}, 1000, function() {
							var lgi = document.getElementById("lg-"+id);//$(this).parent().parent();						
							var lpr = document.getElementById("programas");
							lpr.removeChild( lgi );						
						});
			    	} else {
			    		alert( "Error al borrar en el servidor 1: " + response.msg );
			    		console.log(res);
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al borrar en el servidor 2." );
			    	console.log(res, e);
		    	}
			}
		);
	});
	
	$(".my-dlt-contest-btn").on("click", function(event) {
		event.preventDefault();
		var response = confirm("¿Realmente deseas borrar el reto?");
		if ( !response ) return;

		var id = $(this).attr('data-contest-id');
		var self = $(this);
		console.log("delete " + id);

		$.post( "services/contests.php", { service: "delete", id: id }, // Borrado
			function( res ) {				
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
				    	console.log("Reto " + id + " borrado!");
						self.parents("#lg-reto-"+id).animate({opacity: 0}, 1000, function() {
							var lgi = document.getElementById("lg-reto-"+id);//$(this).parent().parent();						
							var lpr = document.getElementById("retos");
							lpr.removeChild( lgi );						
						});
			    	} else {
			    		alert( "Error al borrar en el servidor 1: " + response.msg );
			    		console.log(res);
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al borrar en el servidor 2." );
			    	console.log(res, e);
		    	}
			}
		);
	});

	$(".my-dlt-group-btn").on("click", function(event) {
		event.preventDefault();
		var response = confirm("¿Realmente deseas borrar el grupo?");
		if ( !response ) return;

		var id = $(this).attr('data-group-id');
		var self = $(this);
		console.log("delete " + id);

		$.post( "services/groups.php", { service: "delete", id: id }, // Borrado
			function( res ) {
				console.log(res);				
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
				    	console.log("Grupo " + id + " borrado!");
						self.parents("#lg-group-"+id).animate({opacity: 0}, 1000, function() {
							var lgi = document.getElementById("lg-group-"+id);//$(this).parent().parent();						
							var lpr = document.getElementById("myGroups");
							lpr.removeChild( lgi );						
						});
						var other = $("#lg-group-i-belong-"+id);
						other.animate({opacity: 0}, 1000, function() {
							var lgi = document.getElementById("lg-group-i-belong-"+id);//$(this).parent().parent();						
							var lpr = document.getElementById("lg-group-i-belong");
							lpr.removeChild( lgi );						
						});
							
			    	} else {
			    		alert( response.msg );
			    		console.log(res);
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al borrar en el servidor 2." );
			    	console.log(res, e);
		    	}
			}
		);
	});

});
</script>
</html>
