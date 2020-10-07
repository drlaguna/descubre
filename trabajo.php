<?php
include_once("common.php");

if ( !$_SESSION['user']->isRegistered() ) {
	header("location:index.php");
	return;
}

if ( !isset( $_GET['user'] ) ) {
	header("location:index.php");
	return;
}

$user = User::loadFromMD5Key( $_GET['user'] );

if ( $_SESSION['user']->getID() != $user->getID() && !$_SESSION['user']->isTutorOf($user) && !$_SESSION['user']->isAdmin() ) {
	header("location:index.php");
	return;
}

$program = null;

if (isset($_GET['program']) && $_GET['program'] != "") {
	$program = Program::loadFromMD5Key( $_GET['program'] );
/*
	if ( $program->getCreator() != $user->getID() ) {
		header("location:index.php");
		return;
	}
	*/
}	

function formatSeconds( $seconds ) {
	$hours = intval($seconds / 3600);
	$seconds = $seconds - $hours*3600;
	$mins = intval($seconds / 60);
	$seconds = $seconds - $mins*60;
	return str_pad($hours, 2, '0', STR_PAD_LEFT).":".str_pad($mins, 2, '0', STR_PAD_LEFT).":".str_pad($seconds, 2, '0', STR_PAD_LEFT);
}

makeHeader("Descubre la Programación : Trabajo ", "", "log", false);

?>
<?php
		
	$from = time() - (3600*24*365);
	$to = time();
	$dates = workload( $from, $to, $user->getID(), $program ? $program->getID() : null );
	$from = date("d-m-Y", $from);
	$to = date("d-m-Y", $to);
	
	$total = 0;
	$cadena = "";
	foreach ($dates as $day=>$time) {
			$total += $time;
			$cadena = $cadena."{ day:'".$day."', time:".intval($time/60)."},";
	}
	if ( strlen($cadena) == 0 ) $cadena = "0";
	$typing = null;
	if ( $program ) {
		$typing = $program->getTyping()/1000;
	}
	?>
	<head>
</head>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h2><?php if ( $user ) echo $user->getName();?></h2>			
<?php 	if ( !$program ) {  ?>			
			<h4>Tiempo total trabajando en la web: <?php echo formatSeconds($total); ?></h4>			
<?php 	} ?>			
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
<?php 	if ( $program ) {  ?>			
			<h3>Programa: <?php echo $program->getTitle(); ?></h3>
			<h4>Tiempo total dedicado al programa: <?php echo formatSeconds($total); ?></h4>
<!--			<h4>Tiempo tecleando el programa: <?php echo formatSeconds($typing); ?></h4> -->
			
<?php 	} ?>			
			<h3></h3>
		</div>
	</div>
	<div class="row" style="z-index:1;">
		<div class="col-md-3">
<!--			<label>Intervalo de tiempo mostrado</label> -->
			<div class="form-group" id="duration">
				<div class="input-group">
					<span class="input-group-addon inline">Desde</span>
					<input class="desde-hasta form-control" type="text" id="desde" name="desde" value="<?php echo $from; ?>">
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group" id="duration">
				<div class="input-group">
					<span class="input-group-addon inline">Hasta</span>
					<input class="desde-hasta form-control" type="text" id="hasta" name="hasta" value="<?php echo $to; ?>">
				</div>
			</div>
		</div>
		<div class="col-md-2">
			<div class="input-group">
				<button class="btn btn-primary" type="button" id="buttonRefresh" title="Pulsa para actualizar gráfica">
					<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar
				</button>
			</div>
		</div>		
<!--		
		<div class="col-md-2">
			<div class="input-group date" data-provide="datepicker">
			    <input type="text" class="form-control">
			    <div class="input-group-addon">
			        <span class="glyphicon glyphicon-th"></span>
			    </div>
			</div>
		</div>		
-->
	</div>
	<div class="row">
		<div class="col-md-12">
		<div id="myfirstchart" style="height: 250px; z-index:0; background:#fff; border:1px solid#ccc; "></div>
		</div>
	</div>
	
	<!--
	<div class="row">
		<div class="col-md-4">
		<h4>Progreso por temas</h4>
		<?php $user->getKeyPoints(); ?>
		</div>
	</div>
	-->
</div>


	
<canvas id="pjs"> </canvas>
</div>
</div>
<script>	
var from = <?php echo mktime(0,0,0, 10,7,2014); ?>;
var to = <?php echo time(); ?>;
var plot = null;

function updatePlot(from, to) {
	$.post( "services/users.php", { service: "workload", user: "<?php echo $user->getID(); ?>", from: from, to: to <?php if ( $program ) echo ', program: "'.$program->getID().'"'; ?> },
		function( res ) {
			console.log(res);
		    var response = JSON.parse( res );
			plot.setData( response.dates );
		}
	);
}



	$('.desde-hasta').datepicker({
		format: 'dd-mm-yyyy',
		orientation: 'top',
	    clearBtn: true,
	    language: "es"
	}).on("change", function( event ) {
		if ( event.currentTarget.id == "desde" ) {			
			var res = event.currentTarget.value.split("-");
			var date = new Date( res[2], res[1]-1, res[0], 8,0,0,0 );
	    	from = date.getTime()/1000;
		}
		if ( event.currentTarget.id == "hasta" ) {
			var res = event.currentTarget.value.split("-");
			var date = new Date( res[2], res[1]-1, res[0], 8,0,0,0 );
	    	to = date.getTime()/1000;
		}
	});


$(document).ready(
function() {

	$("#buttonRefresh").on("click", function(event) {
		console.log("Refresh " + from + " " + to);
		updatePlot( from, to );
	});

	plot = new Morris.Bar({
	  // ID of the element in which to draw the chart.
	  element: 'myfirstchart',
	  // Chart data records -- each entry in this array corresponds to a point on
	  // the chart.
	  xkey: 'day',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['time'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: ['Minutos'],
	  resize: true
	});
	var initial = [<?php echo $cadena; ?>]; 
	plot.setData(initial);
});


</script>

</body>
<script>
</script>
</html>
