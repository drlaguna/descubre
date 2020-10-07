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
			<h1>El lenguaje de programaci�n iJava (5)</h1>
		</div>
	</div>
	<div class="col-md-12">
<h2 id="arrays">Arrays</h2>
<p>Como ya hemos visto, una variable sirve para dar un nombre a un valor y nos permite modificar el valor asociado a dicho nombre durante la ejecuci�n del programa. Un array nos permite asociar un nombre a una lista de valores del mismo tipo. Cada valor se almacenar� en una posici�n de la lista concreta as� que para poder referirnos a los distintos valores se utiliza un �ndice que representar� la posici�n de la lista que ocupa cada valor y que empezar� en 0 y acabar� en <strong>n-1</strong> siendo <strong>n</strong> el tama�o de la lista.</p>
<p>Aunque un array es capaz de almacenar muchos valores distintos se considera una �nica variable que tenemos que declarar para poder utilizarla. Para declarar un array pondremos el tipo que queremos que tengan cada uno de los distintos valores que podr� almacenar el array seguido de una pareja de corchetes y acabando con el nombre de la variable. En el siguiente ejemplo se declara una variable de tipo array de enteros y con nombre <strong>tabla</strong></p>
<pre><code>void main() {
	int[] tabla; // Declaraci�n del array
}</code></pre>		
<p>Una vez que tenemos creada la variable <strong>tabla</strong> lo primero que hay que hacer para poder utilizarla es crear el espacio en memoria donde se guardar�n los distintos valores. Para eso le asignaremos al array el resultado de utilizaremos la palabra <strong>new</strong> seguida del tipo que tendr�n los valores del array indicando entre corchetes el n�mero de valores que queremos poder manejar en dicho array. El n�mero de valores, es decir, el tama�o que queremos que tenga el array debe ser un n�mero entero</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[10]; // Creaci�n del array
}</code></pre>		
<p>En el ejemplo anterior, tras declarar la variable <strong>tabla</strong> para que sea un array de enteros hemos creado espacio suficiente para guardar 10 enteros y ese espacio se lo hemos asignado al array.</p>
<p>
Para utilizar cada uno de los valores que contiene un array utilizaremos un �ndice. En concreto, el nombre de la variable que tiene el array seguido del �ndice entre corchetes representar� el valor que ocupe la posici�n expresada por el �ndice. Adem�s, es muy importante tener en cuenta que la primera posici�n ser� la 0 por lo que si el array se cre� para ser capaz de guardar <strong>N</strong> valores, la �ltima posici�n v�lida ser� la <strong>N-1</strong>. Por ejemplo, si <b>tabla</b> es un array de 3 enteros, para guardar el valor 100 en la primera posici�n y mostrar el valor que ocupe la tercera posici�n (�ltima posici�n de este array) haremos lo siguiente:
</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[3];
	tabla[0] = 100;  // La primera posici�n es la 0
	println(tabla[2]); 
}</code></pre>		
<p>F�jate bien c�mo para acceder a la �ltima posici�n del array <strong>tabla</strong> usamos el �ndice 2. Como tabla tiene tres elementos y el primero ocupa la posici�n 0, el segundo ocupar� la 1 y el tercero la 2.</p>
<!--<p>En la declaraci�n de un array, los corchetes pueden estar a la izquierda o a la derecha del identificador. Sin embargo es muy recomendable ponerlos a la izquierda, cerca del tipo que tendr�n los valores que guarde el array. Adem�s, s-->
<p>Se puede realizar la declaraci�n y creaci�n del array en la misma l�nea igual que cuando cre�bamos variables de tipo entero o real y directamente les d�bamos un valor. Por ejemplo, para declarar y crear un array de enteros llamado <b>tabla</b> con capacidad para 3 enteros podemos hacerlo as�:
</p>
<pre><code>void main() {
	int[] tabla = new int[3];
}</code></pre>		
<p>
<!--Cuando un array se declara pero no se crea en la misma l�nea es recomendable darle un valor inicial que represente el hecho de que no est� creado. El valor m�s adecuado est� representado por una constante predefinida llamada <b>null</b>. Por lo tanto, cada vez que declaremos un array y no lo creemos inmediatamente, deber�amos asignarle <b>null</b>, como hacemos en el siguiente ejemplo:
</p>
<pre><code>void main() {
	int[] tabla = null;
	tabla = new int[3];
}</code></pre>		-->
<p>Tambi�n es posible declarar, crear y rellenar un array en una �nica l�nea. Cuando sabemos todos los valores que queremos que tenga un array podemos escribirlos separados por comas y encerrados entre dos llaves para crear el array completo con dichos valores y asignarlo a la variable que estemos declarando en la misma l�nea.</p>
<pre><code>void main() {
	// Declaro, creo y relleno al mismo tiempo el array tabla
	int[] tabla = {1,2,3};
	// Muestro el valor que ocupa la segunda posici�n
	println(tabla[1]);
}</code></pre>		

