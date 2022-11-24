<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
?>

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
		<div class="option"><a href="index2.php">Ekran główny</a></div>
		<div class="option"><a href="dodawanie.php">Wprowadzanie transakcji</a></div>
		<div class="option"><a href="historia.php">Historia portfela</a></div>
		<div class="option"><a href="skarbonka.php">Skarbonka</a></div>
		<div class="option"><a href="wyloguj.php">Wyloguj</a></div>
		<div style="clear:both;"></div>
	</div>
	<div id="dashboard">
	<?php
	//	echo $_SESSION['transakcja'][1]['cena'];
	?>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>