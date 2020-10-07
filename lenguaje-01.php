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
	<h2>Entorno de programación</h2>
		<p>
		Para escribir un programa en iJava usaremos la página <a href="editor-codigo.php">crea</a> en donde podemos encontrar un un recuadro en blanco para escribir nuestros programas, una zona cuadrada gris oscura en donde aparecerán los gráficos que nuestros programas dibujen y una zona inferior blanca, que sólo se muestra al probar los programas, y en la que aparecerán mensajes de texto que genere nuestro programa.
		</p>
		<p>
		Un programa en iJava siempre incluye una función principal denominada <strong>main</strong> sin parámetros y cuyo tipo es <strong>void</strong>. De este modo el programa más pequeño que podemos escribir en iJava tendrá esta forma:
		</p>
<pre><code>void main() {
}</code></pre>
		<p>
		A medida que escribamos nuestro programa es posible que aparezcan mensajes de error en la parte inferior indicando qué hemos hecho mal y cuál puede ser la solución. Una vez que nuestro programa no tenga errores podremos probarlo pulsando en el botón probar. Y, si además, estamos registrados y tenemos cuenta de usuario, podremos darle un nombre y guardarlo en la nube. El botón nuevo nos permitirá crear un nuevo programa desde cero así que conviene guardar el anterior antes de pulsarlo.
		</p>
		<p>El programa anterior, aunque es correcto, no hace nada. Para que haga algo debemos añadir instrucciones en su interior, es decir, entre las dos llaves (caracteres <strong>{</strong> y <strong>}</strong>). Las instrucciones más sencillas que podemos utilizar son las que sirven para pedir al ordenador que haga alguna de las tareas que ya sabe hacer. A estas tareas las llamamos funciones de biblioteca y nos permitirán, por ejemplo, dibujar un circulo en la pantalla. Para conseguirlo escribe y prueba el siguiente programa</p> 
<pre><code>void main() {
	ellipse(160,160,100,100);
}</code></pre>
		<h2>Funciones</h2>
		<p>El programa anterior incluye una única instrucción terminada con punto y coma. Podemos escribir todas las que queramos una detrás de otra, pero siempre acabando cada una con punto y coma ya que ese símbolo es el que sirve en iJava para separar dos instrucciones. Esa instrucción consiste en la petición al ordenador de que lleve a cabo la función llamada <strong>ellipse</strong> que es una de las funciones gráficas incluidas en la biblioteca de funciones de iJava.</p>
		<p>
		Cada función tienen un objetivo concreto, el de la función <strong>ellipse</strong> es dibujar una elipse. Pero podemos indicarle al ordenador dónde y con qué tamaño dibujarla a través de los parámetros que tiene la función. Los parámetros se indican separados por comas y encerrados entre paréntesis. Las funciones pueden o no tener parámetros y el número y tipo de los mismos también es propio de cada función. Para saber como usar cada función podemos usar la referencia rápida que aparece al pulsar en el botón "ayuda" de la página <a href="editor-codigo.php">crea</a>.
		</p>
		<h2>Funciones Gráficas</h2>
		<p>La función <strong>ellipse</strong>, como el resto de las funciones de biblioteca para manejar gráficos, muestra sus resultados en el cuadrado situado a la derecha de la zona donde se escribe el código al que llamamos pantalla. Esta pantalla tiene un tamaño de 320 por 320 puntos. La esquina superior izquierda corresponde a la posición (0,0), y la esquina inferior derecha a la posición (319,319). Prueba a modificar el programa anterior añadiendo más instrucciones cambiando los parámetros para modificar la posición y tamaño de las elipses que se dibujan.
				</p>
		<p>A continuación tienes una lista con todas las funciones de biblioteca que nos permiten hacer dibujos en la pantalla y pinchando en cada una de ellas accederás a un programa escrito junto con la explicación de los parámetros que tienen.
		</p>	
		
		<ul>
			<li><a href="editor-codigo.php?id=d538ef20bffd47a578eb77a787ea6bc6">point(x,y)</a><p>Dibuja un punto en las coordenadas (x,y)</p></li>
			<li><a href="editor-codigo.php?id=08240c787347a1daaf1b7fb12c8fee54">line(x1,y1,x2,y2)</a><p>Dibuja una línea de (x1,y1) a (x2,y2).</p></li>
			<li><a href="editor-codigo.php?id=9fa39360-803a-42b0-a950-dd83c98ea92e">ellipse(x,y,w,h)</a><p>Dibuja una elipse centrada en (x,y) con anchura w y altura h.</p></li>
			<li><a href="editor-codigo.php?id=f785f554553126f0791b96f67dd10944">rect(x,y,w,h)</a><p>Dibuja un rectángulo cuya esquina superior izquierda será (x,y) y tendrá w puntos de ancho y h puntos de alto.</p></li>
			<li><a href="editor-codigo.php?id=927eb9ec5e91949fe75fad870e9e55e8">triangle(x1,y1,x2,y2,x3,y3)</a><p>Dibuja un triángulo entre los puntos (x1,y1), (x2,y2) y (x3,y3).</p></li>
			<li><a href="editor-codigo.php?id=73d19b229244261ddc469f2d0e8ca75d">background(r,g,b,a)</a><p>Cambia el color de fondo.</p></li>
		</ul>
		<p>Cuando se dibuja una figura con cualquiera de las funciones anteriores el contorno se pinta de color negro y el interior de color blanco. Para dibujar una figura con otros colores tenemos que elegirlos antes de usar la función que dibuje la figura. Para elegir el color del contorno usaremos la función <strong>stroke</strong> y para elegir el color de relleno la función <strong>fill</strong>. También es posible elegir el grosor de los contornos con la función <strong>strokeWeigth</strong>. El siguiente programa muestra cómo se utilizan estas tres funciones</p>
