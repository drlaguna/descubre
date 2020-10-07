<?php
include_once("common.php");

makeHeader("Descubre la Programación", "Explora, aprende y crea tus propios programas", "aprende", false);
?>
<style>
p {font-size:1em;line-height:1.3em;}
</style>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="apage-header">
			<h1>El lenguaje de programación iJava <small></small></h1>
		</div>
	</div>
	<div class="col-md-12">
		<p>
		iJava es un lenguaje de programación diseñado para servir como lenguaje de iniciación a la programación. Su sintaxis es un subconjunto de la de <a href="http://www.java.com/es/download/">Java</a> y, por tanto, coincide con la de <a href="http://processing.org/">Processing</a>. De hecho, casi todas las funciones de biblioteca relacionadas con los gráficos que incluye iJava son muy similares a las que se pueden encontrar en Processing.
		</p>
		<p>
		En los siguientes enlaces puedes encontrar una guía de uso del lenguaje en la que se explica con ejemplos todo lo que puedes hacer con iJava en el mismo orden en que está diseñado el curso que puedes seguir en la ventana <a href="aprende.php">aprende</a>.
		</p>
			<ul>
				<li><a href="lenguaje-01.php">El entorno de programación y las funciones predefinidas.</a></li>
				<li><a href="lenguaje-02.php">Variables, constantes y entrada de datos.</a></li>
				<li><a href="lenguaje-03.php">Introducción a las animaciones y a los Bucles.</a></li>
				<li><a href="lenguaje-04.php">Condiciones y bucles con condición.</a></li>
				<li><a href="lenguaje-05.php">Arrays simples y multidimensionales.</a></li>
				<li><a href="lenguaje-06.php">Control de teclado y ratón.</a></li>
				<li><a href="lenguaje-07.php">Funciones para manejar texto.</a></li>
			</ul>
			<p></br></p>
	</div>		
</div>
<?php
	makeFooter();
?>
</body>
<script>
$(document).ready(function() {
  $('pre code').each(function(i, e) {hljs.highlightBlock(e,"javascript")});
});
</script>
</html>