<p>Una vez que un array est� creado no se puede modificar su tama�o. Pero s� podemos conocerlo utilizando una funci�n llamada <a href="editor-codigo.php?id=088ddc5d14e7cbe7363c7e64ac8131ea">sizeOf</a>. Por ejemplo, el siguiente programa escribe 5 por la pantalla.</p>
<pre><code>void main() {
	int[] tabla;
	tabla = new int[5];
	println(sizeOf(tabla)); 
}</code></pre>		


<h2>Arrays y Funciones propias</h2>
<p>Ya hemos visto que es posible escribir nuestras propias funciones y que, adem�s, podemos ponerles par�metros y hacer que devuelvan un valor. Igual que hemos usado enteros, reales, caracteres, textos o valores de verdad como par�metros o como valor a devolver por la funci�n, tambi�n podemos utilizar arrays de cualquiera de estos tipos como par�metro y como valor devuelto por una funci�n.</p>
<p>Para poner como par�metro un array lo �nico que hay que hacer es declararlo correctamente al especificar el par�metro. Por ejemplo, si queremos hacer una funci�n que muestre por la pantalla todos los enteros incluidos en un array escribiremos lo siguiente:</p>
<pre><code>// Funci�n que muestra el contenido de un array de enteros
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
<p>A la hora de utilizar nuestra funci�n <strong>muestra</strong> primero necesitamos contar con un array. En el ejemplo anterior, la funci�n principal del programa, la funci�n <strong>main</strong>, primero declara el array <strong>tabla</strong> y, tras crearlo, lo rellena con los valores 30, 20 y 10. Despu�s usa la funci�n muestra pasando como par�metro el array tabla completo para lo que s�lo hay que escribir el nombre de la variable que lo contiene.</p>
<p>F�jate bien en la funci�n <strong>muestra</strong>. En ella usamos la funci�n <strong>sizeOf</strong> con el array para saber cu�ntas repeticiones hacer y como dicho n�mero no cambia usamos una estructura <strong>for</strong> para hacer el bucle.</p>
<p>Para escribir una funci�n propia que devuelva un array como resultado de sus acciones simplemente hay que escribir el tipo de los valores que contendr� el array seguido de una pareja de corchetes y, a continuaci�n, el nombre de la funci�n seguido de la lista de par�metros y el bloque de c�digo.</p>
<pre><code>// Funci�n que crea y rellena un array de enteros
int[] crea(int cantidad) {
	int[] tabla = new int[cantidad];
	int indice = 0;
	for ( int n = 1 ; n <= cantidad ; n = n + 1 ) {
		tabla[indice] = indice*10;
		indice = indice + 1;
	}
	return tabla;
}

// Funci�n que muestra el contenido de un array de enteros
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
<p>En este �ltimo programa hemos a�adido al anterior la funci�n que crea un array de enteros del tama�o indicado a trav�s de su par�metro <strong>cantidad</strong> y lo rellena con n�meros. F�jate como se utiliza una variable local llamada <strong>tabla</strong> para almacenar el array creado y rellenado y c�mo la instrucci�n <strong>return</strong> termina la ejecuci�n de la funci�n devolviendo el array creado. En el programa principal (funci�n <strong>main</strong>) se usa la funci�n y su resultado se almacena en otra variable local a la propia funci�n <strong>main</strong>, la variable se llama <strong>mitabla</strong> y, necesariamente tiene el mismo tipo que el devuelto por la funci�n, es decir, es un array de enteros.</p>


