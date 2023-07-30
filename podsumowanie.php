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
	<ol>
		<li><a href="index2.php">Ekran główny</a></li>
		<li><a href="dodawanie.php">Nowa transakcja</a></li>
		<li><a href="historia.php">Historia portfela</a></li>
		<li><a href="#">Skarbonka</a>
					<ul>
						<li><a href="skarbonka.php">Dodaj transakcję</a></li>
						<li><a href="histroria_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	<div id="pole">
		</br></br>Podsumowanie:</br></br>
		<?php
				
				 echo "Stan przed transakcją: ".$_SESSION['stan_konta']."</br>";
				 echo "Zmieniono o: ".$_SESSION['zmiana']." PLN"."</br></br>";
				 echo "Dnia: ".$_SESSION['data_transakcji']."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';

				 
				 
		?>
		</br><br>
	</div>
</div>
</body>