<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	require_once "connect.php";
	$wszystko_ok = true;
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
	<div id="pole">
		</br></br>Podsumowanie:</br></br>
		<?php
				 echo "Stan przed transakcją: ".$_SESSION['stan']."</br>";
				 echo "Zmieniono o: ".$_SESSION['zmiana']." PLN"."</br></br>";
				 echo "Dnia: ".$_SESSION['data_transakcji']."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';
		?>
		</br><br>
	</div>
</div>
</body>