<?php
include_once("common.php");

makeHeader("Descubre la Programaci�n", "Explora, aprende y crea tus propios programas", "aprende", false);
?>
<style>
p {font-size:1em;line-height:1.3em;}
</style>
<div class="container-fluid">
	<div class="col-md-12">
		<div class="apage-header">
			<h1>El lenguaje de programaci�n iJava (3)</h1>
		</div>
	</div>
	<div class="col-md-12">
	
	<h2>Animaciones</h2>
	<p>Una de las cosas que mejor hacen los ordenadores es repetir un conjunto de instrucciones. Los lenguajes de programaci�n nos permiten expresar lo que queremos que se repita y cu�ntas veces queremos que lo haga. En iJava existen varias formas de hacerlo. La m�s sencilla es la que nos permite indicar al ordenador que ejecute una de nuestras funciones y cuando termine la vuelva a ejecutar. Esto nos va a servir adem�s para hacer animaciones gr�ficas.</p>
	<p>Para indicar que queremos que el programa comience un ciclo potencialmente infinito de repeticiones de una funci�n en concreto usaremos la funci�n <strong>animate</strong> a la que le pasaremos como par�metro el nombre de la funci�n que queramos que se repita continuamente. Lo �nico importante es que la funci�n a ejecutar continuamente no debe tener par�metros y debe ser de las que no devuelven nada, es decir, de tipo <strong>void</strong>.</p>
<pre><code>void dibuja() {
	ellipse(160,160,50,50);
}

void main() {
	animate(dibuja);
}</code></pre>
<p>Al probar el programa aparece un c�rculo blanco en el centro de la pantalla pero si te fijas ver�s que en el bot�n que hemos pulsado para probar el programa ahora pone parar. Lo que pasa es que la ejecuci�n de la funci�n <strong>animate</strong> no para hasta que se lo indiquemos pulsando el bot�n. Mientras que no lo hagamos se estar� ejecutando la funci�n dibuja 25 veces por segundo. Sin embargo, como nuestra funci�n siempre dibuja lo mismo no se nota. Para que se note vamos a hacer que los c�rculos se dibujen en posiciones aleatorias.</p>
<pre><code>void dibuja() {
	int x = round(random(320));
	int y = round(random(320));
	ellipse(x,y,50,50);
}

void main() {
	animate(dibuja);
}</code></pre>
<p>Durante la ejecuci�n de un programa como el anterior en los que se est� repitiendo continuamente la ejecuci�n de una funci�n podemos adem�s utilizar algunas variables que existen en iJava aunque no las hayamos creado nosotros. Esta variables guardan en todo momento la posici�n del rat�n y se llaman <strong>mouseX</strong> y <strong>mouseY</strong>. Prueba a usar dichas variables en lugar de <strong>x</strong> e <strong>y</strong> en el programa anterior y tras probar mueve el rat�n por encima de la pantalla de iJava.</p>

	<h2>Variables locales y globales</h2>
	<p>Las variables que creamos dentro de una funci�n s�lo se pueden usar en el c�digo de la propia funci�n, fuera no existen por lo que en una funci�n diferente podemos volver a crear variables con el mismo nombre. Las variables que s�lo existen dentro de una funci�n se denominan variables locales y cada vez que se ejecuta la funci�n son creadas de nuevo. Los par�metros se consideran tambi�n variables locales a la funci�n. Por el contrario, si creamos una variable fuera de cualquier funci�n esta variable se considera global y puede ser utilizada en cualquier funci�n. Adem�s, el valor de la misma se mantiene entre llamadas a las distintas funciones.</p>
	<p>En el siguiente programa se usan dos variables, una global llamada <strong>n</strong> y otra local a la funci�n <strong>dibuja</strong> llamada <strong>m</strong>. A las dos variables se les modifica su valor en cada repetici�n de la funci�n sumando uno al valor que ya tuvieran y su valor es mostrado en la pantalla. Sin embargo, s�lo la variable global aumenta de valor con el paso del tiempo puesto que s� guarda el valor entre llamadas a la funci�n <strong>dibuja</strong> mientras que la local se crea cada vez que se ejecuta la funci�n.</p>

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
<p>Hay que tener en cuenta que no pueden haber dos variables globales con el mismo nombre y tampoco pueden haber dos variables locales con el mismo nombre dentro de una misma funci�n. Sin embargo, funciones distintas s� pueden tener variables o incluso par�metros con el mismo nombre. De hecho, pueden tener variables con el mismo nombre que el que tengan variables globales pero no es aconsejable hacer esto.</p>

	<h2>Repetir c�digo un n�mero fijo de veces</h2>
	<p>Si lo que queremos es que una o varias instrucciones se repitan un n�mero concreto de veces entonces podemos usar la estructura <strong>for</strong>. La estructura <strong>for</strong> nos permite especificar cu�ntas repeticiones queremos hacer y el c�digo que queremos que se repita sin necesidad de que este se encuentre dentro de una funci�n concreta. Por ejemplo, si queremos mostrar cinco veces el mensaje "Hola mundo" haremos lo siguiente</p>

