<?php
include_once("common.php");

if ( ! isset( $_GET['id'] ) || !is_numeric( $_GET['id'] ) ) {
	header("location:aprende.php");
	return;
}

$tema = $_GET['id'];

$temas = array (
array( "src" => "http://www.youtube.com/embed/TBG9VyJlbng?rel=0", "h1" => "Sesi�n 0. Introducci�n", "p" => "En este primer v�deo vamos a ver un ejemplo de lo que llegar�s a aprender en este curso" ),
array( "src" => "http://www.youtube.com/embed/nHFUpbifMeI?rel=0", "h1" => "Sesi�n 1. Haciendo dibujos", "h2" => "Tu primer programa", "p" => "En este v�deo vamos a ver c�mo dibujar un oso usando elipses y c�mo ponerle un marco usando la funci�n rect. Tambi�n veremos lo que son los comentarios y la importancia que tiene el �rden en el que se escriben las instrucciones." ), 
array( "src" => "http://www.youtube.com/embed/4fAeGVlZEZc?rel=0", "h1" => "Sesi�n 1. Haciendo dibujos", "h2" => "Un oso enmarcado", "p" => "En este v�deo aprenderemos a usar la funci�n rect y a comentar el c�digo. Tambi�n lo importante que es el orden en el que se escriben las instrucciones." ), 
array( "src" => "http://www.youtube.com/embed/aU0a6C_ozlE?rel=0", "h1" => "Sesi�n 1. Haciendo dibujos", "h2" => "La bandera ol�mpica", "p" => "En este v�deo veremos c�mo dibujar la bandera ol�mpica aprovechando que podemos hacer c�lculos dentro de nuestros programas. Tambi�n veremos c�mo aplicar la idea de posici�n relativa para calcular m�s f�cilmente las posiciones de nuestros dibujos." ), 
array( "src" => "http://www.youtube.com/embed/4itwg3m5YRU?rel=0", "h1" => "Sesi�n 1. Haciendo dibujos", "h2" => "Coloreando la bandera", "p" => "En este v�deo vemos c�mo dar color a las figuras que dibujamos, evitar que se rellenen y modificar su grosor para conseguir hacer los aros ol�mpicos." ), 
array( "src" => "http://www.youtube.com/embed/tEYX_6LH6bg?rel=0", "h1" => "Sesi�n 1. Haciendo dibujos", "h2" => "Colores RGB", "p" => "En este v�deo se describe el formato RGB y c�mo se utiliza para crear colores s�lidos y tambi�n se explica como conseguir colores transparentes." ), 
array( "src" => "http://www.youtube.com/embed/mPr6RKDoQTA?rel=0", "h1" => "Sesi�n 2. Moviendo los dibujos", "h2" => "Dibujando una nave en cualquier parte de la pantalla", "p" => "En este v�deo aprenderemos a utilizar variables para que cambiando un s�lo n�mero todo el dibujo de nuestro personaje cambie completamente de sitio." ), 
array( "src" => "http://www.youtube.com/embed/V1cNX2sgDOQ?rel=0", "h1" => "Sesi�n 2. Moviendo los dibujos", "h2" => "Modificando el tama�o de la nave", "p" => "En este v�deo utilizamos una variable m�s para controlar el tama�o con el que se dibuja la nave pero manteniendo sus proporciones. Adem�s se usa por primera vez la funci�n random." ), 
array( "src" => "https://www.youtube.com/embed/XbCWu3L7ArY?rel=0", "h1" => "Sesi�n 2. Moviendo los dibujos", "h2" => "Las variables en los juegos", "p" => "En este v�deo se muestra c�mo escribir un programa que dibuja l�neas horizontales en posiciones aleatorias y se explica donde son �tiles las variables en los videojuegos." ), 
array( "src" => "http://www.youtube.com/embed/Qjc5f6jvNjI?rel=0", "h1" => "Sesi�n 2. Moviendo los dibujos", "h2" => "La funci�n 'nave'", "p" => "En este v�deo veremos como dibujar muchas naves de forma muy sencilla creando nuestra propia funci�n." ), 
array( "src" => "http://www.youtube.com/embed/5jhgXsjicZY?rel=0", "h1" => "Sesi�n 2. Moviendo los dibujos", "h2" => "Funciones propias con par�metros", "p" => "En este v�deo se explica como crear funciones propias que tengan par�metros. Esto es muy �til para poder controlar d�nde se dibuja la nave usando valores al ejecutar la funci�n." ), 
array( "src" => "http://www.youtube.com/embed/5e-uckq7gDQ?rel=0", "h1" => "Sesi�n 3. Simplificando las tareas repetitivas", "h2" => "Dibujando cientos de estrellas", "p" => "En este v�deo aprenderemos a escribir programas que repitan instrucciones usando el comando for. Veremos c�mo dibujar cientos de estrellas con tres l�neas de c�digo y muchas cosas m�s." ), 
array( "src" => "http://www.youtube.com/embed/3wJuXXqW6t0?rel=0", "h1" => "Sesi�n 3. Simplificando las tareas repetitivas", "h2" => "Dibujando c�rculos conc�ntricos", "p" => "En este v�deo vamos a aprender a modificar variables dentro de un bucle for para conseguir hacer dibujos que tengan una estructura repetitiva." ), 
array( "src" => "http://www.youtube.com/embed/Kfww7PSaWCw?rel=0", "h1" => "Sesi�n 3. Simplificando las tareas repetitivas", "h2" => "Dibujando un tablero de ajedrez", "p" => "En este v�deo vamos a ver c�mo utilizar bucles for para dibujar un tablero de ajedrez." ), 
array( "src" => "http://www.youtube.com/embed/sUt7I-flxHU?rel=0", "h1" => "Sesi�n 3. Simplificando las tareas repetitivas", "h2" => "Rellenando un tablero con 100 naves", "p" => "En este v�deo veremos c�mo utilizar bucles anidados para repetir instrucciones que repiten otras instrucciones y poder as� hacer muchas m�s cosas con menos l�neas de c�digo." ), 
array( "src" => "http://www.youtube.com/embed/7e3g3JdbXpM?rel=0", "h1" => "Sesi�n 3. Simplificando las tareas repetitivas", "h2" => "Dibujando una flota de enemigos", "p" => "Utilizando los bucles anidados que aprendimos en el v�deo anterior veremos c�mo dibujar una flota completa de enemigos para nuestro juego de naves." ), 
array( "src" => "http://www.youtube.com/embed/VyLIPybKyY8?rel=0", "h1" => "Sesi�n 4. Haciendo que el ordenador decida", "h2" => "Animando nuestra nave", "p" => "En este v�deo aprenderemos a hacer programas interactivos y a controlar la nave de nuestro juego  con el rat�n. Adem�s empezaremos a dotar a nuestros programas de capacidad para tomar decisiones." ), 
array( "src" => "http://www.youtube.com/embed/An3lrMrHqt8?rel=0", "h1" => "Sesi�n 4. Haciendo que el ordenador decida", "h2" => "Disparando el laser con el rat�n", "p" => "En este v�deo aprenderemos a detectar cu�ndo est� pulsado el bot�n del rat�n para que nuestra nave lance un rayo l�ser. Adem�s veremos c�mo crear nuestras propias variables booleanas." ), 
array( "src" => "http://www.youtube.com/embed/gI3aN3Djzow?rel=0", "h1" => "Sesi�n 4. Haciendo que el ordenador decida", "h2" => "Animando una pelota que rebota", "p" => "En este v�deo aprenderemos a usar variables globales para hacer nuestras animaciones." ), 
array( "src" => "http://www.youtube.com/embed/lB67iaZtguk?rel=0", "h1" => "Sesi�n 4. Haciendo que el ordenador decida", "h2" => "Disparando proyectiles", "p" => "En este v�deo utilizamos variables globales para controlar la posici�n y velocidad de las balas que dispara nuestra nave al pulsar con el bot�n del rat�n." ), 
array( "src" => "http://www.youtube.com/embed/oTfDlKNX1xw?rel=0", "h1" => "Sesi�n 4. Haciendo que el ordenador decida", "h2" => "Detectando colisiones entre balas y enemigos", "p" => "En este v�deo aprenderemos a detectar cu�ndo se produce un impacto mediante estructuras if que combinan varias comparaciones al mismo tiempo.") );