<pre><code>void main() {
	// Fijo el color de fondo a gris claro
	background(192,192,192);
	// Fijo el grosor de los contornos a 5 puntos
	strokeWeight(5);
	// Elijo rojo puro para el color de los contornos
	stroke(255,0,0);
	// Elijo amarillo puro para el color de relleno
	fill(255,255,0);
	point(160,160);
	// Elijo verde puro para el color de los contornos
	stroke(0,255,0);
	line(10,10,310,10);
	// Elijo azul puro para el color de los contornos
	stroke(0,0,255);
	ellipse(160,50,300, 40);
	// Elijo negro puro para el color de los contornos
	stroke(0,0,0);
	triangle(10,100, 310, 100, 160,150);
	// Elijo gris para el color de los contornos
	stroke(128,128,128);
	// Elijo cyan para el color de relleno
	fill(0,255,255);
	rect(10,170,300, 50);
}</code></pre>
		<p>En el programa anterior, lo primero que se hace es cambiar el color de fondo usando la función <strong>background</strong>. Después dibuja una figura de cada tipo y utiliza la función <strong>strokeWeight</strong> al comienzo para fijar el grosor de los contornos a cinco puntos. Una vez fijado se mantiene así hasta el final del programa o hasta que se cambie de nuevo. Como en este programa no vuelve a cambiarse el grosor todas las figuras aparecen con un contorno de cinco puntos. Las funciones <strong>stroke</strong> y <strong>fill</strong> se comportan del mismo modo, es decir, una vez que se elige el color de contorno o relleno todas las figuras que se dibujen a continuación usarán esos colores. Por eso, sólo el rectángulo aparece relleno de color cyan ya que justo antes se cambia el color de relleno, el resto de figuras se rellenan de amarillo que es el color de relleno elegido al comienzo del programa.</p>
		<p>En iJava se utiliza el modelo de <a href="http://es.wikipedia.org/wiki/RGB">componentes RGB</a> para trabajar con colores. Esto significa que cualquier color se define mediante tres números enteros entre 0 y 255. También es posible crear colores transparentes añadiendo una cuarta componente llamada alpha que será un número real entre 0 y 1. Para que resulte más fácil elegir el color las funciones de biblioteca que sirven para definir colores aparecen en el código del programa subrayadas y si pinchamos en ellas se despliega un selector de color en el que puedes elegir el color que desees y el nivel de transparencia que necesites.</p>
		<p>Para que no se dibujen los contornos o no se rellenen las figuras se utilizan las funciones <strong>noStroke</strong> y <strong>noFill</strong> respectivamente.</p>
		<ul>
			<li><a href="editor-codigo.php?id=21444ff14f20bb561b93c4428e703416">noStroke()</a><p>Desactiva el dibujado de contornos.</p></li>
			<li><a href="editor-codigo.php?id=0264856f5ab93da00f7a9a17dacbe59b">noFill()</a><p>Desactiva el relleno de figuras.</p></li>
		</ul>
		<p>Además de dibujar figuras es posible dibujar texto en la pantalla. Para ello usamos la función <strong>text</strong> indicando el mensaje a mostrar, y la posición de la parte izquierda del renglón donde se situará el texto. El texo se dibuja con una letra de 14 puntos de ancho pero es posible modificarlo usando la función <strong>textSize</strong>. Por último, la función <strong>textWidth</strong> nos permite saber la anchura de un texto en función del tamaño de letra que haya configurado en ese momento.</p>
		<ul>
			<li><a href="editor-codigo.php?id=f81d945345b89e92ba70234795d0a5e2">text(msg,x,y)</a><p>Muestra msg en las coordenadas (x,y)</p></li>
			<li><a href="editor-codigo.php?id=4a5b1076386229dda59e47b778b54e1f">textSize(n)</a><p>Selecciona el tamaño de letra</p></li>
			<li><a href="editor-codigo.php?id=bf0f88834dca6c070c23c550725de786">textWidth(msg)</a><p>Devuelve la anchura en puntos de msg.</p></li>
		</ul>
		<p>Finalmente, iJava incluye una función para dibujar imágenes. Las imágenes deben estar en alguna página web pública y se deben respetar los derechos de autor a la hora de utilizarlas. Para dibujar una imagen usaremos la función <strong>image</strong>.</p>

		<ul>
			<li><a href="editor-codigo.php?id=70701d14a525d96e05c7f98f7a09fd4e">image(url, x,y,w,h)</a><p>Dibuja la imagen que haya en la dirección de internet url y la dibuja situando la esquina superior ixquierda en (x,y) y con una anchura y altura de w y h puntos respectivamente.</p></li>
		</ul>

		<h2>Funciones para mostrar información</h2>
		<p>
		Cuando se ejecutan los programas, en la parte inferior de la ventana aparece un recuadro gris claro donde se van a mostrar todos los mensajes de texto que genere nuestro programa. Es decir, ese será el dispositivo de salida.	A continuación tienes una lista con las dos funciones de biblioteca que nos permiten mostrar mensajes. Pinchando en cada una de ellas accederás a un programa escrito junto con su explicación.
		</p>	
			<ul>
				<li><a href="editor-codigo.php?id=1bd34e65e7b94699e6910416dfa11396">print(msg)</a><p>Muestra msg por la parte inferior de la ventana.</p></li>
				<li><a href="editor-codigo.php?id=42e479e87cf2a862ac9a3c4e2ef34dc4">println(msg)</a><p>Muestra msg por la parte inferior de la ventana y cambia de renglón al terminar.</p></li>						
			</ul>		
		<p>
		La información que podemos mostrar con las funciones <strong>print</strong> y <strong>println</strong> puede ser un valor literal, es decir, escrito directamente como parámetro. La función <strong>println</strong> se usa habitualmente para mostrar un texto, los textos se encierran entre comillas dobles como se puede ver en el siguiente programa mientras que los números se escriben de la forma habitual e incluso es posible usar la notación cietífica para expresarlos.
		</p>
