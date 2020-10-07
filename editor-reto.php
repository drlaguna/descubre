<?php
include_once("common.php");

if ( ! $_SESSION['user']->isRegistered() ) {
	header("location:login.php?origin=".urlencode( currentURL() ) );
	return;
}

if ( $_SESSION['user']->getRole() != "Profesor"  && ! $_SESSION['user']->isAdmin() ) {
	header("location:index.php" );
	return;
}

$contest = null;

if ( isset( $_GET['id'] ) ) {
	$contest = Contest::loadFromMD5Key( $_GET['id'] );
	if ( !$_SESSION['user']->isAdmin( )  ) {
		if ( $contest->getVisibility() == PRIVADO && $contest->getCreator() != $_SESSION['user']->getID() ) {
			header("location:editor-reto.php");
			return;
		}
	}
}
if ( !$contest ) {
	$contest = new Contest($_SESSION['user']->getID() );
}

if ( $contest->getCreator() != $_SESSION['user']->getID() && ! $_SESSION['user']->isAdmin() ) {
	header("location:editor-reto.php");	
	return;
}

makeHeader("Descubre la Programación : Editor de retos ", "Crea y modifica retos", "editor-retos", true);
?>
<style>
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Título</label>
				<div class="input-group">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button" id="buttonNew" title="Pulsa para crear un nuevo reto">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
						</button>
					</span>				
				
					<input class="form-control" type="text" id="title" placeholder="Título del reto" value="<?php echo $contest->getTitle(); ?>" autofocus >
					<span class="input-group-btn">
						<button class="btn btn-primary disabled" type="button" id="buttonSave" title="Pulsa para guardar el reto" >
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true" ></span> Guardar
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
		
            <div class="form-group">
                <label for="start" class="control-label">Inicio</label>
                <div class="input-group date form_datetime" data-date="" data-link-field="start" id="user-start">
                    <input class="form-control" size="16" type="text" value="<?php echo date2Human($contest->getStart()); ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="start" value="<?php echo $contest->getStart(); ?>" />
            </div>

		</div>		
		<div class="col-md-3">
		
            <div class="form-group">
                <label for="end" class="control-label">Fin</label>
                <div class="input-group date form_datetime" data-date="" data-link-field="end"  id="user-end" data-toggle="tooltip" data-placement="bottom" title="Si se dejan en blanco">
                    <input class="form-control" size="16" type="text" value="<?php echo date2Human($contest->getEnd()); ?>" >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="end" value="<?php echo $contest->getEnd(); ?>" />
            </div>

		</div>		
	</div>
	<div class="collapse" id="result" >
	  <div id="result-content" class="alert">
	  ...
	  </div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label>Grupo</label>


				<div class="dropdown">
					<button class="btn btn-default dropdown-toggle" type="button" id="group-menu-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Elige <span class="caret"></span></button>
					<ul id="group-menu" class="dropdown-menu" aria-labelledby="group-menu-button">
						<li><a href="editor-grupo.php?id=-1">Crear uno</a></li>
						<li role="separator" class="divider"></li>
						<li data-groupid="0"><a class="nolink" href="">Ninguno</a></li>
<?php
	$groups = array();
	if ( $_SESSION['user']->isAdmin() ) {
		$groups = $_SESSION['bbdd']->queryArray("SELECT * FROM GROUP_2");
	} else {
		$groups = $_SESSION['bbdd']->queryArray("SELECT * FROM GROUP_2 WHERE OWNER='".$_SESSION['user']->getID()."'");
	}
	$label = "Ninguno";
	foreach ( $groups as $group ) {
		if ( $contest->getGroup() == $group['ID'] ) $label = $group['NAME'];
?>
						<li data-groupid="<?php echo $group['ID']; ?>"><a class="nolink" href=""><?php echo $group['NAME']?></a></li>
<?php
	}
?>						
					</ul>
					&nbsp;
					<span><em id="selected-group-label"> <?php echo $label; ?> </em></span>
					<input type="hidden" id="selected-group" value="<?php echo $contest->getGroup(); ?>" />
				</div>
			</div>
		</div>		
		<div class="col-md-3">
			<div class="form-group">
				<label for="visibility">Visibilidad</label>
	 			<div class="form-inline" >	
					<div class="form-group radio" id="visibility">
							<label class="radio-inline"><input type="radio" name="visibility-c" value=<?php echo PUBLICO; ?> title="Público" <?php if ($contest->getVisibility() == PUBLICO) echo "checked"; ?> > Público</label>		
							<label class="radio-inline"><input type="radio" name="visibility-c" value=<?php echo PRIVADO; ?> title="Privado" <?php if ($contest->getVisibility() == PRIVADO) echo "checked"; ?>> Privado</label>	
					</div>
<!--
					<input class="form-control" type="text" id="reward" placeholder="Recompensa" value="<?php echo $contest->getReward(); ?>" >
