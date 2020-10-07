<?php
include_once("common.php");

function isValidMd5($md5 ='') {
  return strlen($md5) == 32 && ctype_xdigit($md5);
}		

$program = new Program( $_SESSION['user']->getID() );
if ( isset( $_GET['id']) ) {
	if ( isValidMd5($_GET['id']) ) {
		$version = null;
		if ( isset( $_GET['version'] ) && is_numeric( $_GET['version'] ) && $_SESSION['user']->isAdmin() ) {
			$version = $_GET['version'];
		}
		$program = Program::loadFromMD5Key( $_GET['id'], $version );
	}
	if ( !$program ) $program = new Program( $_SESSION['user']->getID() );
	
	if ( !$_SESSION['user']->isAdmin( )  ) {
		if ( $program->getVisibility() == BORRADO ) {
			header("location:editor-codigo.php");
			return;
		}
		if ( $program->getVisibility() == PRIVADO && $program->getCreator() != $_SESSION['user']->getID() ) {
			header("location:editor-codigo.php");
			return;
		}
		if ( $program->getVisibility() == USABLE && $program->getCreator() != $_SESSION['user']->getID() ) {
			header("location:editor-codigo.php");
			return;
		}
		if ( $program->getVisibility() == SOLUCION && $program->getCreator() != $_SESSION['user']->getID() ) {
			header("location:editor-codigo.php");
			return;
		}
		if ( $program->getVisibility() == EVALUABLE && ( $program->getCreator() != $_SESSION['user']->getID() && !$_SESSION['user']->isTutorOf( $program->getCreator() ) ) ) {
			header("location:editor-codigo.php");
			return;
		}
		if ( $program->getCreator() != $_SESSION['user']->getID() ) $program->incViews();
	}
}

makeHeader("Descubre la Programación : Muestra ", "Muestra tus programas sin salir del navegador", "crea", true);
?>
<?php include ("admin/ayuda.php"); ?>
<div id="colorpicker-div"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8 ">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button" id="buttonNew" title="Pulsa para crear un programa nuevo">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
						</button>
					</span>
					<input class="form-control" type="text" id="title" name="title" tabindex=1 <?php if ( !$program->isSaved() ) echo 'autofocus'; ?> placeholder="Título del programa" value="<?php echo $program->getTitle(); ?>" >
<?php 
if ( $_SESSION['user']->isRegistered() ) { 
	if ( $program->getCreator() == $_SESSION['user']->getID() ) {
?>
					<span class="input-group-btn">
						<button class="btn btn-primary disabled" type="button" id="buttonSave" title="Pulsa para guardar el programa" >
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true" ></span> <span id="buttonSaveText">Guardar</span>
						</button>
					</span>
<?php
	} else {
?>
					<span class="input-group-btn">
						<button class="btn btn-primary disabled" type="button" id="buttonSave" title="Pulsa para crear una versión tuya basada en este programa">
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> <span id="buttonSaveText">Versionar</span>
						</button>
					</span>
<?php
	}
}
?>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-xs-4">
			<div class="form-group">
<!--
				<div class="btn-group pull-left" role="group" aria-label="...">
			        <div class="alert well-sm alert-success alert-sm" id="result-ok" style="opacity:0; " >
			        </div>
			    </div>
-->
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-question-sign"></span> Ayuda</button>
				
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button class="btn btn-primary " type="button" id="buttonRun" title="Pulsa para probar el programa" tabindex=3 >
					<span class="glyphicon glyphicon-play" aria-hidden="true" id="buttonRunGlyph"></span> <span id="buttonRunText">Probar</span></button>
				</div>
			</div>
		</div>		
	</div>
	
	<div class="collapse" id="result" >
	  <div id="result-content" class="alert">
	  ...
	  </div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<textarea id="textarea-editor" name="content" class="ed form-control" rows="16" tabindex=2 ><?php echo $program->getCode(); ?></textarea>
					</div>		
				</div>		
			</div>
			<div class="row">
				<div class="col-sm-12">
					&nbsp;
					<textarea id="output" rows="5" readonly style="font: 14px Courier New; height: 158px; width: 100%; display:none;"></textarea>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div id="compileroutput">
					</div>
				</div>
			</div>
		</div>		
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-12">
					<div id="canvascontainer">
						<canvas id="mycanvas" width="320" height="320">Canvas not supported.</canvas>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group ">
<?php 
if ( $program->isSaved() ) {
?>				
						<div class="form-group ">						
<?php 
	if ( $program->getRoot() != -1 && (Program::loadFromID( $program->getRoot() != -1) ) ) {
		$root = Program::loadFromID( $program->getRoot() );
?>				
							<span class="glyphicon glyphicon-pencil text-primary" aria-hidden="true"></span> Versión de <strong><?php echo $program->getCreatorLink(); ?></strong> a partir de la de <?php echo $root->getCreatorLink(); ?>
<?php
	} else {
?>
							Programado por <strong><?php echo $program->getCreatorLink(); ?></strong>
							<?php 
							echo '<p>';
							if ( $_SESSION['user']->isAdmin() ) {
								echo $program->getVersionList();
							}
							echo '</p>';
							?>
<?php
	}
?>

						</div>			
						<div class="form-group text-info">
<?php 
	if ( $_SESSION['user']->isRegistered() && $program->getCreator() != $_SESSION['user']->getID() ) {
?>
							<span id="vote-icon" class="glyphicon <?php if ( $program->isVotedBy( $_SESSION['user'] ) ) echo 'glyphicon-check'; else echo 'glyphicon-unchecked'; ?>"  data-toggle="tooltip" data-placement="bottom" title="Pulsa para votar o retirar tu voto para este programa" ></span> <span id="nvotes"><?php echo $program->getVotes(); ?></span> votos
<?php
	} else {
?>
							<span class="glyphicon glyphicon-ok" title="votos" ></span> <?php echo $program->getVotes(); ?></span> votos

<?php
	}
?>
							&nbsp;
							<span class="glyphicon glyphicon-download" aria-hidden="true" title="descargas"></span> <?php echo $program->getViews(); ?> descargas
							&nbsp;
							<span class="glyphicon glyphicon-play" aria-hidden="true" title="usos"></span> <?php echo $program->getRuns(); ?> usos
<?php
}
?>
							
						</div>
					</div>		
				</div>		
			</div>
		</div>		
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
var programID = <?php echo $program->getID(); ?>;
var editor = null;

