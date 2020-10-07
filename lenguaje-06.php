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
			<h1>El lenguaje de programación iJava (6)</h1>
		</div>
	</div>
	<div class="col-md-12">
<h2 id="interactivos">Control de teclado y ratón</h2>
<p>Ya hemos visto como hacer una animación usando la función <strong>animate</strong> pero, por ejemplo, para hacer un videojuego, además de mover dibujos por la pantalla tenemos que ser capaces de controlar su comportamiento usando el ratón o el teclado. Para conseguirlo vamos a utilizar algunas variables globales que existen en iJava y que tendremos disponibles en todos nuestros programas sin necesidad de declararlas:
</p>
<ul>
<li><strong>mouseX</strong>: Guarda en todo momento la coordenada horizontal del ratón.</li>
<li><strong>mouseY</strong>: Guarda en todo momento la coordenada vertical del ratón.</li>
<li><strong>mousePressed</strong>: Contiene un valor true si algún botón del ratón está pulsado.</li>
<li><strong>mouseButton</strong>: Indica qué botón (<strong>LEFTBUTTON</strong>, <strong>MIDDLEBUTTON</strong> o <strong>RIGHTBUTTON</strong>) es el que está pulsado.</li>
<li><strong>keyPressed</strong>: Contiene un valor true si alguna tecla está pulsada.</li>
<li><strong>key</strong>: Contiene una cadena indicando el nombre de la tecla pulsada.</li>
</ul>

<p>
En el siguiente ejemplo se utilizan las tres primeras variables para conseguir que se dibuje un círculo en la posición que esté el ratón pero sólo si hay algún botón del ratón pulsado:
</p>

<pre><code>void dibuja() {
  background(0,0,0);
  if (mousePressed) {
    ellipse(mouseX, mouseY, 50,50);
  }  
}

void main() {
  animate(dibuja);
}</code></pre>

<p>
Como puedes ver el uso de estas variables es muy sencillo. Si además queremos que sólo funcione cuando se pulsa un botón en particular usaremos la variable mouseButton para comprobar de qué botón se trata comparándo su valor con el de una de las tres constantes que incluye iJava para referirse a uno de los tres posibles botones del ratón: <strong>LEFTBUTTON</strong>, <strong>MIDDLEBUTTON</strong> o <strong>RIGHTBUTTON</strong>.
</p>

<pre><code>void dibuja() {
  background(0,0,0);
  if (mousePressed) {
    if (mouseButton == RIGHTBUTTON) {
    	ellipse(mouseX, mouseY, 50,50);
    }
  }  
}

void main() {
  animate(dibuja);
}</code></pre>

<p>
Fíjate como sólo hemos tenido que añadir una nueva comprobación y ahora, al ejecutar el programa, sólo se dibuja el círculo cuando pulsamos con el botón derecho. Prueba a cambiar el código para que sólo funcione, por ejemplo, con el botón izquierdo.
</p>

<p>
Para aprender a utilizar las variables <strong>keyPressed</strong> y <strong>key</strong> vamos a hacer un pequeño programa que escriba el nombre de la tecla pulsada cuando haya alguna en esa situación. El programa es el siguiente:
</p>

<pre><code>void dibuja() {
  background(0,0,0);
  if (keyPressed) {
    text(key, 100,100);
  }  
}

void main() {
  animate(dibuja);
}</code></pre>

<p>
Cuando pruebes el programa pulsa las teclas de las letras y los números y verás que el nombre que guarda la variable  <strong>key</strong> se corresponde justamente con la letra o el número en cuestión independientemente de que pulsemos mayúsculas o no. De hecho, si pulsamos mayúsculas el nombre que se muestra es <em>shift</em>. Esto es así con las teclas de control y los nombres que se pueden obtener son los siguientes:
</p>

<ul>
<li><em>shift</em>: para la tecla mayúsculas.</li>
<li><em>control</em>: para la tecla ctrl.</li>
<li>valt</em>: para la tecla alt.</li>
<li>vbackspace</em>: para la tecla de borrar hacia atrás.</li>
<li><em>tab</em>: para la tecla del tabulador.</li>
<li><em>enter</em>: para la tecla de retorno de carro.</li>
<li><em>return</em>: para la tecla de retorno de carro.</li>
<li><em>esc</em>: para la tecla de escape.</li>
<li><em>delete</em>: para la tecla de suprimir (borrar lo que hay a la derecha del cursor).</li>
<li><em>capslk</em>: para la tecla de bloqueo mayúsculas.</li>
<li><em>pgup</em>: para la tecla de página anterior.</li>
<li><em>pgdn</em>: para la tecla de página siguiente.</li>
<li><em>end</em>: para la tecla de fin.</li>
<li><em>home</em>: para la tecla de inicio.</li>
<li><em>left</em>: para la flecha izquierda.</li>
<li>up</em>: para la flecha arriba.</li>
<li><em>right</em>: para la flecha derecha.</li>
<li><em>down</em>: para la flecha abajo</li>
<li>left-meta</em>: para la tecla de AltGr o Cmd del lado izquierdo</li>
<li><em>right-meta</em>: para la tecla de AltGr o Cmd del lado izquierdo</li>
</ul>