-->					
				</div>
			</div>
		</div>		
		<div class="col-md-3">
	 		<div class="form-group" >	
				<label>Contraseña</label>
				<input class="form-control" type="text" id="password" placeholder="contraseña opcional" value="<?php echo $contest->getPassword(); ?>" >
			</div>
		</div>		
		<div class="col-md-3">
			<div class="form-group" >
				<label for="ranking" data-toggle="tooltip" data-placement="bottom" title="Incluir un ranking estilo Olimpiada Informática">Ranking</label>
	 			<div class="form-inline" >	
					<div class="form-group radio" id="ranking">
							<label class="radio-inline"><input type="radio" name="ranking-c" value=1 title="Sí" <?php if ($contest->getRanking()) echo "checked"; ?> > Sí</label>		
							<label class="radio-inline"><input type="radio" name="ranking-c" value=0 title="No" <?php if (!$contest->getRanking()) echo "checked"; ?>> No</label>	
					</div>
<!--
					<input class="form-control" type="text" id="reward" placeholder="Recompensa" value="<?php echo $contest->getReward(); ?>" >
-->					
				</div>
			</div>
		</div>		
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
			<label>Descripción</label>
				<textarea id="description" class="ed form-control" rows="4" placeholder="" ><?php echo $contest->getDescription(); ?></textarea>
			</div>
		</div>
	</div>

	<div class="collapse <?php if ( $contest->isSaved() ) echo "in"; ?>" id="div-pruebas">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Pruebas incluidas en el reto</label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Pruebas no incluidas en el reto</label>
					<a href="editor-prueba.php" class="btn btn-primary pull-right" id="buttonResign" data-toggle="tooltip" data-placement="bottom" title="Pulsa para crear una nueva prueba"><span class="glyphicon glyphicon-plus"></span></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="panel panel-default">
					  <!-- Default panel contents -->
					
					  <!-- Table -->
					  <ul class="list-group sortable" id="challenge-list" style="height: 340px; display: block; overflow: scroll;">
	<?php
	$challenges = array();
	if ( $contest->isSaved() ) {
		$challenges = $contest->getChallenges();
	}
	$i = 1;
	foreach( $challenges as $challenge ) {
		$cc_id = $_SESSION['bbdd']->queryValue("SELECT ID FROM CONTEST_CHALLENGE_2 WHERE CONTEST='".$contest->getID()."' AND CHALLENGE='".$challenge->getID()."'");
		$nusers = $_SESSION['bbdd']->queryValue("SELECT COUNT(DISTINCT USER) FROM CC_PROGRAM_2 WHERE CONTEST_CHALLENGE='".$cc_id."'");
		$anex = "";
		if ( $nusers > 0 ) {
			$anex = ' &nbsp; <span class="badge " data-toggle="tooltip" data-placement="left" title="Usuarios que han intentado esta prueba">'.$nusers.'</span>';
		}
	?>						  
					  <tr id="ch-<?php echo $challenge->getID(); ?>">
					  <li class="list-group-item" data-chid="<?php echo $challenge->getID(); ?>" >
					  	<h4 class="list-group-item-heading">
					  	<?php echo $challenge->getTitle(); ?> <?php echo $anex; ?>
					  	<a href="editor-prueba?id=<?php echo $challenge->getMD5Key(); ?>"><span class="glyphicon glyphicon-edit pull-right" data-toggle="tooltip" data-placement="left" title="Editar prueba"></span>
					  	</a>
					  	</h4>
					  	<p class="list-group-item-text" style="white-space: nowrap; width: 95%; overflow: hidden; text-overflow: ellipsis;">
					  	<?php echo $challenge->getStatement(); ?>
					  	</p>
					  </li>

	<?php
	$i++;
	}
	?>						  
					  </ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group" >
					<div class="panel panel-default" style="height: 340px; display: block; overflow: scroll;">
					  <!-- Default panel contents -->
					
					  <!-- Table -->
					  <ul class="list-group sortable" id="challenge-list-2" style="min-height:220px;">
	<?php
	$challenges = $contest->getNotIncludedChallenges();
	$i = 1;
	foreach( $challenges as $challenge ) {
	?>						  
					  <tr id="ch-<?php echo $challenge->getID(); ?>">
					  <li class="list-group-item" data-chid="<?php echo $challenge->getID(); ?>" >
					  	<h4 class="list-group-item-heading">
					  	<?php echo $challenge->getTitle(); ?> 
					  	<a href="editor-prueba?id=<?php echo $challenge->getMD5Key(); ?>"><span class="glyphicon glyphicon-edit pull-right" data-toggle="tooltip" data-placement="left" title="Editar prueba"></span>
					  	</a>
					  	</h4>
					  	<p class="list-group-item-text" style="white-space: nowrap; width: 95%; overflow: hidden; text-overflow: ellipsis;">
					  	<?php echo $challenge->getStatement(); ?>
					  	</p>
					  </li>
	<?php
	$i++;
	}
	?>						  
					  </ul>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="collapse" id="result2" >
	  <div id="result2-content" class="alert">
	  ...
	  </div>
	</div>
	
