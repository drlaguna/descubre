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
			<h1>El lenguaje de programación iJava (4)</h1>
		</div>
	</div>
	<div class="col-md-12">
	<h2>Decisiones</h2>
	<p>Las instrucciones de todos los programas que hemos escrito hasta el momento se ejecutan una después de otra en el orden en que están escritas. Sin embargo, en muchas ocasiones necesitamos que se ejecuten unas instrucciones u otras dependiendo de alguna condición. Para expresar este tipo de alternativas en nuestro programa iJava cuenta con la estructura <strong>if-else</strong>.</p>
	<p>La estructura <strong>if-else</strong> nos permite especificar dos conjuntos de instrucciones asociados a una condición. Si la condición se cumple se ejecute el primer conjunto de instrucciones y si no se cumple se ejecutará el segundo. Las condiciones las expresaremos utilizando operadores de comparación y de igualdad que veremos más adelante. El siguiente programa muestra cómo usar la estructura <strong>if-else</strong> para mostrar un texto u otro dependiendo de la edad del usuario.</p>
<pre><code>void main() {
	int edad = readInteger("Dime cuántos años tienes");
	if (edad < 18) {
		println("Eres menor de edad");
	} else {
		println("Eres mayor de edad");
	}
}</code></pre>
<p>En el programa anterior se puede ver cómo se hace una comparación entre dos números para ver cuál es mayor. En este caso, si el resultado de la comparación es cierto, es decir, si el valor de la variable edad es menor que 18, se ejecutará el bloque de código en el que hay una instrucción que muestra el mensaje "Eres menor de edad". En caso contrario se muestra la otra frase.</p>	
<p>Además de comparar si un número es menor que otro usando <strong><</strong>, también podemos comparar si un número es mayor que otro con el símbolo <strong>></strong>, si es mayor o igual con <strong>>=</strong> y si es menor o igual con <strong><=</strong>. Por otro lado es posible comprobar si dos valores, sean números o no, son iguales con <strong>==</strong> o si son distintos con <strong>!=</strong>.</p>
<!--<p>Del mismo modo que en las expresiones aritméticas el ordenador realiza las operaciones de comparación de derecha a izquierda pero respetando la prioridad y, en este caso, las operaciones prioritarias son las de desigualdad.</p>-->

<p>El siguiente programa utiliza la estructura <strong>if-else</strong> para decidir si el número introducido por el usuario tiene algún premio suponiendo que se obtiene premio si se acierta el número generado aleatoriamente por el ordenador.</p>

<pre><code>void main() {
	int secreto = round(random(10));
	int numero = readInteger("Dime cuál crees que es el número secreto entre 0 y 10");
	if (numero == secreto) {
		println("Acertaste");
	} else {
		println("Lo siento, era el " + secreto);
	}
}</code></pre>
<p>Además de comparar dos valores también podemos crear expresiones lógicas combinando los resultados de comparaciones mediante las operaciones Y, O y NO lógicas. Los símbolos correspondientes a esos operadores son <strong>&&</strong>, <strong>||</strong> y <strong>!</strong> respectivamente. El uso de paréntesis nos puede servir para escribir las expresiones de una forma que no sea ambigua.</p>
<p>El siguiente programa utiliza una expresión lógica como condición de la estructura <strong>if-else</strong> para decidir si el número introducido por el usuario tiene algún premio suponiendo que se obtiene premio si se acierta el número generado aleatoriamente por el ordenador o si el número elegido es una unidad menor que el correcto.</p>

<pre><code>void main() {
	int secreto = round(random(10));
	int numero = readInteger("Dime cuál crees que es el número secreto entre 0 y 10");
	if (numero == secreto - 1 || numero == secreto) {
		println("Ganaste");
	} else {
		println("Lo siento, era el " + secreto);
	}
}</code></pre>
<p>En los ejemplos que hemos visto hasta el momento se ha utilizado la estructura <strong>if-else</strong> completa, es decir, incluyendo el código a ejecutar cuando la condición es cierta y el código a ejecutar cuando no lo es. Sin embargo, es posible no indicar el código a ejecutar cuando la condición no es cierta. En este caso, podemos hablar de estructura <strong>if</strong> simple. Por ejemplo, el siguiente programa usa una estructura <strong>if</strong> simple para añadir una aclaración en un mensaje.</p>
<pre><code>void main() {
	int secreto = round(random(10));
	println("El número secreto es: " + secreto);
	// Si el resto de dividir el número por dos es cero es que es par
	if (secreto % 2 == 0) {
		println("Y se trata de un número par");
	}
}</code></pre>


