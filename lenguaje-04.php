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
			<h1>El lenguaje de programaci�n iJava (4)</h1>
		</div>
	</div>
	<div class="col-md-12">
	<h2>Decisiones</h2>
	<p>Las instrucciones de todos los programas que hemos escrito hasta el momento se ejecutan una despu�s de otra en el orden en que est�n escritas. Sin embargo, en muchas ocasiones necesitamos que se ejecuten unas instrucciones u otras dependiendo de alguna condici�n. Para expresar este tipo de alternativas en nuestro programa iJava cuenta con la estructura <strong>if-else</strong>.</p>
	<p>La estructura <strong>if-else</strong> nos permite especificar dos conjuntos de instrucciones asociados a una condici�n. Si la condici�n se cumple se ejecute el primer conjunto de instrucciones y si no se cumple se ejecutar� el segundo. Las condiciones las expresaremos utilizando operadores de comparaci�n y de igualdad que veremos m�s adelante. El siguiente programa muestra c�mo usar la estructura <strong>if-else</strong> para mostrar un texto u otro dependiendo de la edad del usuario.</p>
<pre><code>void main() {
	int edad = readInteger("Dime cu�ntos a�os tienes");
	if (edad < 18) {
		println("Eres menor de edad");
	} else {
		println("Eres mayor de edad");
	}
}</code></pre>
<p>En el programa anterior se puede ver c�mo se hace una comparaci�n entre dos n�meros para ver cu�l es mayor. En este caso, si el resultado de la comparaci�n es cierto, es decir, si el valor de la variable edad es menor que 18, se ejecutar� el bloque de c�digo en el que hay una instrucci�n que muestra el mensaje "Eres menor de edad". En caso contrario se muestra la otra frase.</p>	
<p>Adem�s de comparar si un n�mero es menor que otro usando <strong><</strong>, tambi�n podemos comparar si un n�mero es mayor que otro con el s�mbolo <strong>></strong>, si es mayor o igual con <strong>>=</strong> y si es menor o igual con <strong><=</strong>. Por otro lado es posible comprobar si dos valores, sean n�meros o no, son iguales con <strong>==</strong> o si son distintos con <strong>!=</strong>.</p>
<!--<p>Del mismo modo que en las expresiones aritm�ticas el ordenador realiza las operaciones de comparaci�n de derecha a izquierda pero respetando la prioridad y, en este caso, las operaciones prioritarias son las de desigualdad.</p>-->

<p>El siguiente programa utiliza la estructura <strong>if-else</strong> para decidir si el n�mero introducido por el usuario tiene alg�n premio suponiendo que se obtiene premio si se acierta el n�mero generado aleatoriamente por el ordenador.</p>

<pre><code>void main() {
	int secreto = round(random(10));
	int numero = readInteger("Dime cu�l crees que es el n�mero secreto entre 0 y 10");
	if (numero == secreto) {
		println("Acertaste");
	} else {
		println("Lo siento, era el " + secreto);
	}
}</code></pre>
<p>Adem�s de comparar dos valores tambi�n podemos crear expresiones l�gicas combinando los resultados de comparaciones mediante las operaciones Y, O y NO l�gicas. Los s�mbolos correspondientes a esos operadores son <strong>&&</strong>, <strong>||</strong> y <strong>!</strong> respectivamente. El uso de par�ntesis nos puede servir para escribir las expresiones de una forma que no sea ambigua.</p>
<p>El siguiente programa utiliza una expresi�n l�gica como condici�n de la estructura <strong>if-else</strong> para decidir si el n�mero introducido por el usuario tiene alg�n premio suponiendo que se obtiene premio si se acierta el n�mero generado aleatoriamente por el ordenador o si el n�mero elegido es una unidad menor que el correcto.</p>

<pre><code>void main() {
	int secreto = round(random(10));
	int numero = readInteger("Dime cu�l crees que es el n�mero secreto entre 0 y 10");
	if (numero == secreto - 1 || numero == secreto) {
		println("Ganaste");
	} else {
		println("Lo siento, era el " + secreto);
	}
}</code></pre>
<p>En los ejemplos que hemos visto hasta el momento se ha utilizado la estructura <strong>if-else</strong> completa, es decir, incluyendo el c�digo a ejecutar cuando la condici�n es cierta y el c�digo a ejecutar cuando no lo es. Sin embargo, es posible no indicar el c�digo a ejecutar cuando la condici�n no es cierta. En este caso, podemos hablar de estructura <strong>if</strong> simple. Por ejemplo, el siguiente programa usa una estructura <strong>if</strong> simple para a�adir una aclaraci�n en un mensaje.</p>
<pre><code>void main() {
	int secreto = round(random(10));
	println("El n�mero secreto es: " + secreto);
	// Si el resto de dividir el n�mero por dos es cero es que es par
	if (secreto % 2 == 0) {
		println("Y se trata de un n�mero par");
	}
}</code></pre>


