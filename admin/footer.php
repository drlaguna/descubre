    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script>
	$(document).ready(
	function() { // [name='visibility']
		$('[data-toggle="tooltip"]').tooltip()
	});
    </script>
    <script src="js/bootstrap.min.js"></script>
<?php if ( isset($log) && $log ) { ?>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-11629714-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>
<?php }	?>