</div>
<?php
	makeFooter();
?>
</body>
<script>
var cID = <?php echo $contest->getID(); ?>;
var saved = <?php if ( $contest->isSaved() || $contest->getID() == -1 ) echo "true"; else echo "false"; ?>;

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

	$("#buttonNew").on("click", function( event ) {
		window.location.href = "editor-reto.php";
	});    	
	
	// Guardar 
	$("#buttonSave").on("click", function(event) {
		if ( $(this).hasClass("disabled") ) return;

		// Invocar servicio de guardado
//		var visibility = $('#visibility').length > 0 ? $('#visibility label input:checked').val() : <?php echo PRIVADO; ?>;
		if ( $('#ranking label input:checked').val() == "1" && $("#start").val() == "" ) {
			show ( "Para activar el ranking debe haber una fecha de inicio definida", "alert-danger");
			return;	
		}
		if ( $("#selected-group").val() == 0 ) {
			show ( "Para poder guardar el reto debes asignarlo a un grupo. Si no hay ninguno puedes crear uno nuevo y después asignarlo.", "alert-danger");
			return;	
		}

		var params = { 
			service: "save", 
			id: cID,
			group: $("#selected-group").val(),
			title: $("#title").val(),
			description: $("#description").val(),
			visibility: $('#visibility label input:checked').val(), 
			ranking: $('#ranking label input:checked').val(), 
			start: $("#start").val(),
			end: $("#end").val(),
			password: $("#password").val()

		};
		console.log(params);

		$.post( "services/contests.php", params,
			function( res ) {				
		    	try {
			    	var response = JSON.parse( res );
			    	var firstTime = false;
			    	if ( response.id != -1 ) {
			    		if ( cID == -1 ) {
			    			firstTime = true;
							cID = response.id;					
			    		}
/*
						$("#buttonSave").addClass("disabled");
						$("#div-pruebas").collapse("show");	
						saved = true;	
*/
						// **
//						if ( cID == -1 ) return;
						// Actualizar challenges
						var list = document.getElementById("challenge-list");
						var ch = list.children;
						var chlist = [];
						for ( var i = 0 ; i < ch.length ; i++ ) {
							chlist[i] = {
								id: $(ch[i]).attr("data-chid"),								
							};
						}
						console.log(chlist);
						$.post( "services/contests.php", { service: "update-challenges", id: cID, challenges: chlist },
							function( res ) {				
								console.log(res);
						    	try {
							    	var response = JSON.parse( res );
							    	if ( response.id != -1 ) {
							    		if ( firstTime ) {
											show ( "Prueba creada!", "alert-success");
							    		} else {
											show ( "Prueba guardada!", "alert-success");
							    		}							    		
										$("#buttonSave").addClass("disabled");
										$("#div-pruebas").collapse("show");		
										saved = true;	
							    	} else {
								  		show( response.msg, "alert-danger" );
							    		return;
							    	}
						    	} catch ( e ) {
						    		alert( "Error al guardar la lista de pruebas en el servidor." );
							    	console.log(res);
						    	}
							}
						);
			    	} else {
				  		show( response.msg, "alert-danger" );
			    		return;
			    	}
		    	} catch ( e ) {
		    		alert( "Error al guardar el reto en el servidor." );
			    	console.log(res);
		    	}
			}
		);
		// TODO: Debería hacer todo los siguiente en **
		
		
	});    
	
	$("#title").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});    
	
	$("#description").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});    
	
	$("#visibility").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});    
	
	$("#password").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});    
	
	$("#ranking").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});    
	
	Sortable.create(document.getElementById('challenge-list'), 
	{
		group: "challenges", 
		onAdd: function( event ) {
			$("#buttonSave").removeClass("disabled");
			saved = false;
		},
		onRemove: function( event ) {
			$("#buttonSave").removeClass("disabled");
			saved = false;
		},
		onMove: function( event ) {
			$("#buttonSave").removeClass("disabled");
			saved = false;
		}
	});
	Sortable.create(document.getElementById('challenge-list-2'), {group: "challenges", scroll:true});

	$("#group-menu li a.nolink").on("click", function( event ) {
		event.preventDefault();
		var groupid = $(this).parent().attr('data-groupid');
		$("#selected-group-label").text( $(this).text() );
		$("#selected-group").val( groupid );
		$("#buttonSave").removeClass("disabled");
		saved = false;
	});

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        format: "dd/mm/yyyy hh:ii",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 1,
        showMeridian: 0,
        linkFormat: "yyyy-mm-dd hh:ii"
    });
    
    $(".form_datetime").on("change", function( event ) {
		$("#buttonSave").removeClass("disabled");
		saved = false;
    });
    
	window.addEventListener("beforeunload", function(e){		
		if ( ! saved ) return "Si sales de la página sin guardar perderás los cambios realizados";
		else return null;
	}, false);
});

</script>
</html>