<p>El resultado de una expresión en la que usamos operadores de comparación siempre será verdadero o falso. En iJava, como en otros lenguajes de programación, es posible utilizar variables que almacenen esos dos valores. Estas variables se denominan variables booleanas y para crearlas usaremos el tipo de dato llamado <strong>boolean</strong>. Además, para representar los conceptos verdadero y falso se utilizan dos palabras <strong>true</strong> y <strong>false</strong> respectivamente. 
</p>
<p>En siguiente programa genera diez números aleatorios entre 0 y 10 y cuenta el número de veces que aparece un número par. Al mismo tiempo determina si alguno de los números generados coincide con el 5.</p>
<pre><code>void main() {
	int pares = 0;
	boolean encontrado = false;
	for ( int i = 1 ; i <= 10 ; i = i + 1 ) {
		int numero = round(random(10));
		if (numero % 2 == 0) {
			pares = pares + 1;
		}
		if (numero == 5) {
			encontrado = true;
		}
	}
	println("Números pares generados: " + pares);
	if (encontrado == true) {
		println("Al menos una vez ha salido 5");
	}
}</code></pre>
<p>Fíjate como se han creado las variables <strong>pares</strong> y <strong>encontrado</strong> antes del bucle <strong>for</strong> dándoles un valor inicial de 0 y <strong>false</strong> respectivamente. Estas variables cambian de valor cuando en alguna iteración el número aleatorio generado y almacenado en la variable <strong>número</strong> es par o 5 respectivamente. Finalmente, al acabar el bucle <strong>for</strong> se usa la información almacenada en las variables para mostrar el resultado del programa.</p>
<p>Como ya hemos visto en la sección de animaciones, iJava dispone de algunas variables predefinidas que nos permiten conocer la posición del ratón. También hay otra variable, de tipo booleano, que se llama <strong>mousePressed</strong> y su valor indica si el botón del ratón está pulsado o no. El siguiente programa muestra su uso</p>
<pre><code>void dibuja() {
	// Si el botón del ratón está pulsado fondo blanco
	if (mousePressed == true) {
		background(255,255,255);
	} else {
		// Si no, fondo negro
		background(0,0,0);
	}
}

void main() {
	animate(dibuja);
}</code></pre>

<h2>Múltiples alternativas</h2>

<p>
Con las estructuras <strong>if</strong> e <strong>if-else</strong> podemos hacer que un programa sea capaz de ejecutar un trozo de código u otro según se cumpla o no una condición. Es decir, se elige de entre dos alternativas posibles pues una condición sólo puede ser cierta o falsa. Pero además, en iJava también tenemos la estructura <strong>switch</strong> que nos permite expresar dos o más alternativas haciendo que el ordenador elija una de entre todas ellas. En el siguiente ejemplo se muestra el uso de <strong>switch</strong> para decidir qué operación matemática realizar en función del caracter indicado por el usuario.</p>
<pre><code>void main() {
	int numero1 = readInteger("Escribe el primer número");
	char operacion = readChar("Escribe +, -, / o * para elegir la operación");
	int numero2 = readInteger("Escribe el segundo número");
	int resultado = 0;
	switch (operacion) {
		case '+':
			resultado = numero1 + numero2;
			break;
		case '-':
			resultado = numero1 - numero2;
			break;
		case '/':
			resultado = numero1 / numero2;
			break;
		case '*':
			resultado = numero1 * numero2;
			break;
		default:
			println("No se que hacer con " + operacion);
	}
	println("El resultado es " + resultado);
}</code></pre>
<p>La estructura <strong>switch</strong> comienza con dicha palabra y un valor, variable o expresión encerrado entre paréntesis seguido de un bloque de código encerrado entre llaves. La expresión encerrada entre paréntesis es la que se utiliza para decidir y no puede ser ser de tipo <strong>booleano</strong>. Debe ser un número entero (tipo <strong>int</strong>), un carácter (tipo <strong>char</strong>) o un texto (tipo <strong>String</strong>). En este caso es una variable de tipo char que contiene el carácter que representa la operación a realizar y que previamente habrá indicado el usuario.
</p>
<p>El código que va dentro del bloque del <strong>switch</strong> pondremos una etiqueta para cada alternativa que queramos considerar, es decir, para cada posible valor para el que queramos realizar ciertas instrucciones. Las etiquetas se indican con la palabra <strong>case</strong> seguida del valor concreto que debe ser un literal, es decir, un número, un carácter o un texto directamente escrito en el programa. La etiqueta termina con el símbolo de los dos puntos. Cuando <strong>switch</strong> se ejecuta, primero compara el valor entre paréntesis con cada etiqueta y, si el valor obtenido coincide con el de alguna etiqueta, el flujo de programa salta a la instrucción en la que está puesta dicha etiqueta. Es decir, se empiezan a ejecutar las instrucciones encabezadas por dicha etiqueta. Si no hay ninguna que coincida, la estructura <strong>switch</strong> termina.</p>
<p>
Se puede utilizar la etiqueta especial <strong>default:</strong> sin la palabra <strong>case</strong> delante, que marcará la instrucción dentro del <strong>switch</strong> a la que saltará el flujo de programa cuando ninguna de las otras etiquetas coincida con el valor que haya entre los paréntesis del <strong>switch</strong>.
</p>
<p>
También se puede utilizar la instrucción <strong>break;</strong> para hacer que el flujo de programa salte inmediatamente a la siguiente instrucción escrita tras la estructura <strong>switch</strong>. Esto resulta muy útil para evitar que, tras ejecutar la alternativa seleccionada, el flujo de programa continúe ejecutando las siguientes.
</p>