<h2 id="avanzado">Aspectos avanzados</h2>

<p>
Utilizando las variables <strong>key</strong> y <strong>keyPressed</strong> podemos programar casi cualquier tipo de aplicación interativa o videojuego que use el teclado. Sin embargo, a veces necesitamos tener un control más preciso para poder actuar de una manera cuando se pulsa una tecla y de otra cuando se suelta. Si en nuestro programa incluimos una función llamada <strong>onKeyPressed</strong> que reciba como parámetro una cadena y devuelva <strong>void</strong>, dicha función será invocada automáticamente cada vez que se pulse una tecla. El nombre de la tecla en cuestión se le pasará a la función como parámetro. Es importante tener en cuenta que sólo se ejecuta la función al pulsarse la tecla, si esta permanece pulsada no se vuelve a ejecutar la función. 
</p>
<p>Del mismo modo, si incluimos una función llamada <strong>onKeyReleased</strong> seremos capaces de controlar cuando se suelta cualquier tecla. El siguiente programa muestra un ejemplo de uso de las dos funciones en el que, usando las variables globales <strong>aPulsada</strong> y <strong>bPulsada</strong> podemos saber en todo momento el estado de las teclas A y B respectivamente para mostrar por pantalla un dibujo asociado a cada una.
</p>

<pre><code>boolean aPulsada;
boolean bPulsada;

void onKeyPressed(String tecla) {
  switch (tecla) {
    case "a": 
    	aPulsada = true;  
    	break;
    case "b":
    	bPulsada = true;
    	break;
  }
}

void onKeyReleased(String tecla) {
  switch (tecla) {
    case "a":
    	aPulsada = false;
    	break;
    case "b":
    	bPulsada = false;
    	break;
  }
}

void dibuja() {
  background(0,0,0);
	if (aPulsada) {
    ellipse(100,100, 50,50);
  }  
  if (bPulsada) {
    rect(75, 200, 50,50);
  }
}

void main() {
  animate(dibuja);
}</code></pre>
		
<p>
		Normalmente usamos la función <strong>animate</strong> indicando entre paréntesis el nombre de la función que queremos que se anime, es decir, que se ejecute una vez tras otra de forma indefinida. De esta forma, el ordenador ejecuta la función 25 veces por segundo o, dicho de otro modo, cada 40 milisegundos.
		Sin embargo, existe la opción de pasar un segundo parámetro a la función <strong>animate</strong> indicando el tiempo a esperar entre cada ejecución de la función a animar. De este modo podemos hacer animaciones más fluidas o más lentas según nos interese.
</p>
<p>En el siguiente ejemplo vemos una animación que va muy lenta pues estamos haciendo que se ejecute la función <strong>dibuja</strong> cada medio segundo (500 milisegundos)</p>
<pre><code>int x = 25;
int dx = 1;

void dibuja() {
  background(0,0,0);
  if (x > 295) dx = -1;
  if (x < 25) dx = 1;
  x = x + dx;
  ellipse(x,160,50,50);
}

void main() {
  animate(dibuja, 500);
}</code></pre>
<p>
Además, podemos usar la función <strong>animate</strong> a mitad de una animación para hacer que el ordenador deje de hacer la animación actual y pase a hacer la nueva. Esto nos puede resultar muy útil para definir las distintas fases de nuestro juego: menú principal, juego, ventana de resultados, etc. En el siguiente ejemplo se demuestra esta técnica usando dos funciones de animación distintas, una para mover un círculo en horizontal y otra en vertical, al pulsar una tecla se pasa de una animación a la otra:
</p>

<pre><code>int x = 25;
int y = 25;
int dx = 1;
int dy = 1;

void dibujaHorizontal() {
  background(0,0,0);
  if (x > 295) dx = -1;
  if (x < 25) dx = 1;
  x = x + dx;
  ellipse(x,y,50,50);
  if (keyPressed) animate(dibujaVertical);
}

void dibujaVertical() {
  background(0,0,0);
  if (y > 295) dy = -1;
  if (y < 25) dy = 1;
  y = y + dy;
  ellipse(x,y,50,50);
  if (keyPressed) animate(dibujaHorizontal);
}

void main() {
  animate(dibujaHorizontal);
}</code></pre>

<p>
Finalmete, también podemos usar la función <strong>exit</strong> para detener la ejecución del programa en cualquier momento. Esto puede ser útil cuando, a mitad de una animación, se llega a una situación en la que ya no se puede continuar o, por ejemplo, queremos detener el juego después de perder la partida.
</p>
<p>
		
				Sigue aprendiendo en <a href="lenguaje-07.php">El lenguaje de programación iJava (7)</a>
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
