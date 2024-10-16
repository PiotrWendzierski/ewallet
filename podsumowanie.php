<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	if(!isset($_SESSION['data_transakcji']))
	{
		header('Location: dodawanie.php');
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
	<link rel="stylesheet"  href="img/fontello-9677cda3/css/fontello.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-571ab779/css/fontello.css" type="text/css" / >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
</head>
<body>
<div id="container">
	<div id="title">
		<a class="rejestracja" href="login.php"><i class="icon-wallet"></i>eWallett</a>
	</div> 
	
	<div id="meni">		
	<ol>
		<li><a class="rejestraja" ><i class="icon-home"></i>Ekran główny</a></li>
		<li><a class="rejestraja" ><i class="icon-plus-circled"></i>Nowa transakcja</a></li>
		<li><a class="rejestraja" ><i class="icon-history"></i>Historia portfela</a></li>
		<li><a class="rejestraja" ><i class="icon-bank"></i>Skarbonka</a>
					<ul>
						<li><a class="rejestraja" >Dodaj transakcję</a></li>
						<li><a class="rejestraja" >Historia skarbonki</a></li>
					</ul>
				</li>
				<li><a class="rejestraja" href="#"><i class="icon-chart-bar"></i>Wykresy</a>
					<ul>
						<li><a class="rejestraja" >Kategorie wydatków (ilościowy)</a></li>
						<li><a class="rejestraja" >Kategorie wydatków (kwotowy)</a></li>
						<li><a class="rejestraja" >Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" >Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" >Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="z" style="min-height:600px;">
	<div id="formularz">
		</br>Podsumowanie:</br></br>
		<?php
				
				 echo "Stan przed transakcją: ".$_SESSION['stan_konta']."</br>";
				 echo "Zmieniono o: ".$_SESSION['zmianaa']." PLN"."</br></br>";
				 echo "Dnia: ".$_SESSION['data_transakcji']."</br></br>";
				 echo '<a href="index2.php"><input type="submit" class="submitrej" value="Wróć na stronę główną"></a>';

				 
				 
		?>
		</br><br>
	</div></div><div id="footer">Wszelkie prawa zastrzeżone
</div>
</body>