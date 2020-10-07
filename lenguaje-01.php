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
			<h1>El lenguaje de programaci�n iJava (2)</h1>
		</div>
	</div>
	<div class="col-md-12">
	<h2>Entorno de programaci�n</h2>
		<p>
		Para escribir un programa en iJava usaremos la p�gina <a href="editor-codigo.php">crea</a> en donde podemos encontrar un un recuadro en blanco para escribir nuestros programas, una zona cuadrada gris oscura en donde aparecer�n los gr�ficos que nuestros programas dibujen y una zona inferior blanca, que s�lo se muestra al probar los programas, y en la que aparecer�n mensajes de texto que genere nuestro programa.
		</p>
		<p>
		Un programa en iJava siempre incluye una funci�n principal denominada <strong>main</strong> sin par�metros y cuyo tipo es <strong>void</strong>. De este modo el programa m�s peque�o que podemos escribir en iJava tendr� esta forma:
		</p>
<pre><code>void main() {
}</code></pre>
		<p>
		A medida que escribamos nuestro programa es posible que aparezcan mensajes de error en la parte inferior indicando qu� hemos hecho mal y cu�l puede ser la soluci�n. Una vez que nuestro programa no tenga errores podremos probarlo pulsando en el bot�n probar. Y, si adem�s, estamos registrados y tenemos cuenta de usuario, podremos darle un nombre y guardarlo en la nube. El bot�n nuevo nos permitir� crear un nuevo programa desde cero as� que conviene guardar el anterior antes de pulsarlo.
		</p>
		<p>El programa anterior, aunque es correcto, no hace nada. Para que haga algo debemos a�adir instrucciones en su interior, es decir, entre las dos llaves (caracteres <strong>{</strong> y <strong>}</strong>). Las instrucciones m�s sencillas que podemos utilizar son las que sirven para pedir al ordenador que haga alguna de las tareas que ya sabe hacer. A estas tareas las llamamos funciones de biblioteca y nos permitir�n, por ejemplo, dibujar un circulo en la pantalla. Para conseguirlo escribe y prueba el siguiente programa</p> 
