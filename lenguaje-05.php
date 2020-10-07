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
			<h1>El lenguaje de programación iJava (5)</h1>
		</div>
	</div>
	<div class="col-md-12">
<h2 id="arrays">Arrays</h2>
<p>Como ya hemos visto, una variable sirve para dar un nombre a un valor y nos permite modificar el valor asociado a dicho nombre durante la ejecución del programa. Un array nos permite asociar un nombre a una lista de valores del mismo tipo. Cada valor se almacenará en una posición de la lista concreta así que para poder referirnos a los distintos valores se utiliza un índice que representará la posición de la lista que ocupa cada valor y que empezará en 0 y acabará en <strong>n-1</strong> siendo <strong>n</strong> el tamaño de la lista.</p>
<p>Aunque un array es capaz de almacenar muchos valores distintos se considera una única variable que tenemos que declarar para poder utilizarla. Para declarar un array pondremos el tipo que queremos que tengan cada uno de los distintos valores que podrá almacenar el array seguido de una pareja de corchetes y acabando con el nombre de la variable. En el siguiente ejemplo se declara una variable de tipo array de enteros y con nombre <strong>tabla</strong></p>
<pre><code>void main() {
	int[] tabla; // Declaración del array
}</code></pre>		
<p>Una vez que tenemos creada la variable <strong>tabla</strong> lo primero que hay que hacer para poder utilizarla es crear el espacio en memoria donde se guardarán los distintos valores. Para eso le asignaremos al array el resultado de utilizaremos la palabra <strong>new</strong> seguida del tipo que tendrán los valores del array indicando entre corchetes el número de valores que queremos poder manejar en dicho array. El número de valores, es decir, el tamaño que queremos que tenga el array debe ser un número entero</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[10]; // Creación del array
}</code></pre>		
<p>En el ejemplo anterior, tras declarar la variable <strong>tabla</strong> para que sea un array de enteros hemos creado espacio suficiente para guardar 10 enteros y ese espacio se lo hemos asignado al array.</p>
<p>
Para utilizar cada uno de los valores que contiene un array utilizaremos un índice. En concreto, el nombre de la variable que tiene el array seguido del índice entre corchetes representará el valor que ocupe la posición expresada por el índice. Además, es muy importante tener en cuenta que la primera posición será la 0 por lo que si el array se creó para ser capaz de guardar <strong>N</strong> valores, la última posición válida será la <strong>N-1</strong>. Por ejemplo, si <b>tabla</b> es un array de 3 enteros, para guardar el valor 100 en la primera posición y mostrar el valor que ocupe la tercera posición (última posición de este array) haremos lo siguiente:
</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[3];
	tabla[0] = 100;  // La primera posición es la 0
	println(tabla[2]); 
}</code></pre>		
<p>Fíjate bien cómo para acceder a la última posición del array <strong>tabla</strong> usamos el índice 2. Como tabla tiene tres elementos y el primero ocupa la posición 0, el segundo ocupará la 1 y el tercero la 2.</p>
<!--<p>En la declaración de un array, los corchetes pueden estar a la izquierda o a la derecha del identificador. Sin embargo es muy recomendable ponerlos a la izquierda, cerca del tipo que tendrán los valores que guarde el array. Además, s-->
<p>Se puede realizar la declaración y creación del array en la misma línea igual que cuando creábamos variables de tipo entero o real y directamente les dábamos un valor. Por ejemplo, para declarar y crear un array de enteros llamado <b>tabla</b> con capacidad para 3 enteros podemos hacerlo así:
</p>
<pre><code>void main() {
	int[] tabla = new int[3];
}</code></pre>		
<p>
<!--Cuando un array se declara pero no se crea en la misma línea es recomendable darle un valor inicial que represente el hecho de que no está creado. El valor más adecuado está representado por una constante predefinida llamada <b>null</b>. Por lo tanto, cada vez que declaremos un array y no lo creemos inmediatamente, deberíamos asignarle <b>null</b>, como hacemos en el siguiente ejemplo:
</p>
<pre><code>void main() {
	int[] tabla = null;
	tabla = new int[3];
}</code></pre>		-->
<p>También es posible declarar, crear y rellenar un array en una única línea. Cuando sabemos todos los valores que queremos que tenga un array podemos escribirlos separados por comas y encerrados entre dos llaves para crear el array completo con dichos valores y asignarlo a la variable que estemos declarando en la misma línea.</p>
<pre><code>void main() {
	// Declaro, creo y relleno al mismo tiempo el array tabla
	int[] tabla = {1,2,3};
	// Muestro el valor que ocupa la segunda posición
	println(tabla[1]);
}</code></pre>		

