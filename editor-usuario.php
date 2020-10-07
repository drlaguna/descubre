<?php
include_once("common.php");

$user = null;


if ( isset( $_GET['id'] ) ) {
	$_GET['id'] = mysql_real_escape_string($_GET['id']);
	if ( $_GET['id'] == -1 ) {
		$user = new User();
	} else {
		$user = User::loadFromMD5Key( $_GET['id'] );
	}	
}
if ( !$user) {
	if ( $_SESSION['user']->isRegistered() ) {
		$user = $_SESSION['user'];
	} else {
		$user = new User();
	}
}
// S�lo puede modificar los datos el due�o y el admin
if ( $user->getID() != $_SESSION['user']->getID() && ! $_SESSION['user']->isAdmin() && $user->isRegistered() ) {
	header("location:index.php");
	return;
}

makeHeader("Descubre la Programaci�n : Datos de usuario ", "Crea y modifica datos de usuario", "usuario", false);
?>
<style>

.form-signin {
  max-width: 530px;
  margin: 0 auto;
}
</style>
<div class="modal fade bs-example-modal-lg " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">      
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4>Atenci�n</h4>
      </div>
      <div class="modal-body">
        <div class="row">
		  <div id="result-content-2" class="alert">
		  ...
		  </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="collapse" id="result" >
				  <div id="result-content" class="alert">
				  ...
				  </div>				 
				</div>
				
				<form class="form-signin" id="editUserForm" action="">
				<?php if ( $user->isRegistered() ) { ?>
					<h3 class="form-signin-heading">Modificaci�n de datos del usuario</h3>
				<?php }	else {	?>
					<h3 class="form-signin-heading">Creaci�n de nuevo usuario</h3>
				<?php }	?>
				
				
					<label for="username" class="">Usuario</label>
					<input type="text" id="username" name="username" value="<?php echo $user->getUsername() ?>" class="form-control" placeholder="Identificador de usuario" required tabindex=1>
					
					<label for="name" class="">Nombre</label>
					<input type="text" id="name" name="name" value="<?php echo $user->getName() ?>" class="form-control" placeholder="Nombre real" required >
					
					<label for="email" class="">email</label>
					<input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>" class="form-control" placeholder="email - Nota: Descubre no funciona bien con direcciones de hotmail" required>
				
					<label for="birthyear" class="">A�o de nacimiento</label>
					<input type="number" id="birthyear" name="birthyear" value="<?php echo $user->getBirthyear(); ?>" class="form-control" placeholder="A�o de nacimiento" required>

					<label for="centre" class="">Centro donde estudias</label>
					<input type="text" id="centre" name="centre" value="<?php echo $user->getCentre(); ?>" class="form-control" placeholder="Centro donde estudias" required>
					<div class="row">
						<div class="form-group col-md-6">
							<label>G�nero</label>
				 			<div class="form-inline" >	
								<div class="form-group radio" id="gender">
										<label class="radio-inline"><input type="radio" name="gender-c" value="M" title="Chico" <?php if ($user->getGender() == "M") echo "checked"; ?> > Chico</label>		
										<label class="radio-inline"><input type="radio" name="gender-c" value="F" title="Chica" <?php if ($user->getGender() == "F") echo "checked"; ?> > Chica</label>	
								</div>														
							</div>
						</div>
	
						<div class="form-group col-md-6">
							<label>Rol</label>
				 			<div class="form-inline" >	
								<div class="form-group radio" id="role">
										<label class="radio-inline"><input type="radio" name="role-c" value="Estudiante" title="Estudiante" <?php if ($user->getRole() == "Estudiante") echo "checked"; ?> > Estudiante</label>		
										<label class="radio-inline"><input type="radio" name="role-c" value="Profesor" title="Profesor" <?php if ($user->getRole() == "Profesor") echo "checked"; ?> > Profesor</label>	
								</div>														
							</div>
						</div>
					</div>

<?php	if ( $user->isRegistered() ) {	?>
					<div class="checkbox">
						<label class="checkbox-inline"><input type="checkbox" id="password-change"  >Modificar password</label>		
					</div> 					
<?php 	}	?>