<h2>Repetir código mientras se cumpla una condición</h2>
<p>En iJava, como en otros lenguajes, podemos hacer bucles cuyo número de repeticiones no sea fijo sino que dependa del cumplimiento de una condición. Lo normal es que dicha condición se cumpla inicialmente pero como consecuencia de la ejecución del código que se repite en cada iteración deje de cumplirse en algún momento y, por lo tanto, el bucle termine.</p>
<p>Por ejemplo, si quisiéramos que el ordenador generase un número aleatorio entre 0 y 10 y que, además, fuese par podríamos hacer que generara un número tras otro hasta que se cumpliera que el número es par. O dicho de otro modo, podríamos hacer que mientras que el número generado no fuera par se estuvieran generando nuevos números. Para hacer esto en iJava podemos usar la estructura <strong>do-while</strong> del siguiente modo.</p>
<pre><code>void main() {
	int generados = 0;
	boolean encontrado = false;
	do {
		int numero = round(random(10));
		generados = generados + 1;
		if (numero % 2 == 0) {
			encontrado = true;
		}
	} while (encontrado == false);
	println("Números generados: " + generados);
	if (encontrado == true) {
		println("Al menos uno fue par");
	}
}</code></pre>
<p>La estructura <strong>do-while</strong> comienza con la palabra <strong>do</strong>, a continuación escribimos el bloque de código que queremos que se repita encerrado entre dos llaves y terminamos con la palabra <strong>while</strong> seguida de la condición que se debe cumplir para volver a repetir el bucle. La condición debe estar encerrada entre paréntesis y seguida de un punto y coma.</p>
<p>Además de la estructura <strong>do-while</strong> también existe la estructura <strong>while</strong>. En este caso para escribir el bucle se pone primero la palabra <strong>while</strong>. A continuación, y entre paréntesis, se escribe la condición. Finalmente, encerrado entre llaves se indica el bloque de código que queremos que se repita mientras se cumpla la condición.</p><p>La diferencia principal entre <strong>do-while</strong> y <strong>while</strong> es que al usar la primera estructura tenemos un bucle que, como mínimo, se ejecuta una vez. Sin embargo, al usar la estructura <strong>while</strong> como la condición se comprueba primero, si esta es falsa desde el principio, puede ser que el bucle no se ejecute ninguna vez.</p>
<pre><code>void main() {
	int numero = readInteger("Escribe el número de veces que quieres mostrar el mensaje");
	while (numero > 0) {
		println("Hola mundo!");
		numero = numero - 1;
	}
	println("Fin");
}</code></pre>
<p>En el ejemplo anterior se muestran tantos mensajes "Hola mundo!" como diga el usuario, pero si el usuario dice 0 no hay que mostrar ningún mensaje. En este caso resulta necesario usar la estructura while pues, según la respuesta del usuario, puede que no sea necesario hacer ninguna vez el bloque de código asociado al bucle.</p>


<p>
				Sigue aprendiendo en <a href="lenguaje-05.php">El lenguaje de programación iJava (5)</a>
</p>
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
