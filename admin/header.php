<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content=<?php echo '"'.$description.'"'; ?> />
	<meta name="author" content="Facultad de Inform�tica, Universidad de Murcia"  />
	<meta name="language" content="Spanish" />	
<?php
	if ( $ijava ) {
?>
	<script src="https://cdn.jsdelivr.net/npm/tone@14.7.77/build/Tone.js" type="text/javascript"></script>
	<script src="js/ijava/ij_lexer.js" type="text/javascript"></script>
	<script src="js/ijava/ij_parser.js" type="text/javascript"></script>
	<script src="js/ijava/ij_funcs.js" type="text/javascript"></script>
	<script src="js/ijava/iJavaCompiler.js" type="text/javascript"></script>                
	<script src="js/ijava/iJavaEditor.js" type= "text/javascript"></script>    
	<script src="js/ijava/iJavaViewer.js" type= "text/javascript"></script>    
	
	<link rel="stylesheet" href="css/codemirror.css">
	<link rel="stylesheet" href="css/lint.css">
	<link rel="stylesheet" href="css/neat.css">
	<link rel="stylesheet" href="js/vendor/spectrum/spectrum.css">
	<link rel="stylesheet" href="css/ijava.css">

	<script src="js/vendor/codemirror/codemirror.js"></script>
	<script src="js/vendor/codemirror/matchbrackets.js"></script>
	<script src="js/vendor/codemirror/closebrackets.js"></script>
	<script src="js/vendor/codemirror/active-line.js"></script>
	<script src="js/vendor/codemirror/ijava.js"></script>
	<script src="js/vendor/codemirror/lint.js"></script>
<?
	}
?>

<link rel="stylesheet" href="css/morris.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="js/vendor/raphael-2.1.2/raphael-min.js"></script>
<script src="js/vendor/morris-0.5.2/morris.min.js"></script>
<script src="js/vendor/highlight/highlight.pack.js"></script>


    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/glyphicon.css">
	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">

	<link href="js/vendor/highlight/styles/vs.css" rel="stylesheet">
	
	<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.es.min.js"></script>
	<script type="text/javascript" src="js/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
	<script src="js/vendor/sortable-1.4.2/Sortable.min.js"></script>
	<script type="text/javascript" src="js/md5.js"></script>
	<script type="text/javascript" src="js/descubre.js"></script>
<?php
	if ( $ijava ) {
?>
	<script type="text/javascript" src="js/vendor/spectrum/spectrum-1.4.js"></script>
<?
	}
?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
body {
	background: #FFF7E2; 
}

/* navbar */
.navbar {
	background: #0C3844;		
	border:none;
	height:60px;
}
/* title */
.navbar .navbar-brand {
    color: #FFF;
	padding:0;
	margin:0;
}
.navbar .navbar-brand:hover,
.navbar .navbar-brand:focus {
    color: #FFF;
}
/* link */
.navbar .navbar-nav > li > a {
    color: #EEE;
    background: #0C3844;
    z-index:100;
	font-size:16px;
	padding-top:25px;
}
.navbar .navbar-nav > li > a:hover,
.navbar .navbar-nav > li > a:focus {
    color: #EEE;
    text-shadow: 1px 1px #000;
    background-color: #9F69A2;
    height:64px;
}
.navbar .navbar-nav > .active > a, 
.navbar .navbar-nav > .active > a:hover, 
.navbar .navbar-nav > .active > a:focus {
    color: #EEE;
    text-shadow: 1px 1px #000;
    background-color: #8B3290; /* #27b2dd; */
    height:64px;
}
.navbar .navbar-nav > .open > a, 
.navbar .navbar-nav > .open > a:hover, 
.navbar .navbar-nav > .open > a:focus {
    color: #EEE;
    text-shadow: 1px 1px #000;
    background-color: #27b2dd;
    height:64px;
}
/* caret */
.navbar .navbar-nav > .dropdown > a .caret {
    border-top-color: #777;
    border-bottom-color: #777;
}

.carousel-fade .carousel-inner .item {
  opacity: 0;
  -webkit-transition-property: opacity;
  -moz-transition-property: opacity;
  -o-transition-property: opacity;
  transition-property: opacity;
}
.carousel-fade .carousel-inner .active {
  opacity: 1;
}
.carousel-fade .carousel-inner .active.left,
.carousel-fade .carousel-inner .active.right {
  left: 0;
  opacity: 0;
  z-index: 1;
}
.carousel-fade .carousel-inner .next.left,
.carousel-fade .carousel-inner .prev.right {
  opacity: 1;
}
.carousel-fade .carousel-control {
  z-index: 2;
}
	.carousel h3 {
		color: #9D0523;
		background-color:rgba(240,240,240, 0.95);
		padding:8px 8px;
		text-align: right;
		font-size: 20px;
	}
	
	carousel p {
		color: #9D0523;
		background-color:rgba(240,240,240, 0.95);
	}
	
	.carousel-inner > .item > img {
	  margin: 0 auto;
	  max-width:100%;
	  height:auto;
	}

/**** EITK ****/
@media all and (transform-3d), (-webkit-transform-3d) {
    .carousel-fade .carousel-inner > .item.next,
    .carousel-fade .carousel-inner > .item.active.right {
      opacity: 0;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
    .carousel-fade .carousel-inner > .item.prev,
    .carousel-fade .carousel-inner > .item.active.left {
      opacity: 0;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
    .carousel-fade .carousel-inner > .item.next.left,
    .carousel-fade .carousel-inner > .item.prev.right,
    .carousel-fade .carousel-inner > .item.active {
      opacity: 1;
      -webkit-transform: translate3d(0, 0, 0);
              transform: translate3d(0, 0, 0);
    }
}
/* Footer --------------------------------------------------------------------------- */

.footer {
	background-color: #0c3844;
	font-size:0.9em;
	color: #FFF;
	height:192px;
	padding-top:16px;
}
.footer a {
	color: #fff;
}



<?php
	if ( $ijava ) {
?>	
	.CodeMirror {
		float: left;		
		font-size: 16px;
		border: 1px solid #aaa;
	}
	#mycanvas {
		background: #eee;		
		width:  100%;
		padding: 0px;
		margin: 0px;	
			
		border: 1px solid #aaa;
	}
	#mycanvas:focus { 
		outline:none;
	}
	#mycanvas::-moz-focus-inner { 
		border: none; 
		}
	#output:focus { 
		outline: none; 
	}
	#output::-moz-focus-inner { 
		border: none; 
		}
<?
	}
?>
</style>
</head>
<body>
