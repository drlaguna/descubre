<?php
//	http://getbootstrap.com/examples/signin/
	include_once("common.php");
	makeHeader("Descubre la Programación", "Explora, aprende y crea tus propios programas", "", false);

	$origin = isset( $_GET['origin'] ) ? urldecode( $_GET['origin'] ) : "index.php";
?>
	<script type="text/javascript" src="js/utils.js"></script>
<style>

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

</style>
    <div class="container">
<!--
			<div class="alert alert-danger">
			Desde la última actualización para acceder se utiliza el e-mail que indicaste al registrarte en lugar del nombre de usuario. Si no lo recuerdas escribe a <a href="mailto:descubrelainformatica@um.es">descubrelainformatica@um.es</a> indicando tu antiguo nombre de usuario.
			</div>
-->
      <form class="form-signin" id="loginForm" name="login" action="">
        <h3 class="form-signin-heading">Formulario de acceso</h3>

        <label for="username" class="sr-only">Usuario</label>
        <input type="text" id="username" name="username" value="" class="form-control" autofocus tabindex=1 placeholder="e-mail o identificador de usuario" required >

        <label for="password" class="sr-only">Contraseña</label>
        <input type="password" id="password" name="password" class="form-control"  tabindex=2 placeholder="Contraseña" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit" >Acceder</button>
        
		<label>
		¿Aún no tienes una cuenta? <a href="editor-usuario.php?id=-1" >Inscríbete</a>
		</label
      </form>
      <div class="alert alert-danger" id="result-no" style="opacity:0;" ></div>

    </div> <!-- /container -->
<?php
	makeFooter();
?>
</body>
	<script type="text/javascript">
	$(".alert").click(
	function( event ) {
	    // Animation complete.
	    $(this).animate( {opacity: 0 }, 1000);
		
	});
	
	function mostrar( elemento, msg ) {
		elemento.empty();
		elemento.append( msg );
		elemento.animate( { opacity: 1 }, 1000 ).delay(10000).animate({opacity: 0}, 1000);
	}
	
	$( "#loginForm" ).on("submit",  
		function ( event ) {
			event.preventDefault();
			var form = $ ( "#loginForm" );
			var username = $ ( "#username" ).val();
			var password = md5( $ ( "#password" ).val() );
			$.post( "services/users.php", { service: "login", username: username, password: password},
				function( data ) {
					if ( data === "ok" ) {
						window.location = "<?php echo $origin; ?>";
					} else {
						mostrar ( $("#result-no"), data );
					}
				}
			);
		}
	);
	</script>
</html>