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
			<h1>El lenguaje de programación iJava (3)</h1>
		</div>
	</div>
	<div class="col-md-12">
	
	<h2>Animaciones</h2>
	<p>Una de las cosas que mejor hacen los ordenadores es repetir un conjunto de instrucciones. Los lenguajes de programación nos permiten expresar lo que queremos que se repita y cuántas veces queremos que lo haga. En iJava existen varias formas de hacerlo. La más sencilla es la que nos permite indicar al ordenador que ejecute una de nuestras funciones y cuando termine la vuelva a ejecutar. Esto nos va a servir además para hacer animaciones gráficas.</p>
	<p>Para indicar que queremos que el programa comience un ciclo potencialmente infinito de repeticiones de una función en concreto usaremos la función <strong>animate</strong> a la que le pasaremos como parámetro el nombre de la función que queramos que se repita continuamente. Lo único importante es que la función a ejecutar continuamente no debe tener parámetros y debe ser de las que no devuelven nada, es decir, de tipo <strong>void</strong>.</p>
<pre><code>void dibuja() {
	ellipse(160,160,50,50);
}

void main() {
	animate(dibuja);
}</code></pre>
<p>Al probar el programa aparece un círculo blanco en el centro de la pantalla pero si te fijas verás que en el botón que hemos pulsado para probar el programa ahora pone parar. Lo que pasa es que la ejecución de la función <strong>animate</strong> no para hasta que se lo indiquemos pulsando el botón. Mientras que no lo hagamos se estará ejecutando la función dibuja 25 veces por segundo. Sin embargo, como nuestra función siempre dibuja lo mismo no se nota. Para que se note vamos a hacer que los círculos se dibujen en posiciones aleatorias.</p>
<pre><code>void dibuja() {
	int x = round(random(320));
	int y = round(random(320));
	ellipse(x,y,50,50);
}

void main() {
	animate(dibuja);
}</code></pre>
<p>Durante la ejecución de un programa como el anterior en los que se está repitiendo continuamente la ejecución de una función podemos además utilizar algunas variables que existen en iJava aunque no las hayamos creado nosotros. Esta variables guardan en todo momento la posición del ratón y se llaman <strong>mouseX</strong> y <strong>mouseY</strong>. Prueba a usar dichas variables en lugar de <strong>x</strong> e <strong>y</strong> en el programa anterior y tras probar mueve el ratón por encima de la pantalla de iJava.</p>

	<h2>Variables locales y globales</h2>
	<p>Las variables que creamos dentro de una función sólo se pueden usar en el código de la propia función, fuera no existen por lo que en una función diferente podemos volver a crear variables con el mismo nombre. Las variables que sólo existen dentro de una función se denominan variables locales y cada vez que se ejecuta la función son creadas de nuevo. Los parámetros se consideran también variables locales a la función. Por el contrario, si creamos una variable fuera de cualquier función esta variable se considera global y puede ser utilizada en cualquier función. Además, el valor de la misma se mantiene entre llamadas a las distintas funciones.</p>
	<p>En el siguiente programa se usan dos variables, una global llamada <strong>n</strong> y otra local a la función <strong>dibuja</strong> llamada <strong>m</strong>. A las dos variables se les modifica su valor en cada repetición de la función sumando uno al valor que ya tuvieran y su valor es mostrado en la pantalla. Sin embargo, sólo la variable global aumenta de valor con el paso del tiempo puesto que sí guarda el valor entre llamadas a la función <strong>dibuja</strong> mientras que la local se crea cada vez que se ejecuta la función.</p>

<pre><code>// n es una variable global
int n = 0;

void dibuja() {
	// m es una variable local
	int m = 0;
	n = n + 1;
	m = m + 1;
	// Borramos el fondo de la pantalla
	background(0,0,0);
	// Mostramos el valor de cada variable
	text("n = " + n, 140, 100);
	text("m = " + m, 140, 120);
}

