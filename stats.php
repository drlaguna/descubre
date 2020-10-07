<?php
include_once("common.php");

if ( ! $_SESSION['user']->isAdmin() ) {
	header("location:index.php" );
	return;
}

function tabla($role) {
	if ( $role != "" ) $role = "U.ROLE = '".$role."' AND "; 
?>
		<table class="table table-striped table-bordered">
<?php
			echo "<thead><tr><th>Curso</th><th>Altas</th>";
	for ( $yf = 2014 ; $yf < date("Y") ; $yf++ ) {
		$yf2 = $yf+1;
		echo "<th>$yf-$yf2</th>";
	}
			echo "</tr></thead>";
			echo "<tbody>";
	for ( $yf = 2014 ; $yf < date("Y") ; $yf++ ) {
?>
			<tr>
<?php		
		$altas = $_SESSION['bbdd']->queryValue("SELECT COUNT(*) FROM USER_2 AS U WHERE ".$role." U.SIGNUPDATE >= '".$yf."-09-01' AND U.SIGNUPDATE <= '".($yf+1)."-10-31';");
		$yf2 = $yf+1;
		echo "<td>$yf-$yf2</td>";
		echo "<td>$altas</td>";
		for ( $yt = 2014 ; $yt < date("Y") ; $yt++ ) {
			$total = $_SESSION['bbdd']->queryValue("select count(distinct(E.USER)) from EVENT_2 AS E, USER_2 AS U WHERE ".$role." E.USER = U.ID AND SIGNUPDATE >= '".$yf."-09-01' AND SIGNUPDATE <= '".($yf+1)."-10-31' AND MOMENT >= '".$yt."-09-01' AND MOMENT <= '".($yt+1)."-10-31' AND WHAT='login';");
			if ( $yt >= $yf ) {
?>
			<td><?php echo $total; ?></td>
<?php
			} else {
				echo "<td></td>";
			}
			
		}
?>
			</tr>
<?php		
	}
?>
		</tbody>
	</table>
<?php
}

makeHeader("Descubre la Programación : Stats ", "", "log", false);

?>

<div class="container-fluid">
	<div class="col-md-12">
	<h4>Usuarios activos por curso académico (incluidos no identificados)</h4>
	<?php tabla("");?>
	<h4>Estudiantes activos por curso académico </h4>
	<?php tabla("estudiante");?>
	<h4>Profesores activos por curso académico </h4>
	<?php tabla("profesor");?>


	</div>
</div>
<?php
	makeFooter();
?>
</body>
<script>
</script>
</html>
