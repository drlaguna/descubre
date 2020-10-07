<?php
	function dateRange($from, $to) {
		$list = array();
		while ($from <= $to) {
			$list[] = $from;
			$from += (24*60*60);
		}
		return $list;
	}

	function dayOfWeek($date) {
		$day = date("w", $date);
		if ($day == 0) $day = 7;
		return $day-1;
	}
	
	function replaceurl($url, $opage, $npage) {
		$transform = array (
				$opage => $npage);
		return strtr($url, $transform);
	}
	
	function splitemails($emails) {
		// path to 'MAIL.php' file from XPM4 package
		require_once 'mail/MAIL.php';
		$emails = str_replace(";", ",", $emails);
		$list = split('[ ,]', $emails);
		$destinations = array();
		for ($i = 0 ; $i < sizeof($list) ; $i++ ) {
			if (!in_array($list[$i], $destinations) && $list[$i]!="" && FUNC::is_mail($list[$i], false)) $destinations[] = $list[$i];
		}
		$str = '';
		if (sizeof($destinations) > 0 && $destinations[0]!="") $str = $destinations[0];
		for ( $i = 1 ; $i < sizeof($destinations) ; $i++ ) if ($destinations[$i] != "") $str = $str.', '.$destinations[$i];
		return $str;
	}
	
	// NO enviar mas de 250 mensajes cada 5 minutos
	
	function sendHTMLmail($to, $subject, $msg, $from="", $bcc="", $replyTo="") {
        // path to 'MAIL.php' file from XPM4 package
				// turn on, recommended for debugging mode
				define('DISPLAY_XPM4_ERRORS', true);
				// or turn off, recommended for production mode
				// define('DISPLAY_XPM4_ERRORS', false);
        require_once 'mail/MAIL.php';
        
        $destinations = explode(',', $to);

        for ($i = 0 ; $i < sizeof($destinations) ; $i++ ) {
//        	echo "Enviando a [".$destinations[$i]."]<br/>";
        	$dest = trim($destinations[$i]);
	        $m = new MAIL; // initialize MAIL class
	        if (!FUNC::is_mail($dest, false)) {
//	        	echo "Erronea<br/>";
	        	continue;
	        }
	        $m->From($from); // set From mail address  
        	$m->AddTo($dest); // add To mail address
	        if ($replyTo != "") {
	        	$m->AddHeader('Reply-To', $replyTo);
	        }
	        $m->Subject($subject, "utf-8"); // set your mail subject
	        // set your mail message (text/html)
	        $m->Html($msg, "utf-8");
//	        $m->Html($msg, "iso-8859-1");
	
	        // connect to MTA server 'smtp.gmail.com' port '465' via SSL ('tls' encryption)
	        // with authentication: 'username@gmail.com' and 'password'
	        // set the connection timeout to 10 seconds, the name of your
	        // host 'localhost' and the authentication method to 'plain'
	        // make sure you have OpenSSL module (extension) enable on your php configuration
	        $c = $m->Connect('smtp.um.es', 25);
	        
	
	        // send mail relay using the '$c' resource connection
	        $result = $m->Send($c);
//					echo "Resultado = ".$result."<br/>";
	        // disconnect from server
	        $m->Disconnect();
        }
        return $result;
	}
	// http://stackoverflow.com/questions/14762492/preg-replace-converting-line-breaks-into-paragraphs
	function formatBody( $msg ) {
		// Normalize line endings
	  // Convert all line-endings to UNIX format
	  $msg = str_replace("\r\n", "\n", $msg);
	  $msg = str_replace("\r", "\n", $msg);
	  // Don't allow out-of-control blank lines
	  $msg = preg_replace("/\n{2,}/", "\n\n", $msg);
		$msg = preg_replace('/\n{2,}/', "</p><p>", trim($msg));
		$msg = preg_replace('/\n/', '<br>',$msg);
		$msg = "<p>{$msg}</p>";
		$body = '<html>
		<head>
		<meta charset="UTF-8">
		<title>Template</title>
		</head>
		<body style="font-family:Gotham, Helvetica, Arial, sans-serif; font-size:18px; background: #FFF; color:#092130;">
		<div style="padding:0 80px; margin-left:auto; margin-right:auto;  min-width:520px;">
			<div style="background-image:url(header_bg.png); background-repeat:repeat-x;">
					<a href="http://descubre.inf.um.es"><img src="http://descubre.inf.um.es/images/descubre_logo.png" width="267" height="68"  alt="Descubre la Programación"/></a>
			</div>
			<div style="overflow:hidden; background-color: #fff7e2; ">
				<div style="padding:0 20px;">'.$msg.
			 '</div>
				<div style="background-color:#1b5463;">
					<div style="padding:0 20px;">
						<a href="mailto:descubrelainformatica@um.es"><img src="http://descubre.inf.um.es/images/email-recto.png"></a>
						<a href="https://twitter.com/descubreinfum" target="_blank"><img src="http://descubre.inf.um.es/images/twitter.png"></a>																																			<p style=" font-size: 15px; color: #82b9b8; margin: 10px 0 6px 0; float: right; display: block; width: 320; height:20px;"><a style="text-decoration:none; color: #82b9b8;" href="http://www.um.es/informatica">Facultad de Informática</a> | <a style="text-decoration:none; color: #82b9b8;" href="http://www.um.es/">Universidad de Murcia</a></p>
					</div>
				</div>
			</div>	
		</div>
		</body>
		</html>';
		return $body;
	}
?>
