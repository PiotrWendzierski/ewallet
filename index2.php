<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
</head>
<body>
<div id="container">
	<div id="title">
		eWallett
	</div>
	<div id="meni">		
		<a href="dodawanie.php">+</a>
	</div>
	<div id="dashboard">
		<div class="kafelek">
		<?php
			echo "Obecny stan portfela"."</br></br>";
			$stan = $_POST['stan'];
			echo $stan." zł";
		?>
		</div>
		<div class="kafelek">2</div>
		<div style="clear:both"></div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>