<p>El resultado de una expresi�n en la que usamos operadores de comparaci�n siempre ser� verdadero o falso. En iJava, como en otros lenguajes de programaci�n, es posible utilizar variables que almacenen esos dos valores. Estas variables se denominan variables booleanas y para crearlas usaremos el tipo de dato llamado <strong>boolean</strong>. Adem�s, para representar los conceptos verdadero y falso se utilizan dos palabras <strong>true</strong> y <strong>false</strong> respectivamente. 
</p>
<p>En siguiente programa genera diez n�meros aleatorios entre 0 y 10 y cuenta el n�mero de veces que aparece un n�mero par. Al mismo tiempo determina si alguno de los n�meros generados coincide con el 5.</p>
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
	println("N�meros pares generados: " + pares);
	if (encontrado == true) {
		println("Al menos una vez ha salido 5");
	}
}</code></pre>
<p>F�jate como se han creado las variables <strong>pares</strong> y <strong>encontrado</strong> antes del bucle <strong>for</strong> d�ndoles un valor inicial de 0 y <strong>false</strong> respectivamente. Estas variables cambian de valor cuando en alguna iteraci�n el n�mero aleatorio generado y almacenado en la variable <strong>n�mero</strong> es par o 5 respectivamente. Finalmente, al acabar el bucle <strong>for</strong> se usa la informaci�n almacenada en las variables para mostrar el resultado del programa.</p>
<p>Como ya hemos visto en la secci�n de animaciones, iJava dispone de algunas variables predefinidas que nos permiten conocer la posici�n del rat�n. Tambi�n hay otra variable, de tipo booleano, que se llama <strong>mousePressed</strong> y su valor indica si el bot�n del rat�n est� pulsado o no. El siguiente programa muestra su uso</p>
<pre><code>void dibuja() {
	// Si el bot�n del rat�n est� pulsado fondo blanco
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

<h2>M�ltiples alternativas</h2>

<p>
Con las estructuras <strong>if</strong> e <strong>if-else</strong> podemos hacer que un programa sea capaz de ejecutar un trozo de c�digo u otro seg�n se cumpla o no una condici�n. Es decir, se elige de entre dos alternativas posibles pues una condici�n s�lo puede ser cierta o falsa. Pero adem�s, en iJava tambi�n tenemos la estructura <strong>switch</strong> que nos permite expresar dos o m�s alternativas haciendo que el ordenador elija una de entre todas ellas. En el siguiente ejemplo se muestra el uso de <strong>switch</strong> para decidir qu� operaci�n matem�tica realizar en funci�n del caracter indicado por el usuario.</p>
<pre><code>void main() {
	int numero1 = readInteger("Escribe el primer n�mero");
	char operacion = readChar("Escribe +, -, / o * para elegir la operaci�n");
	int numero2 = readInteger("Escribe el segundo n�mero");
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
<p>La estructura <strong>switch</strong> comienza con dicha palabra y un valor, variable o expresi�n encerrado entre par�ntesis seguido de un bloque de c�digo encerrado entre llaves. La expresi�n encerrada entre par�ntesis es la que se utiliza para decidir y no puede ser ser de tipo <strong>booleano</strong>. Debe ser un n�mero entero (tipo <strong>int</strong>), un car�cter (tipo <strong>char</strong>) o un texto (tipo <strong>String</strong>). En este caso es una variable de tipo char que contiene el car�cter que representa la operaci�n a realizar y que previamente habr� indicado el usuario.
</p>
<p>El c�digo que va dentro del bloque del <strong>switch</strong> pondremos una etiqueta para cada alternativa que queramos considerar, es decir, para cada posible valor para el que queramos realizar ciertas instrucciones. Las etiquetas se indican con la palabra <strong>case</strong> seguida del valor concreto que debe ser un literal, es decir, un n�mero, un car�cter o un texto directamente escrito en el programa. La etiqueta termina con el s�mbolo de los dos puntos. Cuando <strong>switch</strong> se ejecuta, primero compara el valor entre par�ntesis con cada etiqueta y, si el valor obtenido coincide con el de alguna etiqueta, el flujo de programa salta a la instrucci�n en la que est� puesta dicha etiqueta. Es decir, se empiezan a ejecutar las instrucciones encabezadas por dicha etiqueta. Si no hay ninguna que coincida, la estructura <strong>switch</strong> termina.</p>
<p>
Se puede utilizar la etiqueta especial <strong>default:</strong> sin la palabra <strong>case</strong> delante, que marcar� la instrucci�n dentro del <strong>switch</strong> a la que saltar� el flujo de programa cuando ninguna de las otras etiquetas coincida con el valor que haya entre los par�ntesis del <strong>switch</strong>.
</p>
<p>
Tambi�n se puede utilizar la instrucci�n <strong>break;</strong> para hacer que el flujo de programa salte inmediatamente a la siguiente instrucci�n escrita tras la estructura <strong>switch</strong>. Esto resulta muy �til para evitar que, tras ejecutar la alternativa seleccionada, el flujo de programa contin�e ejecutando las siguientes.
</p>


<h2>Repetir c�digo mientras se cumpla una condici�n</h2>
<p>En iJava, como en otros lenguajes, podemos hacer bucles cuyo n�mero de repeticiones no sea fijo sino que dependa del cumplimiento de una condici�n. Lo normal es que dicha condici�n se cumpla inicialmente pero como consecuencia de la ejecuci�n del c�digo que se repite en cada iteraci�n deje de cumplirse en alg�n momento y, por lo tanto, el bucle termine.</p>
<p>Por ejemplo, si quisi�ramos que el ordenador generase un n�mero aleatorio entre 0 y 10 y que, adem�s, fuese par podr�amos hacer que generara un n�mero tras otro hasta que se cumpliera que el n�mero es par. O dicho de otro modo, podr�amos hacer que mientras que el n�mero generado no fuera par se estuvieran generando nuevos n�meros. Para hacer esto en iJava podemos usar la estructura <strong>do-while</strong> del siguiente modo.</p>
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
	println("N�meros generados: " + generados);
	if (encontrado == true) {
		println("Al menos uno fue par");
	}
}</code></pre>
<p>La estructura <strong>do-while</strong> comienza con la palabra <strong>do</strong>, a continuaci�n escribimos el bloque de c�digo que queremos que se repita encerrado entre dos llaves y terminamos con la palabra <strong>while</strong> seguida de la condici�n que se debe cumplir para volver a repetir el bucle. La condici�n debe estar encerrada entre par�ntesis y seguida de un punto y coma.</p>
<p>Adem�s de la estructura <strong>do-while</strong> tambi�n existe la estructura <strong>while</strong>. En este caso para escribir el bucle se pone primero la palabra <strong>while</strong>. A continuaci�n, y entre par�ntesis, se escribe la condici�n. Finalmente, encerrado entre llaves se indica el bloque de c�digo que queremos que se repita mientras se cumpla la condici�n.</p><p>La diferencia principal entre <strong>do-while</strong> y <strong>while</strong> es que al usar la primera estructura tenemos un bucle que, como m�nimo, se ejecuta una vez. Sin embargo, al usar la estructura <strong>while</strong> como la condici�n se comprueba primero, si esta es falsa desde el principio, puede ser que el bucle no se ejecute ninguna vez.</p>
<pre><code>void main() {
	int numero = readInteger("Escribe el n�mero de veces que quieres mostrar el mensaje");
	while (numero > 0) {
		println("Hola mundo!");
		numero = numero - 1;
	}
	println("Fin");
}</code></pre>
<p>En el ejemplo anterior se muestran tantos mensajes "Hola mundo!" como diga el usuario, pero si el usuario dice 0 no hay que mostrar ning�n mensaje. En este caso resulta necesario usar la estructura while pues, seg�n la respuesta del usuario, puede que no sea necesario hacer ninguna vez el bloque de c�digo asociado al bucle.</p>


<p>
				Sigue aprendiendo en <a href="lenguaje-05.php">El lenguaje de programaci�n iJava (5)</a>
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
