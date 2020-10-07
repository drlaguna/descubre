<!-- Static navbar -->
<?php 
	if ( isset( $_SESSION['user'] ) && $_SESSION['user']->isRegistered( ) && $_SESSION['user']->isAdmin() && false ) {
?>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div id="admin-menu" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Crear <span class="caret"></span></a>
              <ul class="dropdown-menu">
		         <li><a href='edit.php?pagina=-1'>Página</a></li>
		         <li><a href='edit.php?recurso=-1'>Recurso</a></li>
		         <li><a href='edit.php?noticia=-1'>Noticia</a></li>
		         <li><a href='edit.php?usuario=-1'>Usuario</a></li>
              </ul>
            </li>
		    <li><a href='index.php?seccion=pagesmngr'>Páginas</a></li>
		    <li><a href='index.php?seccion=resourcesmngr'>Recursos</a></li>
		    <li><a href='index.php?seccion=newsmngr'>Noticias</a></li>
		    <li><a href='index.php?seccion=usersmngr'>Usuarios</a></li>
          </ul>
        </div>
      </div>
    </nav>  
<?php 
	}
?>

 <!-- Static navbar -->

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo DESCUBRE_URL; ?>">
		<img alt="Descubre la programación" src="images/descubre_logo.png">
<!--      	
 -->
      </a>
<!--	  
 -->
	<p class="navbar-text navbar-left">
	  <a href="mailto:descubrelainformatica@um.es" class="navbar-link"><img alt="" src="images/email-recto.png" ></a>
	  <a href="https://twitter.com/descubreinfum" class="navbar-link"><img alt="" src="images/twitter.png" ></a>
	 </p>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="navbar-collapse collapse" id="main-menu" >


      <ul class="nav navbar-nav navbar-right">
<?php 
if ( $_SESSION['user']->isRegistered() ) { 
?>
        <li <?php if ($active == "perfil") echo 'class="active"'; ?> data-toggle="tooltip" data-placement="bottom" title="Consulta tu perfil y tus programas"><a href="perfil.php?id=<?php echo $_SESSION['user']->getMD5Key(); ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp; <?php echo $_SESSION['user']->getUsername(); ?><span class="sr-only">(current)</span></a></li>
<?php
}
?>
        <li <?php if ($active == "explora") echo 'class="active"'; ?>><a href="explora.php">Explora <span class="sr-only">(current)</span></a></li>
        <li <?php if ($active == "aprende") echo 'class="active"'; ?>><a href="aprende.php">Aprende</a></li>
        <li <?php if ($active == "crea") echo 'class="active"'; ?>><a href="editor-codigo.php">Crea</a></li>
        <li <?php if ($active == "retos") echo 'class="active"'; ?>><a href="retos.php">Retos</a></li>
<?php
	if ( isset( $_SESSION['user'] ) && $_SESSION['user']->isRegistered( ) ) {
		$userURL = "logout.php?origin=".urlencode( currentURL() );
?>        
		<li class="" data-toggle="tooltip" data-placement="bottom" title="Desconectar"><a href="<?php echo $userURL; ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
<?php
	} else {
		$userURL = "login.php?origin=".urlencode( currentURL() );
?>
		<li class="" data-toggle="tooltip" data-placement="bottom" title="Acceder"><a href="<?php echo $userURL; ?>"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a></li>
<?php
	}
?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>