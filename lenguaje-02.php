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
			<h1>El lenguaje de programación iJava (2)</h1>
		</div>
	</div>
	<div class="col-md-12">
<h2>Variables y constantes</h2>
		<p>Las variables y las constantes nos permiten darle un nombre a un valor y utilizar el nombre en cualquier parte del programa donde podríamos utilizar el valor. Como ambas guardan valores y hay valores de diferentes tipos es necesario indicar de qué tipo serán los valores que guardarán.
		</p>
		<p>Para crear una variable escribiremos el nombre del tipo seguido del nombre que queremos que tenga la variable y tras un símbolo <strong>=</strong> el valor que queramos que tenga dicha variable. Los dos tipos que hemos estado manejando hasta el momento son enteros y reales. Para crear una variable de tipo entera usaremos como tipo <strong>int</strong> y para crear una variable que guarde valores reales usaremos <strong>double</strong>.</p>
<pre><code>void main() {
	// Declaramos una variable para guardar la altura
	double altura = 2.5;
	// Declaramos una variable para guardar el número de ruedas
	int ruedas = 4;
	// Calculo el área de un rectángulo de base 3 metros y altura 2.5 metros
	println(altura * 3.0);
	// Calculo lo que cuesta cambiar las cuatro ruedas si una vale 150?
	println(ruedas * 150);
}</code></pre>
		<p>Las constantes se crean de igual forma pero escribiendo la palabra <strong>final</strong> al principio. En el siguiente ejemplo declaramos la constante GRAVEDAD que representa la fuerza de gravedad en la tierra. Normalmente el nombre de las constantes se pone con todas las letras en mayúsculas.</p>
<pre><code>void main() {
	// Declaramos una constante
	final double GRAVEDAD = 9.8;
	// Variable con la altura en metros del objeto
	double altura = 10;
	// Cálculo del tiempo que tarda en caer
	double tiempo = sqrt(2.0*altura/GRAVEDAD);
	println(tiempo);
}</code></pre>
<p>En el programa anterior hemos creado nuestra propia constante pero iJava también tiene algunas constantes predefinidas como PI y E. El siguiente programa utiliza dicha constante para calcular la altura de un triángulo rectángulo conociendo la hipotenusa y el ángulo opuesto.</p>
<pre><code>void main() {
	double hipotenusa = 10;
	double angulo = PI/6.0;
	double altura = hipotenusa * sin(angulo);
	println(altura);
}</code></pre>
		
		<p> La diferencia principal entre una constante y una variable es que las variables pueden cambiar de valor durante la ejecución del programa. Para modificar el valor de una variable usaremos la instrucción de asignación que consiste en indicar el nombre de la variable, un símbolo <strong>=</strong>, el valor que queremos que tome la variable y, como siempre, acabar la instrucción con un punto y coma. Por ejemplo, en el siguiente programa usamos la misma variable dos veces, pero cada vez tiene un valor distinto porque, entre medias, lo hemos modificado con una instrucción de asignación.</p>
<pre><code>void main() {
	// Declaramos una variable para guardar la altura
	double altura = 2.5;
	// Declaramos una variable para guardar la anchura
	double anchura = 3.0;
	// Mostramos el área del rectángulo
	println(anchura * altura);
	// Modificamos la altura
	altura = 5.0;
	// Mostramos el área del rectángulo otra vez
	println(anchura * altura);
	// Modificamos la anchura
	anchura = altura * 2;
	// Mostramos el área del rectángulo otra vez
	println(anchura * altura);	
}</code></pre>
		<p>Como se ve en el ejemplo anterior, el valor que se le da a una variable puede ser un literal, es decir, un valor escrito directamente como el 5 que se asigna a <strong>altura</strong> en el ejemplo anterior, o puede ser el resultado de una operación, como en el caso de la asignación que se hace a <strong>anchura</strong> que será el resultado de multiplicar el valor de <strong>altura</strong> (5 en este caso) por 2. En este último caso es posible utilizar la misma variable cuyo valor pretendemos modificar ya que el valor que se usará para hacer el cálculo será el que tenga hasta ese momento.</p>
		<p>Además también es posible aumentar o reducir en una unidad el valor de una variable de tipo entero o real usando los operadores <strong>++</strong> y <strong>--</strong>. Por ejemplo, para aumentar en uno una variable llamada a de tipo entero escribiríamos <strong>a++;</strong>. Sin embargo el código resulta más fácil de entender si se usa la fórmula de asignación normal equivalente, es decir <strong>a = a + 1</strong> o <strong>a = a - 1</strong> para reducir el valor.</p>
		<h2>Funciones para pedir datos</h2>
		<p>Para que nuestros programas pidan al usuario que introduzca un dato podemos usar las siguientes funciones de biblioteca. Las siguientes son las funciones que nos permiten pedir al usuario números enteros o reales.
		</p>
		<ul>
		<li><a href="editor-codigo.php?id=7fbb64ac6ac351fd0c4d121e29515bf3">readInteger()</a></li>
		<li><a href="editor-codigo.php?id=6892f0eec96a4a5aba70410a0335ce94">readDouble()</a></li>
		<li><a href="editor-codigo.php?id=24e7ca5081c0e8716e04ae2b9d447e02">readChar()</a></li>
		<li><a href="editor-codigo.php?id=9618b5655ee809f5fe3d96f823ac1d8e">readString()</a></li>
		</ul>
		<p>Las dos primeras funciones nos sirven para pedir al usuario un número ya sea entero o real. Las dos siguientes nos permiten preguntar al usuario por una letra y por un texto respectivamente. Hasta el momento sólo hemos utilizado dos tipos de datos de los que tiene iJava, aquí vemos otros dos tipos: <strong>char</strong> y <strong>String</strong>. El primero representa caracteres y el segundo representa texto. Podemos crear variables de ambos tipos y almacenar en las de tipo <strong>char</strong> una única letra y en las de tipo <strong>String</strong> un texto completo. Observa el siguiente programa.</p>
