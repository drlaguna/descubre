<?php
include_once("common.php");

makeHeader("Descubre la Programación", "Explora, aprende y crea tus propios programas", "", false);
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-md-6">

			<div id="carousel-example-generic" class="carousel slide carousel-fade" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
			    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="5"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="6"></li>
			    <li data-target="#carousel-example-generic" data-slide-to="7"></li>
			  </ol>
			
			  <!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
			    <div class="item active">
			      <img src="images/carousel/p1.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;Creo que todo el mundo debería aprender a programar porque te enseña a pensar.&rdquo;</h3>
			      	<p>&mdash; Steve Jobs</p>
			      </div>
			    </div>
			
			    <div class="item">
			      <img src="images/carousel/p2.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;Tener conocimientos básicos de programación es una habilidad esencial en el siglo 21.&rdquo;</h3>
			      	<p>&mdash; Stephen Hawking</p>
			      </div>
			    </div>
			
			    <div class="item">
			      <img src="images/carousel/p3.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;En EEUU se necesitan 120000 nuevos programadores cada año.&rdquo;</h3>
			      	<p>&mdash; Bill Clinton</p>
			      </div>
			    </div>
			
			    <div class="item">
			      <img src="images/carousel/p4.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;Aprender a programar agudiza tu mente y te ayuda a pensar mejor.&rdquo;</h3>
			      	<p>&mdash; Bill Gates</p>
			      </div>
			    </div>
			    
			    <div class="item">
			      <img src="images/carousel/p5.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;Aprender a programar es excitante, y muy divertido. En muy poco tiempo serás capaz de hacer cosas increibles.&rdquo;</h3>
			      	<p>&mdash; Dr. John Hennessy</p>
			      </div>
			    </div>
			    
			    <div class="item">
			      <img src="images/carousel/p6.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;Saber cómo programar ordenadores puede ser tu mejor oportunidad para encontrar empleo.&rdquo;</h3>
			      	<p>&mdash; Steve Ballmer</p>
			      </div>
			    </div>

			    <div class="item">
			      <img src="images/carousel/p7.jpg" alt="...">
			      <div class="carousel-caption">
			      	<h3>&ldquo;En Facebook tratamos de contratar la mayor cantidad posible de ingenieros con talento que podamos.&rdquo;</h3>
			      	<p>&mdash; Mark Zuckerberg</p>
			      </div>
			    </div>

			  </div>
			
			  <!-- Controls -->
			  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
		</div>
		<div class="col-md-6">
			<div style="color: #1b5563;">
				<h3>En esta web podrás aprender programación paso a paso junto a tus amigos, y casi sin darte cuenta</h3>
			</div>
			
			
			<a href="editor-usuario.php?id=-1" type="button" class="btn btn-primary btn-block">
				<span class="glyphicon glyphicon-ok" aria-hidden="true" ></span> Inscríbete
			</a>
			&nbsp;
			<p><a href="el-proyecto.php">Conoce más sobre el proyecto</a></p>
<!-- --			
			<div style="color: #1b5563;">
				<h3>Apúntate ya a la Olimpida Informática de la Región de Murcia 2019</h3>
				<a href="http://olimpiada.inf.um.es"><img src="http://olimpiada.inf.um.es/images/titulo-oirm-19.png" width=320></a>
			</div>
!-- -->					
		</div>
	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
$(document).ready(
function() {
	$('.carousel').carousel({
	  interval: 5000
	})
});
</script>
</html>