void main() {
	animate(dibuja);
}</code></pre>
<p>Hay que tener en cuenta que no pueden haber dos variables globales con el mismo nombre y tampoco pueden haber dos variables locales con el mismo nombre dentro de una misma función. Sin embargo, funciones distintas sí pueden tener variables o incluso parámetros con el mismo nombre. De hecho, pueden tener variables con el mismo nombre que el que tengan variables globales pero no es aconsejable hacer esto.</p>

	<h2>Repetir código un número fijo de veces</h2>
	<p>Si lo que queremos es que una o varias instrucciones se repitan un número concreto de veces entonces podemos usar la estructura <strong>for</strong>. La estructura <strong>for</strong> nos permite especificar cuántas repeticiones queremos hacer y el código que queremos que se repita sin necesidad de que este se encuentre dentro de una función concreta. Por ejemplo, si queremos mostrar cinco veces el mensaje "Hola mundo" haremos lo siguiente</p>

<pre><code>void main() {
	for ( int vez = 1 ; vez <= 5 ; vez = vez + 1 ) {
		println("Hola mundo!");
	}
}</code></pre>
	<p>La estructura <strong>for</strong> nos permite hacer lo que en programación denominamos bucle y se compone de cuatro partes, las tres primeras están dentro de los paréntesis y se separan con punto y coma. La cuarta se corresponde con el conjunto de instrucciones que hay encerrado entre las dos llaves. La estructura <strong>for</strong> en iJava se comporta exactamente igual que en Java. Sin embargo nosotros recomendamos utilizarla siempre del modo en que lo hemos hecho antes. Es decir, para conseguir que la cuarta parte se repita <strong>N</strong> veces crearemos una variable en la primera parte del <strong>for</strong> a la que le daremos 1 como valor inicial. En la segunda parte escribiremos el nombre de la variable seguido del símbolo <strong><=</strong> y el número de repeticiones que queramos que se lleven a cabo. La tercera parte consistirá en una instrucción de asignación en la que modificaremos el valor de la variable que hemos creado en la primera parte sumándole uno al valor que ya tuviera para que vaya contando el número de repeticiones que se van haciendo.</p>

	<p>La variable que creamos en la primera parte del <strong>for</strong> se puede utilizar dentro del conjunto de instrucciones que forma la cuarta parte del mismo como cualquier otra variable. Sin embargo es muy recomendable no modificar su valor ya que se alteraría el número de repeticiones que se hacen.</p>
	<h2>Bucles anidados</h2>
	<p>La estructura <strong>for</strong> puede utilizarse en cualquier parte de nuestro programa incluso dentro de otra estructura <strong>for</strong>. A esto le llamamos bucles anidados. En este caso conseguiremos que para cada repetición del bucle externo se haga un ciclo completo de repeticiones del bucle interno.</p>
<pre><code>void main() {
	for ( int tabla = 1 ; tabla <= 10 ; tabla = tabla + 1 ) {
		println("Tabla del " + tabla);
		for ( int n = 1 ; n <= 10 ; n = n + 1 ) {
			int resultado = tabla * n;
			println(tabla + " x " + n + " = " + resultado);
		}
	}
}</code></pre>
<p>En el ejemplo anterior utilizamos un bucle externo para enumerar las tablas de multiplicar desde la del uno hasta la del 10. En cada repetición de ese bucle se muestra un mensaje indicando la tabla que se va a calcular. A continuación, hacemos otro bucle completo del 1 al 10 para calcular los diez resultados de la tabla que toca. Fíjate que en este caso estamos utilizando directamente los valores de las variables que sirven para contar el número de repeticiones de la estructura <strong>for</strong>.</p>	
<p>
				Sigue aprendiendo en <a href="lenguaje-04.php">El lenguaje de programación iJava (4)</a>
</p>
			<p></br></p>	</div>		
</div>
<?php
	makeFooter();
?>
</body>
<script>
$('.sintax').each(function() {

    var $this = $(this),
        $code = $this.html();

    $this.empty();

    var myCodeMirror = CodeMirror(this, {
        value: $code,
        mode: 'text/x-ijava',
        lineNumbers: !$this.is('.inline'),
        readOnly: true,
        lineWrapping: true
    });
    myCodeMirror.setSize("auto","auto");
});
$(document).ready(function() {
  $('pre code').each(function(i, e) {hljs.highlightBlock(e,"javascript")});
});
</script>
</html>
