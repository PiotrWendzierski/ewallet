<?php
//s
session_start();
if(!isset($_SESSION['zalogowany']))
{
	header('Location: login.php');
	exit();
}
if($_COOKIE['i'] != 0)
{
	header('Location: login.php');
	exit();
}
?><!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
						<li><a href="historia_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="#">Wykresy</a>
					<ul>
						<li><a href="kategorie_wydatkow.php">Kategorie wydatków (ilościowy)</a></li>
						<li><a href="kategorie_wydatkowprocent.php">Kategorie wydatków (kwotowy)</a></li>
						<li><a href="kategorie_wplywowprocent.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a href="kategorie_wplywow.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	<div id = "brak">
	<form action="index2.php" method = "POST">
			<?php
			if((isset($_COOKIE['i'])) && ($_COOKIE['i'] == 0))
			{
				setcookie('i', 1);
				echo '<span style="color:red">Brak wydatków w tym okresie!</span>'.'</br>';
			}
			
			?>
			<input type="submit" value="Wróć"></input>
	</form>
	</div>
</body>

</html>