<p>Una vez que un array está creado no se puede modificar su tamaño. Pero sí podemos conocerlo utilizando una función llamada <a href="editor-codigo.php?id=088ddc5d14e7cbe7363c7e64ac8131ea">sizeOf</a>. Por ejemplo, el siguiente programa escribe 5 por la pantalla.</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[5];
	println(sizeOf(tabla)); 
}</code></pre>		


<h2>Arrays y Funciones propias</h2>
<p>Ya hemos visto que es posible escribir nuestras propias funciones y que, además, podemos ponerles parámetros y hacer que devuelvan un valor. Igual que hemos usado enteros, reales, caracteres, textos o valores de verdad como parámetros o como valor a devolver por la función, también podemos utilizar arrays de cualquiera de estos tipos como parámetro y como valor devuelto por una función.</p>
<p>Para poner como parámetro un array lo único que hay que hacer es declararlo correctamente al especificar el parámetro. Por ejemplo, si queremos hacer una función que muestre por la pantalla todos los enteros incluidos en un array escribiremos lo siguiente:</p>
<pre><code>// Función que muestra el contenido de un array de enteros
void muestra(int[] array) {
	int indice = 0;
	for ( int n = 1 ; n <= sizeOf(array) ; n = n + 1 ) {
		println(array[indice]);
		indice = indice + 1;
	}
}

void main() {
	int[] tabla;
	tabla = new int[3];
	tabla[0] = 30;
	tabla[1] = 20;
	tabla[2] = 10;
	muestra(tabla);
}</code></pre>		
<p>A la hora de utilizar nuestra función <strong>muestra</strong> primero necesitamos contar con un array. En el ejemplo anterior, la función principal del programa, la función <strong>main</strong>, primero declara el array <strong>tabla</strong> y, tras crearlo, lo rellena con los valores 30, 20 y 10. Después usa la función muestra pasando como parámetro el array tabla completo para lo que sólo hay que escribir el nombre de la variable que lo contiene.</p>
<p>Fíjate bien en la función <strong>muestra</strong>. En ella usamos la función <strong>sizeOf</strong> con el array para saber cuántas repeticiones hacer y como dicho número no cambia usamos una estructura <strong>for</strong> para hacer el bucle.</p>
<p>Para escribir una función propia que devuelva un array como resultado de sus acciones simplemente hay que escribir el tipo de los valores que contendrá el array seguido de una pareja de corchetes y, a continuación, el nombre de la función seguido de la lista de parámetros y el bloque de código.</p>
<pre><code>// Función que crea y rellena un array de enteros
int[] crea(int cantidad) {
	int[] tabla = new int[cantidad];
	int indice = 0;
	for ( int n = 1 ; n <= cantidad ; n = n + 1 ) {
		tabla[indice] = indice*10;
		indice = indice + 1;
	}
	return tabla;
}

// Función que muestra el contenido de un array de enteros
void muestra(int[] array) {
	int indice = 0;
	for ( int n = 1 ; n <= sizeOf(array) ; n = n + 1 ) {
		println(array[indice]);
		indice = indice + 1;
	}
}

void main() {
	int[] mitabla = crea(5);
	muestra(mitabla);
}</code></pre>		
<p>En este último programa hemos añadido al anterior la función que crea un array de enteros del tamaño indicado a través de su parámetro <strong>cantidad</strong> y lo rellena con números. Fíjate como se utiliza una variable local llamada <strong>tabla</strong> para almacenar el array creado y rellenado y cómo la instrucción <strong>return</strong> termina la ejecución de la función devolviendo el array creado. En el programa principal (función <strong>main</strong>) se usa la función y su resultado se almacena en otra variable local a la propia función <strong>main</strong>, la variable se llama <strong>mitabla</strong> y, necesariamente tiene el mismo tipo que el devuelto por la función, es decir, es un array de enteros.</p>


