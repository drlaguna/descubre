<?php
include_once("common.php");

if ( ! $_SESSION['user']->isRegistered() ) {
	header("location:login.php?origin=".urlencode( currentURL() ) );
	return;
}

if ( $_SESSION['user']->getRole() != "Profesor" ) {
	header("location:index.php" );
	return;
}

$group = null;

if ( isset( $_GET['id'] ) ) {
	if ( $_GET['id'] == -1 ) {
		$group = new Group( $_SESSION['user']->getID() );
	} else {
		$group = Group::loadFromMD5Key( $_GET['id'] );
	}	
}
if ( !$group) {
	$group = new Group( $_SESSION['user']->getID() );
}

if ( $group->getCreator() != $_SESSION['user']->getID() && !($_SESSION['user']->isAdmin()) ) {
	header("location:index.php");
	return;
}

makeHeader("Descubre la Programación :Editor de grupos ", "Crea y modifica grupos de alumnos", "grupo", false);
?>
<style>

.form-signin {
  max-width: 530px;
  margin: 0 auto;
}
</style>
<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="collapse" id="result" >
				  <div id="result-content" class="alert">
				  ...
				  </div>
				</div>
				<form class="form-signin" id="groupForm" action="">
				<?php if ( $group->isSaved() ) { ?>
					<h3 class="form-signin-heading">Modificación de datos del grupo</h3>
				<?php }	else {	?>
					<h3 class="form-signin-heading">Creación de nuevo grupo</h3>
				<?php }	?>
				
				
					<label for="nombre" class="">Nombre</label>
					<input type="text" id="name" name="name" value="<?php echo $group->getName() ?>" class="form-control" placeholder="Nombre del grupo" required tabindex=1>
					
					<label>Contraseña</label>
					<input class="form-control" type="text" id="password" placeholder="contraseña opcional" value="<?php echo $group->getPassword(); ?>" >
					
					<div class="form-group">
				<?php if ( !$group->isSaved() ) { ?>
						<button class="btn btn-lg btn-primary btn-block" type="submit" >Crear grupo</button>
				<?php }	else {	?>
						<button class="btn btn-lg btn-primary btn-block" type="submit" >Guardar cambios</button>
				<?php }	?>
					</div>				
				</form>
			</div>
		</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
$(document).ready(
function() {
	// type must be alert-success, alert-warning, alert-info, or alert-danger
	function show( msg, type ) {
		if ( $("#result").hiddenTimer ) clearTimeout($("#result").hiddenTimer);
		$("#result").collapse("hide");		
		$("#result-content").removeClass(); // Remove all classes
		$("#result-content").addClass("alert " + type);
		$("#result-content").text( msg );
		$("#result").collapse("show");		
	}
		
	$("#result").on('shown.bs.collapse', function(){
		$(this).hiddenTimer = setTimeout(function() {
			$("#result").collapse("hide");
		}, 5000);
    });    

	$( "#groupForm" ).on("submit",  
		function ( event ) {
			event.preventDefault();
			if ( $( "#name" ).val() == "" ) {
					show ( "Es necesario que indiques el nombre del grupo","alert-danger" );
					return;
			}
			var form = $ ( "#loginForm" );
			var parameters = {
				service: "save", 
				id: <?php echo $group->getID(); ?>, 
				name: $( "#name" ).val(), 
				password: $( "#password" ).val()
				
			};
			console.log(parameters);
			$.post( "services/groups.php", parameters,
				function( res ) {
					console.log(res);
			    	try {
				    	var response = JSON.parse( res );
				    	if ( response.id != -1 ) {
							if ( parameters.id == -1 ) {
								show ( "Grupo creado correctamente.", "alert-success" );
								// Avisar de registro de activación							
							} else {
								show ( "Datos modificados correctamente", "alert-success" );
							}
				    	} else {
							show ( data, "alert-danger" );
				    	}
			    	} catch ( e ) {
			    		alert( "Error al guardar el grupo en el servidor." );
				    	console.log(res);
			    	}
					
				}	
			);
		}
	);
});
</script>
</html>
