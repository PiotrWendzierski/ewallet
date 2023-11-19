<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
	header('Location: login.php');
	exit();
}
if(!isset($_SESSION['stan_konta'])|| ($_SESSION['stan_konta'] ==false))
{
	header('Location: index.php');
	exit();
}

require_once("connect.php");
$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
$id = $_SESSION['id'];
 $sql = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wyplyw' ";

$rezultat = mysqli_query($polaczenie,$sql);

$ile_kategorii = $rezultat -> num_rows; 
if($ile_kategorii != 0)
{
	header('Location: index.php');
	exit();
}

?>

<!DOCTYPE HTML>
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
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	<div id = "brak">
	Brak danych! </br>
	<a href="index.php">Wróć</a>
	</div>
</body>

</html>