$url = $temas[$tema]["src"];
$h1 = $temas[$tema]["h1"];
$h2 = $temas[$tema]["h2"] ? $temas[$tema]["h2"] : "";
$p = $temas[$tema]["p"];

makeHeader("Descubre la Programaci�n", "Explora, aprende y crea tus propios programas", "", true);
?>

<?php include ("admin/ayuda.php"); ?>
<div id="colorpicker-div"></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">		
			<div class="embed-responsive embed-responsive-16by9">
			  <iframe class="embed-responsive-item" src="<?php echo $url; ?>"></iframe>
		    </div>
		</div>
		<div class="col-md-4">
			<h3><?php echo $h1; ?></h3>
			<h4><?php echo $h2; ?></h4>
			<p><?php echo $p; ?></p>
<?php	if ( $tema > 1 ) { 				?>
			<input type="button" value="Lecci�n anterior" class="btn btn-primary"  onclick="window.location='tema.php?id=<?php echo $tema-1; ?>'">
<?php	}												?>
<?php	if ( $tema+1 < sizeof( $temas ) ) { 				?>
			<input type="button" value="Siguiente Lecci�n" class="btn btn-primary"  onclick="window.location='tema.php?id=<?php echo $tema+1; ?>'">
<?php	}												?>
		</div>
	</div>
	&nbsp;

	<div class="row">
		<div class="col-sm-offset-8 col-sm-4 col-xs-4">
			<div class="form-group">

				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-question-sign"></span> Ayuda</button>
				
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button class="btn btn-primary " type="button" id="buttonRun" title="Pulsa para probar el programa" tabindex=3 >
					<span class="glyphicon glyphicon-play" aria-hidden="true" id="buttonRunGlyph"></span> <span id="buttonRunText">Probar</span></button>
				</div>
			</div>
		</div>		
	</div>


	<div class="row">
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<textarea id="textarea-editor" name="content" class="ed form-control" rows="16" tabindex=2 ></textarea>
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
		</div>		
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
var editor = null;

$(document).ready(
function() {

	var gui = {
		onCodeChange: function() {
			$("#buttonSave").removeClass("disabled");
		},
		onProgramStarted: function() {
			console.log("started");
			$("#buttonRunText").text("Parar");
			$("#buttonRunGlyph").removeClass("glyphicon-play");
			$("#buttonRunGlyph").addClass("glyphicon-stop");			
		},
		onProgramStopped: function() {
			console.log("stopped");
			$("#buttonRunText").text("Probar");
			$("#buttonRunGlyph").removeClass("glyphicon-stop");			
			$("#buttonRunGlyph").addClass("glyphicon-play");
		}
	};
	
    editor = new iJavaEditor("textarea-editor", "mycanvas", "output", "compileroutput", gui);
    
	
	$("#buttonRun").on("click", function( event ) {
		editor.run();
	});    
});

</script>
</html>
