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
	
	<div id="pole">
	<?php
	if (!isset($_SESSION['skarbonka'] ))
	{
		echo "</br></br>Wpisz cel twoich oszczędności: </br></br>
		<form action='podsumowanie_skarbonki.php' method='post'>
			<input type='text' name='cel_oszczednosci'> </br>
			</br>Wpisz ile potrzebujesz pieniędzy: </br></br>
			<input type='number' name='potrzebna_ilosc'> </br></br>
			<input type='submit' value='Zaczynamy'>
		</form>
		</br><br>";
		$_SESSION['skarbonka'] = true;
		$_SESSION['stan_skarbonki'] = 0;
		$_SESSION['kwota_przeznaczona'] = 0;
		$_SESSION['oszczednosci'] = false;
	}
	else 
	{
		echo "</br></br>Wpisz kwotę którą przeznaczasz: </br></br>
					<form action='podsumowanie_skarbonki.php' method='post'>
						<input type='number' name='kwota_przeznaczona'> </br></br>
						<input type='date' name='data_transakcji'>  </br></br>
						<input type='submit' value='Zaoszczędź'>
					</form>
					</br><br>";
					$_SESSION['oszczednosci'] = true;
	}
	?>
	</div>
</div>
</body>

</html>