<pre><code>void main() {
	ellipse(160,160,100,100);
}</code></pre>
		<h2>Funciones</h2>
		<p>El programa anterior incluye una �nica instrucci�n terminada con punto y coma. Podemos escribir todas las que queramos una detr�s de otra, pero siempre acabando cada una con punto y coma ya que ese s�mbolo es el que sirve en iJava para separar dos instrucciones. Esa instrucci�n consiste en la petici�n al ordenador de que lleve a cabo la funci�n llamada <strong>ellipse</strong> que es una de las funciones gr�ficas incluidas en la biblioteca de funciones de iJava.</p>
		<p>
		Cada funci�n tienen un objetivo concreto, el de la funci�n <strong>ellipse</strong> es dibujar una elipse. Pero podemos indicarle al ordenador d�nde y con qu� tama�o dibujarla a trav�s de los par�metros que tiene la funci�n. Los par�metros se indican separados por comas y encerrados entre par�ntesis. Las funciones pueden o no tener par�metros y el n�mero y tipo de los mismos tambi�n es propio de cada funci�n. Para saber como usar cada funci�n podemos usar la referencia r�pida que aparece al pulsar en el bot�n "ayuda" de la p�gina <a href="editor-codigo.php">crea</a>.
		</p>
		<h2>Funciones Gr�ficas</h2>
		<p>La funci�n <strong>ellipse</strong>, como el resto de las funciones de biblioteca para manejar gr�ficos, muestra sus resultados en el cuadrado situado a la derecha de la zona donde se escribe el c�digo al que llamamos pantalla. Esta pantalla tiene un tama�o de 320 por 320 puntos. La esquina superior izquierda corresponde a la posici�n (0,0), y la esquina inferior derecha a la posici�n (319,319). Prueba a modificar el programa anterior a�adiendo m�s instrucciones cambiando los par�metros para modificar la posici�n y tama�o de las elipses que se dibujan.
				</p>
		<p>A continuaci�n tienes una lista con todas las funciones de biblioteca que nos permiten hacer dibujos en la pantalla y pinchando en cada una de ellas acceder�s a un programa escrito junto con la explicaci�n de los par�metros que tienen.
		</p>	
		
		<ul>
			<li><a href="editor-codigo.php?id=d538ef20bffd47a578eb77a787ea6bc6">point(x,y)</a><p>Dibuja un punto en las coordenadas (x,y)</p></li>
			<li><a href="editor-codigo.php?id=08240c787347a1daaf1b7fb12c8fee54">line(x1,y1,x2,y2)</a><p>Dibuja una l�nea de (x1,y1) a (x2,y2).</p></li>
			<li><a href="editor-codigo.php?id=9fa39360-803a-42b0-a950-dd83c98ea92e">ellipse(x,y,w,h)</a><p>Dibuja una elipse centrada en (x,y) con anchura w y altura h.</p></li>
			<li><a href="editor-codigo.php?id=f785f554553126f0791b96f67dd10944">rect(x,y,w,h)</a><p>Dibuja un rect�ngulo cuya esquina superior izquierda ser� (x,y) y tendr� w puntos de ancho y h puntos de alto.</p></li>
			<li><a href="editor-codigo.php?id=927eb9ec5e91949fe75fad870e9e55e8">triangle(x1,y1,x2,y2,x3,y3)</a><p>Dibuja un tri�ngulo entre los puntos (x1,y1), (x2,y2) y (x3,y3).</p></li>
			<li><a href="editor-codigo.php?id=73d19b229244261ddc469f2d0e8ca75d">background(r,g,b,a)</a><p>Cambia el color de fondo.</p></li>
		</ul>
		<p>Cuando se dibuja una figura con cualquiera de las funciones anteriores el contorno se pinta de color negro y el interior de color blanco. Para dibujar una figura con otros colores tenemos que elegirlos antes de usar la funci�n que dibuje la figura. Para elegir el color del contorno usaremos la funci�n <strong>stroke</strong> y para elegir el color de relleno la funci�n <strong>fill</strong>. Tambi�n es posible elegir el grosor de los contornos con la funci�n <strong>strokeWeigth</strong>. El siguiente programa muestra c�mo se utilizan estas tres funciones</p>
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
		<p>En el programa anterior, lo primero que se hace es cambiar el color de fondo usando la funci�n <strong>background</strong>. Despu�s dibuja una figura de cada tipo y utiliza la funci�n <strong>strokeWeight</strong> al comienzo para fijar el grosor de los contornos a cinco puntos. Una vez fijado se mantiene as� hasta el final del programa o hasta que se cambie de nuevo. Como en este programa no vuelve a cambiarse el grosor todas las figuras aparecen con un contorno de cinco puntos. Las funciones <strong>stroke</strong> y <strong>fill</strong> se comportan del mismo modo, es decir, una vez que se elige el color de contorno o relleno todas las figuras que se dibujen a continuaci�n usar�n esos colores. Por eso, s�lo el rect�ngulo aparece relleno de color cyan ya que justo antes se cambia el color de relleno, el resto de figuras se rellenan de amarillo que es el color de relleno elegido al comienzo del programa.</p>
		<p>En iJava se utiliza el modelo de <a href="http://es.wikipedia.org/wiki/RGB">componentes RGB</a> para trabajar con colores. Esto significa que cualquier color se define mediante tres n�meros enteros entre 0 y 255. Tambi�n es posible crear colores transparentes a�adiendo una cuarta componente llamada alpha que ser� un n�mero real entre 0 y 1. Para que resulte m�s f�cil elegir el color las funciones de biblioteca que sirven para definir colores aparecen en el c�digo del programa subrayadas y si pinchamos en ellas se despliega un selector de color en el que puedes elegir el color que desees y el nivel de transparencia que necesites.</p>
		<p>Para que no se dibujen los contornos o no se rellenen las figuras se utilizan las funciones <strong>noStroke</strong> y <strong>noFill</strong> respectivamente.</p>
		<ul>
			<li><a href="editor-codigo.php?id=21444ff14f20bb561b93c4428e703416">noStroke()</a><p>Desactiva el dibujado de contornos.</p></li>
			<li><a href="editor-codigo.php?id=0264856f5ab93da00f7a9a17dacbe59b">noFill()</a><p>Desactiva el relleno de figuras.</p></li>
		</ul>
		<p>Adem�s de dibujar figuras es posible dibujar texto en la pantalla. Para ello usamos la funci�n <strong>text</strong> indicando el mensaje a mostrar, y la posici�n de la parte izquierda del rengl�n donde se situar� el texto. El texo se dibuja con una letra de 14 puntos de ancho pero es posible modificarlo usando la funci�n <strong>textSize</strong>. Por �ltimo, la funci�n <strong>textWidth</strong> nos permite saber la anchura de un texto en funci�n del tama�o de letra que haya configurado en ese momento.</p>
		<ul>
			<li><a href="editor-codigo.php?id=f81d945345b89e92ba70234795d0a5e2">text(msg,x,y)</a><p>Muestra msg en las coordenadas (x,y)</p></li>
			<li><a href="editor-codigo.php?id=4a5b1076386229dda59e47b778b54e1f">textSize(n)</a><p>Selecciona el tama�o de letra</p></li>
			<li><a href="editor-codigo.php?id=bf0f88834dca6c070c23c550725de786">textWidth(msg)</a><p>Devuelve la anchura en puntos de msg.</p></li>
		</ul>
		<p>Finalmente, iJava incluye una funci�n para dibujar im�genes. Las im�genes deben estar en alguna p�gina web p�blica y se deben respetar los derechos de autor a la hora de utilizarlas. Para dibujar una imagen usaremos la funci�n <strong>image</strong>.</p>

		<ul>
			<li><a href="editor-codigo.php?id=70701d14a525d96e05c7f98f7a09fd4e">image(url, x,y,w,h)</a><p>Dibuja la imagen que haya en la direcci�n de internet url y la dibuja situando la esquina superior ixquierda en (x,y) y con una anchura y altura de w y h puntos respectivamente.</p></li>
		</ul>

		<h2>Funciones para mostrar informaci�n</h2>
		<p>
		Cuando se ejecutan los programas, en la parte inferior de la ventana aparece un recuadro gris claro donde se van a mostrar todos los mensajes de texto que genere nuestro programa. Es decir, ese ser� el dispositivo de salida.	A continuaci�n tienes una lista con las dos funciones de biblioteca que nos permiten mostrar mensajes. Pinchando en cada una de ellas acceder�s a un programa escrito junto con su explicaci�n.
		</p>	
			<ul>
				<li><a href="editor-codigo.php?id=1bd34e65e7b94699e6910416dfa11396">print(msg)</a><p>Muestra msg por la parte inferior de la ventana.</p></li>
				<li><a href="editor-codigo.php?id=42e479e87cf2a862ac9a3c4e2ef34dc4">println(msg)</a><p>Muestra msg por la parte inferior de la ventana y cambia de rengl�n al terminar.</p></li>						
			</ul>		
		<p>
		La informaci�n que podemos mostrar con las funciones <strong>print</strong> y <strong>println</strong> puede ser un valor literal, es decir, escrito directamente como par�metro. La funci�n <strong>println</strong> se usa habitualmente para mostrar un texto, los textos se encierran entre comillas dobles como se puede ver en el siguiente programa mientras que los n�meros se escriben de la forma habitual e incluso es posible usar la notaci�n ciet�fica para expresarlos.
		</p>
