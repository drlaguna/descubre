<?php
include_once("common.php");

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

$user = null;

if ( isset( $_GET['id'] ) ) {
	$_GET['id'] = mysql_real_escape_string($_GET['id']);
	$user = User::loadFromMD5Key( $_GET['id'] );
	if ( !$user ) {
		header("location:index.php");
		return;
	}
}

// Sólo puede hacer esto el dueño y el admin
if ( $user->getID() != $_SESSION['user']->getID() && ! $_SESSION['user']->isAdmin() && $user->isRegistered() ) {
	header("location:index.php");
	return;
}

// Sólo si estás registrado y no lo has hecho ya
if ( !$user->isRegistered() || $user->hasAgreed() ) {
	header("location:index.php");
	return;
}

makeHeader("Descubre la Programación : RGPD ", "Acepta la Política de Privacidad y los Términos y Condiciones", "usuario", false);
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
		<h4>Atención</h4>
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
				
				<form class="form-signin" id="rgpdForm" action="">
					<h3 class="form-signin-heading">Pulsa el botón para declarar lo siguiente</h3>
					<p>Conozco y estoy de acuerdo con los <a href="terminos-y-condiciones.php">términos y condiciones</a> y la <a href="politica-privacidad.php">política de privacidad</a> y, ademas, confirmo que tengo 14 años o más de edad.</p>

					<div class="form-group">
						<button class="btn btn-lg btn-primary btn-block" type="submit" >Estoy de acuerdo con lo anterior</button>
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


	$( "#rgpdForm" ).on("submit",  
		function ( event ) {			
			event.preventDefault();
			
			var parameters = {
				service: "agreed", 
				id: <?php echo $user->getID(); ?>
				
			};
			$.post( "services/rgpd.php", parameters,
				function( res ) {
			    	try {
				    	var response = JSON.parse( res );
				    	if ( response.id != -1 ) {
							show ( "Muchas gracias", "alert-success" );
				    	} else {
							show ( response.msg, "alert-danger" );
				    	}
			    	} catch ( e ) {
			    		alert( "Error al acceder al servidor." );
				    	console.log(e, res);
			    	}
					
				}	
			);
		}
	);
});
</script>
</html>