<h3>Arrays multidimensionales</h3>
<p>Los arrays nos permiten manejar más de un valor usando un sólo nombre de variable y un índice. El índice indica la posición a la que nos referimos de entre todas las que almacena la variable. Como hemos dicho anteriormente, podemos considerar que un array es como una lista de valores del mismo tipo. Es decir, los arrays que hemos visto nos permiten representar un conjunto de valores estructurado en una dimensión.</p>
<p>Pero en algunas ocasiones necesitamos representar conjuntos de valores estructurados en más de una dimensión, por ejemplo, si quisiéramos representar un tablero del juego hundir la flota en el que cada casilla se identifica mediante dos coordenadas:horizontales y verticales.</p>
<p>En iJava podemos declarar, crear y utilizar arrays de dos o más dimensiones sin más que añadir más parejas de corchetes en la declaración, creación y uso de los mismos.</p> 
<pre><code>// Función que crea y rellena un array bidimensional de enteros
int[][] crea(int filas, int columnas) {
	int[][] tabla = new int[filas][columnas];
	int fila = 0;
	for ( int n = 1 ; n <= filas ; n = n + 1 ) {
		int columna = 0;
		for ( int m = 1 ; m <= columnas ; m = m + 1 ) {
			tabla[fila][columna] = fila*columna;
			columna = columna + 1;
		}
		fila = fila + 1;
	}
	return tabla;
}

// Función que muestra el contenido de un array bidimensional de enteros
void muestra(int[][] matriz) {
	int filas = sizeOf(matriz,1);
	int columnas = sizeOf(matriz,2);
	int fila = 0;
	for ( int n = 1 ; n <= filas ; n = n + 1 ) {
		int columna = 0;
		for ( int m = 1 ; m <= columnas ; m = m + 1 ) {
			print(matriz[fila][columna]);
			print(" ");
			columna = columna + 1;
		}
		println();
		fila = fila + 1;
	}
}

void main() {
	int[][] mitabla = crea(5,10);
	muestra(mitabla);
}</code></pre>
<p>Este programa es una modificación del anterior para utilizar arrays de dos dimensiones. Fíjate que es necesario realizar bucles anidados tanto para rellenarlos como para mostrar su contenido. También es importante destacar que para conocer el tamaño de las diferentes dimensiones en un array multidimensional la función <strong>sizeOf</strong> permite indicar, ademas del array, la dimensión sobre la que queremos conocer el tamaño. La primera dimensión será la 1, la segunda la 2 y así sucesivamente.</p>
<p>Finalmente, es interesante destacar el uso de la función <strong>print</strong> para mostrar uno tras otro en el mismo renglón los valores almacenados en la misma fila y, después, cada vez que se cambia de fila, el uso de <strong>println</strong> sin ningún valor para conseguir el cambio de línea para mostrar los valores de la siguiente fila en el siguiente renglón.</p>

<h2>Crear y Rellenar simultáneamente arrays multidimensionales</h2>
<p>Igual que asignamos valores a un array en su declaración poniéndolos en una lista separada por comas y encerrada entre llaves, también podemos hacerlo cuando el array que estamos declarando tiene varias dimensiones. Lo único que hay que hacer es usar tantas llaves anidadas como dimensiones tengamos.</p>
<pre><code>// Función que muestra el contenido de un array bidimensional de enteros
void muestra(int[][] matriz) {
	int filas = sizeOf(matriz,1);
	int columnas = sizeOf(matriz,2);
	int fila = 0;
	for ( int n = 1 ; n <= filas ; n = n + 1 ) {
		int columna = 0;
		for ( int m = 1 ; m <= columnas ; m = m + 1 ) {
			print(matriz[fila][columna]);
			print(" ");
			columna = columna + 1;
		}
		println();
		fila = fila + 1;
	}
}

void main() {
	int[][] mitabla = {{1,2},{3,4},{5,6}};
	muestra(mitabla);
}</code></pre>
<p>En el ejemplo anterior, el array <strong>mitabla</strong> se declara para tener dos dimensiones de enteros. Para crearlo y rellenarlo de valores al mismo tiempo ponemos entre dos llaves la lista de arrays de una dimensión que forman cada fila del array de dos dimensiones. Eso sí, todos ellos deben tener el mismo número de valores para que el array de dos dimensiones.</p>

<!--<p>A diferencia de lo que ocurre en Java, los arrays multidimensionales en iJava no son arrays de arrays y por tanto, el tamaño de cada dimensión es fijo. Es decir, en un array de dos dimensiones de enteros, si entendemos que la primera dimensión representa las filas y las segunda las columnas, todas las filas tienen el mismo número de columnas. Además, si un array tiene N dimensiones para acceder a sus elementos tendremos que dar un valor para cada índice, no es posible utilizar menos índices entendiendo que estamos accediendo a un subarray.</p>-->

		
<p>
				Sigue aprendiendo en <a href="lenguaje-06.php">El lenguaje de programación iJava (6)</a>
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