<pre><code>void main() {
	println("Hola mundo!!");
	println(2);
	println(1.35);
	println(0.135E+2);
}</code></pre>			
		<p>Pero además de valores literales, <strong>print</strong> y <strong>println</strong> también pueden mostrar el resultado de un cálculo. El ordenador, como cualquier calculadora, es capaz de hacer operaciones matemáticas. Para expresarlas utilizaremos los símbolos <strong>+</strong>, <strong>-</strong>, <strong>*</strong>, <strong>/</strong> para indicar las operaciones aritméticas básicas, y el símbolo <strong>%</strong> para obtener el resto de la división. También se pueden utilizar paréntesis para agrupar los términos.
		</p>
<!--		<p>
		La evaluación de expresiones se hace en orden según la prioridad de los operadores. En primer lugar se hacen las multiplicaciones, divisiones y restos y después las sumas y restas. Cuando hay varios operadores de la misma prioridad, se empieza por la izquierda, y para alterar el orden natural de evaluación se pueden utilizar paréntesis de la forma habitual. Por ejemplo, el siguiente programa muestra los resultados de varias operaciones aritméticas. Fíjate especialmente en la diferencia que existe al utilizar números enteros o números reales en las operaciones.
		</p>-->
		
<pre><code>void main() {
	// Muestra 1 más el resultado de multiplicar 2 y 3
	println(1 + 2 * 3);
	// Muestra el resultado de multiplicar 3 por la suma de 1 y 2
	println((1 + 2) * 3);
	// Muestra el resto de dividir 5 entre 2
	println(5 % 2);
	// Muestra el resultado de dividir 1 entre 2 cuando ambos números son enteros
	println(1 / 2);
	// Muestra el resultado de dividir 1 entre 2 cuando ambos números son reales
	println(1.0 / 2.0);			
}</code></pre>
		<p>
		En el programa anterior también aparece una de las dos formas en que podemos introducir comentarios en nuestros programas. Los comentarios los escribimos para explicar qué hace nuestro programa. Sirven para que otras personas o incluso nosotros mismos entendamos mejor el programa pero el ordenador los ignora completamente. Un comentario de una línea se escribe empezando por dos barras inclinadas <strong>//</strong> y un comentario de múltiples líneas se escribe encerrando todo el texto entre las marcas <strong>/*</strong> y <strong>*/</strong>.
		</p>
		<p>
		Las expresiones aritméticas que hemos visto cómo escribir en el programa anterior son calculadas por el ordenador y, en ese caso, su resultado es utilizado como parámetro de la función <strong>println</strong>.Aunque también podemos utilizar expresiones aritméticas como parámetros de cualquier función.
		</p>
		<h2>Funciones matemáticas</h2>
		<p>Además de las operaciones aritméticas básicas también suele ser necesario realizar otras operaciones matemáticas más complejas como raíces cuadradas o cálculos con ángulos. Para realizar todas estas operaciones iJava incluye las siguientes funciones matemáticas.
		</p>
		<ul>
			<li><a href="editor-codigo.php?id=46f2c08ea0547d751719c68a140b79c7">abs(n)</a></li>
			<li><a href="editor-codigo.php?id=d7538195e32e988df847ae5ac09f12c3">log(n)</a></li>
			<li><a href="editor-codigo.php?id=55cc23c3084f8b849561d9af27bdb54b">sqrt(n)</a></li>
			<li><a href="editor-codigo.php?id=b0443703085d417c7f35c65b24714341">pow(b,e)</a></li>
			<li><a href="editor-codigo.php?id=e9e870fcf22a9baed80c88408b009144">floor(n)</a></li>
			<li><a href="editor-codigo.php?id=b12d07f3b421c0e0a76acbcadff70c58">ceil(n)</a></li>
			<li><a href="editor-codigo.php?id=35553ecee848cb2b4e94e9f03e4a4489">round(n)</a></li>
			<li><a href="editor-codigo.php?id=7fb7df3a517fb72846655f3e58d81c0f">sin(n)</a></li>
			<li><a href="editor-codigo.php?id=34b06804695c76d1a4ef056515f39763">cos(n)</a></li>
			<li><a href="editor-codigo.php?id=38163f1c116056d27754f687f48dfdd1">tan(n)</a></li>
			<li><a href="editor-codigo.php?id=6db41fe33b76b4bc5c7c683d92ea29c6">asin(n)</a></li>
			<li><a href="editor-codigo.php?id=5a1ab543be062143cbec38396909626f">acos(n)</a></li>
			<li><a href="editor-codigo.php?id=406b48809ffd31c2b1061981e4fb977f">atan(n)</a></li>
		</ul>
		<p>Todas las funciones matemáticas devuelve un resultado que podemos utilizar independientemente o como un operando más de una expresión aritmética. Por ejemplo, el siguiente programa muestra primero cuánto vale la raíz cuadrada de 2, después el valor de la raíz de 3 y finalmente el resultado de sumar ambas.
		</p>