<h3>Arrays multidimensionales</h3>
<p>Los arrays nos permiten manejar m�s de un valor usando un s�lo nombre de variable y un �ndice. El �ndice indica la posici�n a la que nos referimos de entre todas las que almacena la variable. Como hemos dicho anteriormente, podemos considerar que un array es como una lista de valores del mismo tipo. Es decir, los arrays que hemos visto nos permiten representar un conjunto de valores estructurado en una dimensi�n.</p>
<p>Pero en algunas ocasiones necesitamos representar conjuntos de valores estructurados en m�s de una dimensi�n, por ejemplo, si quisi�ramos representar un tablero del juego hundir la flota en el que cada casilla se identifica mediante dos coordenadas:horizontales y verticales.</p>
<p>En iJava podemos declarar, crear y utilizar arrays de dos o m�s dimensiones sin m�s que a�adir m�s parejas de corchetes en la declaraci�n, creaci�n y uso de los mismos.</p> 
<pre><code>// Funci�n que crea y rellena un array bidimensional de enteros
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

// Funci�n que muestra el contenido de un array bidimensional de enteros
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
<p>Este programa es una modificaci�n del anterior para utilizar arrays de dos dimensiones. F�jate que es necesario realizar bucles anidados tanto para rellenarlos como para mostrar su contenido. Tambi�n es importante destacar que para conocer el tama�o de las diferentes dimensiones en un array multidimensional la funci�n <strong>sizeOf</strong> permite indicar, ademas del array, la dimensi�n sobre la que queremos conocer el tama�o. La primera dimensi�n ser� la 1, la segunda la 2 y as� sucesivamente.</p>
<p>Finalmente, es interesante destacar el uso de la funci�n <strong>print</strong> para mostrar uno tras otro en el mismo rengl�n los valores almacenados en la misma fila y, despu�s, cada vez que se cambia de fila, el uso de <strong>println</strong> sin ning�n valor para conseguir el cambio de l�nea para mostrar los valores de la siguiente fila en el siguiente rengl�n.</p>

<h2>Crear y Rellenar simult�neamente arrays multidimensionales</h2>
<p>Igual que asignamos valores a un array en su declaraci�n poni�ndolos en una lista separada por comas y encerrada entre llaves, tambi�n podemos hacerlo cuando el array que estamos declarando tiene varias dimensiones. Lo �nico que hay que hacer es usar tantas llaves anidadas como dimensiones tengamos.</p>
<pre><code>// Funci�n que muestra el contenido de un array bidimensional de enteros
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
<p>En el ejemplo anterior, el array <strong>mitabla</strong> se declara para tener dos dimensiones de enteros. Para crearlo y rellenarlo de valores al mismo tiempo ponemos entre dos llaves la lista de arrays de una dimensi�n que forman cada fila del array de dos dimensiones. Eso s�, todos ellos deben tener el mismo n�mero de valores para que el array de dos dimensiones.</p>

<!--<p>A diferencia de lo que ocurre en Java, los arrays multidimensionales en iJava no son arrays de arrays y por tanto, el tama�o de cada dimensi�n es fijo. Es decir, en un array de dos dimensiones de enteros, si entendemos que la primera dimensi�n representa las filas y las segunda las columnas, todas las filas tienen el mismo n�mero de columnas. Adem�s, si un array tiene N dimensiones para acceder a sus elementos tendremos que dar un valor para cada �ndice, no es posible utilizar menos �ndices entendiendo que estamos accediendo a un subarray.</p>-->

		
<p>
				Sigue aprendiendo en <a href="lenguaje-06.php">El lenguaje de programaci�n iJava (6)</a>
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