<pre><code>void main() {
	println("Hola mundo!!");
	println(2);
	println(1.35);
	println(0.135E+2);
}</code></pre>			
		<p>Pero adem�s de valores literales, <strong>print</strong> y <strong>println</strong> tambi�n pueden mostrar el resultado de un c�lculo. El ordenador, como cualquier calculadora, es capaz de hacer operaciones matem�ticas. Para expresarlas utilizaremos los s�mbolos <strong>+</strong>, <strong>-</strong>, <strong>*</strong>, <strong>/</strong> para indicar las operaciones aritm�ticas b�sicas, y el s�mbolo <strong>%</strong> para obtener el resto de la divisi�n. Tambi�n se pueden utilizar par�ntesis para agrupar los t�rminos.
		</p>
<!--		<p>
		La evaluaci�n de expresiones se hace en orden seg�n la prioridad de los operadores. En primer lugar se hacen las multiplicaciones, divisiones y restos y despu�s las sumas y restas. Cuando hay varios operadores de la misma prioridad, se empieza por la izquierda, y para alterar el orden natural de evaluaci�n se pueden utilizar par�ntesis de la forma habitual. Por ejemplo, el siguiente programa muestra los resultados de varias operaciones aritm�ticas. F�jate especialmente en la diferencia que existe al utilizar n�meros enteros o n�meros reales en las operaciones.
		</p>-->
		
<pre><code>void main() {
	// Muestra 1 m�s el resultado de multiplicar 2 y 3
	println(1 + 2 * 3);
	// Muestra el resultado de multiplicar 3 por la suma de 1 y 2
	println((1 + 2) * 3);
	// Muestra el resto de dividir 5 entre 2
	println(5 % 2);
	// Muestra el resultado de dividir 1 entre 2 cuando ambos n�meros son enteros
	println(1 / 2);
	// Muestra el resultado de dividir 1 entre 2 cuando ambos n�meros son reales
	println(1.0 / 2.0);			
}</code></pre>
		<p>
		En el programa anterior tambi�n aparece una de las dos formas en que podemos introducir comentarios en nuestros programas. Los comentarios los escribimos para explicar qu� hace nuestro programa. Sirven para que otras personas o incluso nosotros mismos entendamos mejor el programa pero el ordenador los ignora completamente. Un comentario de una l�nea se escribe empezando por dos barras inclinadas <strong>//</strong> y un comentario de m�ltiples l�neas se escribe encerrando todo el texto entre las marcas <strong>/*</strong> y <strong>*/</strong>.
		</p>
		<p>
		Las expresiones aritm�ticas que hemos visto c�mo escribir en el programa anterior son calculadas por el ordenador y, en ese caso, su resultado es utilizado como par�metro de la funci�n <strong>println</strong>.Aunque tambi�n podemos utilizar expresiones aritm�ticas como par�metros de cualquier funci�n.
		</p>
		<h2>Funciones matem�ticas</h2>
		<p>Adem�s de las operaciones aritm�ticas b�sicas tambi�n suele ser necesario realizar otras operaciones matem�ticas m�s complejas como ra�ces cuadradas o c�lculos con �ngulos. Para realizar todas estas operaciones iJava incluye las siguientes funciones matem�ticas.
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
		<p>Todas las funciones matem�ticas devuelve un resultado que podemos utilizar independientemente o como un operando m�s de una expresi�n aritm�tica. Por ejemplo, el siguiente programa muestra primero cu�nto vale la ra�z cuadrada de 2, despu�s el valor de la ra�z de 3 y finalmente el resultado de sumar ambas.
		</p>