<pre><code>void main() {
	char letra = readChar("Escribe una letra");
	println(letra);
	String nombre = readString("Escribe tu nombre");
	String mensaje = "Hola, " + nombre + " el programa ha terminado";
	println(mensaje);	
}</code></pre>
		<p>Fíjate bien como podemos concatenar texto usando el símbolo <strong>+</strong>. Esto nos permite hacer que los mensajes incluyan valores calculados o solicitados al usuario y hace que nuestros programas sean más interesantes. Observa también que es importante escribir el tipo <strong>String</strong> usando con la primera letra en mayúscula, de no hacerlo el programa contendrá un error.</p>		
		
		<h2>Funciones propias</h2>
		<p>Además de utilizar funciones que ya incluye iJava es posible escribir nuestras propias funciones. Nuestras funciones serán un trozo de código al que le daremos un nombre y unos parámetros y que podremos utilizar en el resto de nuestro programa. Por ejemplo, para escribir una función llamada estrella haremos lo siguiente.</p>
<pre><code>void estrella() {
	triangle(160,100, 220,200, 100,200);
	triangle(160,220, 220,130, 100,130);
}

void main() {
	estrella();
}</code></pre>
		<p>Como puedes ver el código asociado a la funciún estrella se escribe dentro de dos llaves y la función estrella va precedida de la palabra clave <strong>void</strong> que es la misma que usamos para definir la función principal o función <strong>main</strong>. La función <strong>estrella</strong> tal y como la hemos escrito siempre dibuja la estrella en el mismo sitio. Si quisiéramos que cada vez la dibujara en una posición diferente podemos añadir un par de variables que representen las coordenadas del centro y en lugar de usar valores literales para las coordenadas de los dos triángulos que dibujan la estrella calcular dichas posiciones en relación al centro de la estrella. El siguiente programa muestra cómo hacer lo que acabamos de contar.</p>
		
<pre><code>void estrella() {
	int x = round(random(320));
	int y = round(random(320));
	triangle(x,y-60, x+60,y+40, x-60,y+40);
	triangle(x,y+60, x+60,y-30, x-60,y-30);
}

void main() {
	estrella();
}</code></pre>
		<p>Además, podemos poner parámetros a nuestras funciones escribiendo entre los paréntesis la lista de nombres que queremos que tengan así como el tipo de cada uno. Dichos nombre actuarán como variables dentro de la propia función y su valor será el que se haya puesto al usar la función en otra parte del programa. Por ejemplo, para poner como parámetros las dos coordenadas que representan el centro de la estrella haremos lo siguiente.</p>
<pre><code>void estrella(double x, double y) {
	triangle(x,y-60, x+60,y+40, x-60,y+40);
	triangle(x,y+60, x+60,y-30, x-60,y-30);
}
	
void main() {
	estrella(80, 80);
	estrella(250,90);
	estrella(160,250);
}</code></pre>
		<p>
		Podemos escribir funciones que no devuelven ningún valor sino que realizan alguna tarea cuyo resultado aparece en la pantalla en forma de dibujo o texto como la función estrella que acabamos de escribir. Y también es posible escribir funciones que como <strong>sin</strong> o <strong>cos</strong> realizan un cílculo y devuelven un valor que podemos utilizar en una expresión. En este caso es necesario indicar en lugar de <strong>void</strong> el tipo que devolverá la función e incluir una instrucción llamada <strong>return</strong> dentro de la función que se encargue de hacer que la función termine y se haga la devolución del valor. Por ejemplo, una función que calcule el cuadrado de un número la escribiremos así</p>
<pre><code>double cuadrado(double x) {
	return x * x;
}
	
void main() {
	double y = cuadrado(4);
	println(y);
	println(cuadrado(8));
}</code></pre><p>
				Sigue aprendiendo en <a href="lenguaje-03.php">El lenguaje de programación iJava (3)</a>
</p>
			<p></br></p>	</div>		
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