<pre><code>void main() {
	// Muestra la raiz de 2
	println(sqrt(2));
	// Muestra la raiz de 3
	println(sqrt(3));
	// Muestra la suma de ambas
	println(sqrt(2)+sqrt(3));
}</code></pre>
		<p>Es importante entender el orden en que se ejecuta el programa. En la primera instrucción, iJava lo primero que hace es pasar el valor 2 a la función <strong>sqrt</strong> para que esta pueda calcular la raíz cuadrada de 2. Después, usa el resultado que devuelve dicha función como parámetro de la función <strong>println</strong> que se encarga de mostrar ese valor por la pantalla. La segunda instrucción se comporta de forma similar mientras que en la tercera, lo primero que hace iJava es calcular el resultado de la raíz cuadrada de 2 usando la función <strong>sqrt</strong>, después hace lo propio con la raíz cuadrada de 3, posteriormente calcula el resultado de sumar ambos valores y ese número es el que le pasa a la función <strong>println</strong> para que aparezca por la pantalla.
		</p>
		<p>
		Todas las funciones matemáticas esperan números que pueden ser enteros o reales aunque los interpretan como si fueran reales. Sin embargo, mientras que las funciones <strong>log</strong>, <strong>sqrt</strong>, <strong>pow</strong>, <strong>sin</strong>, <strong>cos</strong>, <strong>tan</strong>, <strong>asin</strong>, <strong>acos</strong> y <strong>atan</strong> devuelven un número real, las funciones <strong>abs</strong>, <strong>floor</strong>, <strong>ceil</strong> y <strong>round</strong> devuelven un número entero.
		</p>
		<p>
		iJava incluye otra función que, aunque no es exactamente una función matemática, puede sernos de utilidad en muchos casos. Se trata de la función <strong>random</strong> que sirve para generar un número aleatorio. Esta función puede ser utilizada sin parámetros, con uno o con dos. Cuando la usamos sin parámetros devuelve un número real aleatorio entre 0 y 1, cuando le ponemos un parámetro el número aleatorio se genera entre 0 y el valor del parámetro, y si ponemos dos, el número aleatorio se genera en el intervalo indicado por ambos parámetros.
		</p>
		<ul>
			<li><a href="editor-codigo.php?id=0b11713511fe5c4848d0fb1a4f28b406">random()</a></li>
		</ul>
		
		<h2>Fecha y hora</h2>
		<p>iJava también incluye algunas funciones para conocer la fecha y la hora del ordenador donde se está utilizando. Las funciones son las siguientes:</p>
		<ul>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">second()</a><p>Devuelve los segundos correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">minute()</a><p>Devuelve los minutos correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">hour()</a><p>Devuelve la hora correspondiente al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">day()</a><p>Devuelve el día correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">month()</a><p>Devuelve el mes correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">year()</a><p>Devuelve el año correspondiente al momento actual.</p></li>
		</ul>
		
		<p>Además también existe la función <a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">millis()</a> que devuelve el número de milisegundos que han transcurrido desde que empezó el programa. Esta función suele usarse en programas con animaciones para controlar el progreso de las mismas.</p>

				
				<p>
				Sigue aprendiendo en <a href="lenguaje-02.php">El lenguaje de programación iJava (2)</a>
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