<pre><code>void main() {
	for ( int vez = 1 ; vez <= 5 ; vez = vez + 1 ) {
		println("Hola mundo!");
	}
}</code></pre>
	<p>La estructura <strong>for</strong> nos permite hacer lo que en programaci�n denominamos bucle y se compone de cuatro partes, las tres primeras est�n dentro de los par�ntesis y se separan con punto y coma. La cuarta se corresponde con el conjunto de instrucciones que hay encerrado entre las dos llaves. La estructura <strong>for</strong> en iJava se comporta exactamente igual que en Java. Sin embargo nosotros recomendamos utilizarla siempre del modo en que lo hemos hecho antes. Es decir, para conseguir que la cuarta parte se repita <strong>N</strong> veces crearemos una variable en la primera parte del <strong>for</strong> a la que le daremos 1 como valor inicial. En la segunda parte escribiremos el nombre de la variable seguido del s�mbolo <strong><=</strong> y el n�mero de repeticiones que queramos que se lleven a cabo. La tercera parte consistir� en una instrucci�n de asignaci�n en la que modificaremos el valor de la variable que hemos creado en la primera parte sum�ndole uno al valor que ya tuviera para que vaya contando el n�mero de repeticiones que se van haciendo.</p>

	<p>La variable que creamos en la primera parte del <strong>for</strong> se puede utilizar dentro del conjunto de instrucciones que forma la cuarta parte del mismo como cualquier otra variable. Sin embargo es muy recomendable no modificar su valor ya que se alterar�a el n�mero de repeticiones que se hacen.</p>
	<h2>Bucles anidados</h2>
	<p>La estructura <strong>for</strong> puede utilizarse en cualquier parte de nuestro programa incluso dentro de otra estructura <strong>for</strong>. A esto le llamamos bucles anidados. En este caso conseguiremos que para cada repetici�n del bucle externo se haga un ciclo completo de repeticiones del bucle interno.</p>
<pre><code>void main() {
	for ( int tabla = 1 ; tabla <= 10 ; tabla = tabla + 1 ) {
		println("Tabla del " + tabla);
		for ( int n = 1 ; n <= 10 ; n = n + 1 ) {
			int resultado = tabla * n;
			println(tabla + " x " + n + " = " + resultado);
		}
	}
}</code></pre>
<p>En el ejemplo anterior utilizamos un bucle externo para enumerar las tablas de multiplicar desde la del uno hasta la del 10. En cada repetici�n de ese bucle se muestra un mensaje indicando la tabla que se va a calcular. A continuaci�n, hacemos otro bucle completo del 1 al 10 para calcular los diez resultados de la tabla que toca. F�jate que en este caso estamos utilizando directamente los valores de las variables que sirven para contar el n�mero de repeticiones de la estructura <strong>for</strong>.</p>	
<p>
				Sigue aprendiendo en <a href="lenguaje-04.php">El lenguaje de programaci�n iJava (4)</a>
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