<pre><code>void main() {
	// Muestra la raiz de 2
	println(sqrt(2));
	// Muestra la raiz de 3
	println(sqrt(3));
	// Muestra la suma de ambas
	println(sqrt(2)+sqrt(3));
}</code></pre>
		<p>Es importante entender el orden en que se ejecuta el programa. En la primera instrucci�n, iJava lo primero que hace es pasar el valor 2 a la funci�n <strong>sqrt</strong> para que esta pueda calcular la ra�z cuadrada de 2. Despu�s, usa el resultado que devuelve dicha funci�n como par�metro de la funci�n <strong>println</strong> que se encarga de mostrar ese valor por la pantalla. La segunda instrucci�n se comporta de forma similar mientras que en la tercera, lo primero que hace iJava es calcular el resultado de la ra�z cuadrada de 2 usando la funci�n <strong>sqrt</strong>, despu�s hace lo propio con la ra�z cuadrada de 3, posteriormente calcula el resultado de sumar ambos valores y ese n�mero es el que le pasa a la funci�n <strong>println</strong> para que aparezca por la pantalla.
		</p>
		<p>
		Todas las funciones matem�ticas esperan n�meros que pueden ser enteros o reales aunque los interpretan como si fueran reales. Sin embargo, mientras que las funciones <strong>log</strong>, <strong>sqrt</strong>, <strong>pow</strong>, <strong>sin</strong>, <strong>cos</strong>, <strong>tan</strong>, <strong>asin</strong>, <strong>acos</strong> y <strong>atan</strong> devuelven un n�mero real, las funciones <strong>abs</strong>, <strong>floor</strong>, <strong>ceil</strong> y <strong>round</strong> devuelven un n�mero entero.
		</p>
		<p>
		iJava incluye otra funci�n que, aunque no es exactamente una funci�n matem�tica, puede sernos de utilidad en muchos casos. Se trata de la funci�n <strong>random</strong> que sirve para generar un n�mero aleatorio. Esta funci�n puede ser utilizada sin par�metros, con uno o con dos. Cuando la usamos sin par�metros devuelve un n�mero real aleatorio entre 0 y 1, cuando le ponemos un par�metro el n�mero aleatorio se genera entre 0 y el valor del par�metro, y si ponemos dos, el n�mero aleatorio se genera en el intervalo indicado por ambos par�metros.
		</p>
		<ul>
			<li><a href="editor-codigo.php?id=0b11713511fe5c4848d0fb1a4f28b406">random()</a></li>
		</ul>
		
		<h2>Fecha y hora</h2>
		<p>iJava tambi�n incluye algunas funciones para conocer la fecha y la hora del ordenador donde se est� utilizando. Las funciones son las siguientes:</p>
		<ul>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">second()</a><p>Devuelve los segundos correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">minute()</a><p>Devuelve los minutos correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">hour()</a><p>Devuelve la hora correspondiente al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">day()</a><p>Devuelve el d�a correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">month()</a><p>Devuelve el mes correspondientes al momento actual.</p></li>
			<li><a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">year()</a><p>Devuelve el a�o correspondiente al momento actual.</p></li>
		</ul>
		
		<p>Adem�s tambi�n existe la funci�n <a href="editor-codigo.php?id=c89b1aa4a898514f055d1495279ca351">millis()</a> que devuelve el n�mero de milisegundos que han transcurrido desde que empez� el programa. Esta funci�n suele usarse en programas con animaciones para controlar el progreso de las mismas.</p>

				
				<p>
				Sigue aprendiendo en <a href="lenguaje-02.php">El lenguaje de programaci�n iJava (2)</a>
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
