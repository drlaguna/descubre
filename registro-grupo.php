<?php
//	http://getbootstrap.com/examples/signin/
include_once("common.php");

if ( !isset( $_GET['id'] ) ) {
	header("location:index.php");
	return;
}

if ( !isset( $_GET['action'] ) ) {
	header("location:index.php");
	return;
}

if ( $_GET['action'] != "join" && $_GET['action'] != "leave" ) {
	header("location:index.php");
	return;
}

if ( !$_SESSION['user']->isRegistered() ) {
	header("location:login.php?origin=".urlencode( currentURL() ) );
	return;
}

$group = Group::loadFromMD5Key( $_GET['id'] );
if ( !$group ) {
	header("location:index.php");
	return;
}

$origin = isset( $_GET['origin'] ) ? urldecode( $_GET['origin'] ) : "index.php";

if ( ! $group->hasPassword() && ( $_GET['action'] == "join" ) ) {
	if ( ! $group->includes( $_SESSION['user'] ) ) $group->add( $_SESSION['user'] );
	header("location:".$origin);
	return;
}

if ( $_GET['action'] == "leave" ) {
	if ( $group->includes( $_SESSION['user'] ) ) $group->remove( $_SESSION['user'] );
	header("location:".$origin);
	return;
}

$tutor = User::loadFromID( $group->getCreator() );

makeHeader("Descubre la Programación", "Explora, aprende y crea tus propios programas", "", false);

$origin = isset( $_GET['origin'] ) ? urldecode( $_GET['origin'] ) : "index.php";
?>
    <div class="container">
    	<div class="col-md-12">
			<div class="page-header">
			  <h1><small>Introduce la clave para inscribirte en el grupo '<?php echo $group->getName(); ?>' con el tutor '<?php echo $tutor->getName()?>'</small></h1>
			</div>
			<div class="collapse" id="result" >
			  <div id="result-content" class="alert">
			  ...
			  </div>
			</div>
		</div>
    	<div class="col-md-4">
			<div class="form-group">
	        <input type="password" id="password" name="password" class="form-control"  tabindex=2 placeholder="Contraseña" required>
			</div>
			<div class="form-group">
			<button class="btn btn-primary" type="button" id="buttonRegister" title="">Unirse</button>
			</div>
		</div>
    </div> <!-- /container -->
<?php
	makeFooter();
?>
</body>
<script type="text/javascript">
var gid = <?php echo $group->getID(); ?>;
	
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

	$( "#buttonRegister" ).on("click",  
		function ( event ) {
			var password = md5( $ ( "#password" ).val() );
			$.post( "services/groups.php", { service: "join", id: gid, password: password},
				function( res ) {
					console.log(res);
			    	try {
				    	var response = JSON.parse( res );
				    	if ( response.id != -1 ) {
				    		console.log(response);
							show ( "Te has unido al grupo", "alert-success");						    		
							var origin = "<?php echo $origin; ?>";
							if ( origin != "index.php" ) {
								window.location = origin;
							}
				    	} else {
							show ( response.msg, "alert-danger");						    		
				    	}
			    	} catch ( e ) {
			    		console.log(e, res);
			    		alert(  "Error al unirse al grupo" + res );
			    	}
				}
			);
		}
	);
</script>
</html>