$(document).ready(
function() {
	$("#title").on("change", function(event) {
		$("#buttonSave").removeClass("disabled");
	});    
	
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

	var gui = {
		onCodeChange: function() {
			$("#buttonSave").removeClass("disabled");
		},
		onProgramStarted: function() {
			$("#buttonRunText").text("Parar");
			$("#buttonRunGlyph").removeClass("glyphicon-play");
			$("#buttonRunGlyph").addClass("glyphicon-stop");			
			var id = $(this).parent().attr('id');
		  	var visibility = $(this).children().val();
			$.post( "services/programs.php", { service: "run", id: programID },
				function( res ) {		
				}
			);
		},
		onProgramStopped: function() {
			$("#buttonRunText").text("Probar");
			$("#buttonRunGlyph").removeClass("glyphicon-stop");			
			$("#buttonRunGlyph").addClass("glyphicon-play");
		}
	};
	
    editor = new iJavaEditor("textarea-editor", "mycanvas", "output", "compileroutput", gui);
    
	$("#buttonNew").on("click", function(event) {
		window.location.href = "editor-codigo.php";
	});    
	
	// Guardar o versionar según el caso
	$("#buttonSave").on("click", function(event) {
		if ( $(this).hasClass("disabled") ) return;
		
		var title = $("#title").val();
		// Invocar servicio de guardado
		// TODO: Convertir en llamada post para reducir código
		var formData = new FormData();
    	formData.append("id", programID);
    	formData.append("service", "save");
    	formData.append("title", title);
    	formData.append("code", editor.getValue());    	
    	formData.append("visibility", <?php echo $program->getVisibility(); ?>); 
    	formData.append("keypoints", JSON.stringify( editor.getKeyPoints() ) );
	    $.ajax({
	    	async: false,
	        url: "services/programs.php",
	        type: "post",
	        dataType: "html",
	        data: formData,
	        cache: false,
	        contentType: false,
			processData: false
	    }).done(function(res){
	    	try {
		    	var response = JSON.parse( res );
		    	if ( response.id != -1 ) {
					show ( "¡Programa guardado!", "alert-success" );
					$("#buttonSaveText").text(" Guardar");
					$("#buttonSave").addClass("disabled");
					programID = response.id;
		    	} else {
		    		show( response.msg, "alert-danger");
		    		return;
		    	}
	    	} catch ( e ) {
	    		alert( "Error al guardar el programa en el servidor." );
		    	console.log(res);
	    	}
	    });		
	});    
	
	$("#buttonRun").on("click", function( event ) {
		editor.run();
	});    

	var votado = <?php if ( $program->isVotedBy( $_SESSION['user'] ) ) echo 'true'; else echo 'false'; ?>;
		
	var voteIconIn = function( ) {
		if ( votado ) {
		    $("#vote-icon").removeClass('glyphicon-check');
		    $("#vote-icon").addClass('glyphicon-unchecked');	    
		} else {
		    $("#vote-icon").removeClass('glyphicon-unchecked');
		    $("#vote-icon").addClass('glyphicon-check');	    
		}
	}
		
	var voteIconOut = function( ) {
		if ( votado ) {
		    $("#vote-icon").removeClass('glyphicon-unchecked');
		    $("#vote-icon").addClass('glyphicon-check');	    
		} else {
		    $("#vote-icon").removeClass('glyphicon-check');
		    $("#vote-icon").addClass('glyphicon-unchecked');	    
		}
	}

	$("#vote-icon").hover(voteIconIn, voteIconOut);
	
	$("#vote-icon").on("click", function( event )  {
		$.post( "services/programs.php", { service: "vote", id: programID },
			function( res ) {				
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
			    		votado = !votado;
					    $("#vote-icon").removeClass('glyphicon-unchecked');
					    $("#vote-icon").addClass('glyphicon-check');	    
					    $("#nvotes").text(response.votes);
			    	} else {
			    		show( response.msg, "alert-danger");
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al modificar el programa en el servidor." );
			    	console.log(res);
		    	}
			}
		);		
	});
     
	window.addEventListener("beforeunload", function(e){
		$.post( "services/programs.php", { service: "type", id: programID, ms: editor.getTypingTime() },
			function( res ) {				
				// TODO:  Anular porque, en ningún caso informa al usuario		
		    	try {
			    	var response = JSON.parse( res );
			    	if ( response.id != -1 ) {
				    	console.log("Programa " + response.id + " modificado!");
			    	} else {
			    		console.log( response.msg );
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al modificar el programa en el servidor." );
			    	console.log(res);
		    	}
			}
		);
	}, false);
});

</script>
</html>
