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
			<h1>El lenguaje de programación iJava (7)</h1>
		</div>
	</div>
	<div class="col-md-12">
<h2 id="strings">Funciones para manejar texto</h2>
<p>Para manejar datos de tipo numérico usamos variables de tipo entero o real y para manejar valores de verdad usamos las variables de tipo <strong>boolean</strong>. Para manejar texto usamos variables de tipo <strong>String.</strong></p>
<p>A las variables de tipo <strong>String</strong> podemos darle un valor inicial indicando entre comillas dobles el texto que queramos que tengan. Y podemos comparar su valor con un texto concreto o con el de otra variable usando el símbolo <strong>==</strong>.</p>

<pre><code>void main() {
  String nombre = "Pepe";
  
  if (nombre == "Juan") {
    println("Eres tu!!");
  } else {
    println("¿Quién eres?")
  }
}</code></pre>

<p>Además de comparar dos cadenas también podemos usar variables de tipo <strong>String</strong> como elemento de decisión en la estructura <strong>switch</strong>. Por ejemplo, el siguiente programa se muestra una frase u otra en función del día de la semana que se introduzca.</p>

<pre><code>void main() {
  String dia = readString();
  switch (dia) {
    case "lunes":
      println("Comienza la semana");
      break;
    case "martes":
    case "miércoles":
    case "jueves":
    case "viernes":
      println("Mitad de semana");
      break;
    case "sábado":
    case "domingo":
      println("Fin de semana");
      break;
    default:
      println("¿Realmente " + dia + " es un día de la semana?");
  }
}</code></pre>

<p>
Fíjate como en el programa anterior usamos como etiquetas textos escritos directamente entre comillas (valores literales). Además, como lo que hay que hacer en caso de que el día introducido sea "martes", "miércoles", "jueves" o "viernes" es lo mismo hemos agrupado todas esas etiquetas para que sea cual sea la que coincida se ejecute sólo la instrucción que muestra el mensaje "Mitad de semana".
	</p>
	
	<p>
	Además, iJava incluye las siguientes funciones para manejo de texto:
	</p>	
	
	<ul>
		<li><a href="editor-codigo.php?id=65a7c8852f20aad88555e851f301d1d8">sizeOf</a>. Calcula la longitud de un texto, es decir, el número de caracteres que lo componen.</li>
		<li><a href="editor-codigo.php?id=d5127d31b0ec25f0dac6fe5b8346481a">charAt</a>. Obtiene una de las letras de un texto en función de su posición.</li>
	
		<li><a href="editor-codigo.php?id=f1ea7eaf0056585000d478da4caaa2ea">concat</a>. Crea un texto juntando otros dos.</li>
	
		<li><a href="editor-codigo.php?id=f5043039ed83b8cfa8f14e42ef4fb747">compare</a>. Compara dos textos lexicográficamente.</li>
	</ul>
	
	<p>Haciendo click en los enlaces anteriores podrás ver ejemplos de cada función. En el caso de la función <strong>compare</strong> hay que aclarar que orden lexicográfico significa que se compara letra a letra cada texto. Una letra será menor que otra si está antes en el alfabeto, además, los números también se consideran letras y son menores que todas ellas, es decir, se supone que están antes que la letra 'a' en el alfabeto. </p>
	<p>Por otro lado, la función concat une dos cadenas pero ya hemos visto en multitud de ejemplos que usando el símbolo <strong>+</strong> también podemos conseguir el mismo efecto.</p>

<h2 id="arryasYstrings">Cadenas y arrays de caracteres</h2>
		
		<p>Una variable de tipo <strong>String</strong> representa un texto y consiste en una lista de caracteres a los que, como hemos visto antes, usando la función <strong>charAt</strong> podemos acceder indicando su posición. Un array de caracteres también puede servir para representar un texto pero no podemos concatenar dos arrays de caracteres con la función concat ni usando el símbolo <strong>+</strong>. Por lo tanto, exite cierta relación entre un <strong>String</strong> y un array de caracteres pero hay que tener claro que no son lo mismo. 
		</p>
		<p>iJava incluye dos funciones para pasar de una forma de representar un texto a la otra.</p>
		
		<ul>		
		<li><a href="editor-codigo.php?id=911dad55322a70c4ff99545e9f247253">charArrayToString</a></li>
		<li><a href="editor-codigo.php?id=ce0d24e90364062f11138b30fbd84500">stringToCharArray</a></li>
		</ul>
		
		<p>Haciendo click en los enlaces anteriores podrás ver ejemplos de cada función. En el caso de la función <strong>charArrayToString</strong> hay que aclarar que sólo funciona con arrays de caracteres de una única dimensión pues son los equivalentes a un variable de tipo <strong>String</strong>.</p>

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