<?php	if ( $user->isRegistered() ) {	?>
					<div id="password-form" style="display:none;">
<?php 	} else {	?>
					<div id="password-form" >
<?php 	}	?>
						<label for="password" class="">Contrase�a</label>
						<input type="password" id="password" name="password" class="form-control" placeholder="Contrase�a" >
						
						<input type="password" id="password2" name="password2" class="form-control" placeholder="Repita la Contrase�a" >
					</div>

					<div class="checkbox">
						<label class="checkbox-inline"><input type="checkbox" id="rgpd-change"  >
						Conozco los <a href="terminos-y-condiciones.php">t�rminos y condiciones</a> y la <a href="politica-privacidad.php">pol�tica de privacidad</a> y estoy de acuerdo con ellos. Ademas, confirmo que tengo 14 a�os o m�s de edad.
						</label>		
					</div> 					

					<div class="form-group">
				<?php if ( !$user->isRegistered() ) { ?>
						<button class="btn btn-lg btn-primary btn-block" type="submit" >Crear usuario</button>
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
	function show2( msg) {
		$("#result-content-2").text( msg );
		$('#myModal').modal();					
								
	}
	
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
		}, 10000);
    });    

	$( "#password-change" ).on("click",
		function ( event ) {
			if ( $(this).prop('checked') ) {
				$( "#password-form" ).css("display", "block");
			} else {
				$( "#password-form" ).css("display", "none");
			}
		}
	);	

	$( "#editUserForm" ).on("submit",  
		function ( event ) {			
			event.preventDefault();
			if ( $( "#username" ).val() == "" ) {
					show ( "Tienes que elegir alg�n identificador de usuario","alert-danger" );
					return;
			}
			if ( $( "#name" ).val() == "" ) {
					show ( "Es necesario que indiques tu nombre","alert-danger" );
					return;
			}
			if ( $( "#email" ).val() == "" ) {
					show ( "Es necesario que indiques un correo electr�nico v�lido","alert-danger" );
					return;
			}
			if ( !$( "#rgpd-change" ).prop('checked') ) {
					show ( "Es necesario que marques la casilla indicando que conoces y est�s conforme con nuestra pol�tica de privacidad y nuestros t�rminos y condiciones y que, adem�s, tienes 14 a�os o m�s","alert-danger" );
					return;
			}
			
			var form = $ ( "#editUserForm" );
			var parameters = {
				service: "add/edit", 
				id: <?php echo $user->getID(); ?>, 
				login: $( "#username" ).val(), 
				name: $( "#name" ).val(), 
				email: $( "#email" ).val(),
				birthyear: $( "#birthyear" ).val(),
				centre: $( "#centre" ).val(),
				gender: $('#gender label input:checked').val(),
				role: $('#role label input:checked').val()
				
			};
			if ( $( "#password-change" ).prop('checked') ||
			   <?php if ( !$user->isRegistered() ) echo "true"; else echo "false"; ?>) {
				var password = md5( $( "#password" ).val() );
				var password2 = md5( $( "#password2" ).val() );			
				if ( password != password2 ) {
					show( "El password no coincide con la repetici�n del mismo", "alert-danger" );
					return;
				}
				if ( $( "#password" ).val() == "" ) {
					show ( "El password no puede estar vac�o","alert-danger" );
					return;
				}
				parameters.password = password;
			}
			$.post( "services/users.php", parameters,
				function( res ) {
			    	try {
				    	var response = JSON.parse( res );
				    	if ( response.id != -1 ) {
							if ( parameters.id == -1 ) {
								
								show2 ( "Usuario creado correctamente. Antes de poder acceder al sistema debes activar tu usuario. Sigue las instrucciones que te hemos enviado a '" + $( "#email" ).val() + "'.", "alert-success" );
								// Avisar de registro de activaci�n							
							} else {
								show ( "Datos modificados correctamente", "alert-success" );
							}
				    	} else {
							show ( response.msg, "alert-danger" );
				    	}
			    	} catch ( e ) {
			    		alert( "Error al guardar el usuario en el servidor." );
				    	console.log(e, res);
			    	}
					
				}	
			);
		}
	);
});
</script>
</html>
