<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	
	$id = $_SESSION['id'];
	require_once "connect.php";
	$polaczenie = new mysqli ($host, $db_user, $db_password, $db_name);
	$db = mysqli_select_db($polaczenie, "ewallet");
	
	if(isset($_POST['delete']))
	{
		$id_user = $_SESSION['id'];
		$kwota_przeznaczona = $_POST['cena'];
		$data = $_POST['data'];
		
		setcookie('kwota_przeznaczona', $kwota_przeznaczona);
		setcookie('data', $data);
		
		
		
		
	}
	if(isset($_COOKIE['kwota_przeznaczona']))
	{
		$stara_kwota_przeznaczona = $_COOKIE['kwota_przeznaczona'];
		$stara_data = $_COOKIE['data'];
	}
	
	if(isset($_POST['edytuj']))
	{
		$wszystko_ok = true;		
		$nowa_kwota_przeznaczona = $_POST['kwota_przeznaczona'];
		$nowa_data = $_POST['data_transakcji'];
		$query1 = "SELECT * FROM uzytkownicy WHERE id='$id' ";
		$wiersz1 = mysqli_query($polaczenie, $query1);
		$rezultat1 = mysqli_fetch_assoc($wiersz1);
		
		$stan_konta = $rezultat1 ['stan_konta'];
		$stan_skarbonki = $rezultat1['skarbonka'];
		$stan_konta_przed_edytowaniem = $stan_konta + $stara_kwota_przeznaczona;
		//testy wprowadzonyh danych
		//jesli nowowprowadzona kwota bedzie = 0 
		if(($_POST['kwota_przeznaczona'] == "")|| ($_POST['kwota_przeznaczona'] == 0))
		{
			$wszystko_ok = false;
			$e_kwota_przeznaczona = '<span style="color:red">Kwota przeznaczona musi rózna od 0!</span>'.'</br>';
		}
		//jesli nowa kwota bedzie za wysoka
		if($stan_konta_przed_edytowaniem - $nowa_kwota_przeznaczona < 0)
		{
			$wszystko_ok = false;
			$e_kwota_przeznaczona2 = '<span style="color:red">Po wprowadzeniu nowej kwoty, stan konta musi być większy lub równy 0!</span>'.'</br>';
		}
		if($stan_skarbonki - $stara_kwota_przeznaczona + $nowa_kwota_przeznaczona<0)
		{
			$wszystko_ok = false;
			$e_kwota_przeznaczona3 = '<span style="color:red">Po wprowadzeniu nowej kwoty, stan skarbonki musi być większy lub równy 0!</span>'.'</br>';
		}
		
		
		//gdy nie wprowadono daty transakcji
		if($_POST['data_transakcji'] =="")
		{
			$wszystko_ok = false;
			$e_data_transakcji= '</br>'.'<span style="color:red">Wpisz datę transakcji!</span>'.'</br>';
		}
		//gdy data transakcji jest pozniejsza, niz dzisiejsza data 
		$data = $_POST['data_transakcji'];
		$dataczas = new DateTime();
		$koniec = DateTime::createFromFormat('Y-m-d', $data);
		if($dataczas<$koniec)
		{
			$wszystko_ok = false;
			$e_data_transakcji2 = '</br>'.'<span style="color:red">Wpisz datę transakcji dzisiejszą lub z przeszłości!</span>'.'</br>';
		}
		
		if((isset($wszystko_ok))&& ($wszystko_ok == true))
		{
			$stan_konta = $stan_konta_przed_edytowaniem - $nowa_kwota_przeznaczona;
			//zmiana stanu konta uzytkownika;
			$query2 = "UPDATE uzytkownicy SET stan_konta = '$stan_konta' WHERE id = '$id'";
			$query_run11 = mysqli_query($polaczenie, $query2);
			//zmiana w transakcjach skarbonki, odczytanie jednej jedynej transakcji którą chcemy edtować ( zdobycie id tej transakcji)
			$query10 = "SELECT * FROM transakcji_skarbonki WHERE id='$id' AND data_transakcji = '$stara_data' AND kwota_przeznaczona = '$stara_kwota_przeznaczona'";
			$wiersz10 = mysqli_query($polaczenie, $query10);
			$rezultat10 = mysqli_fetch_assoc($wiersz10);
			
			$id_jedynej_transakcji = $rezultat10 ['id_transakcji'];
			
			//edytowanie tej jedynej transakcji
			
			$query3 = "UPDATE transakcji_skarbonki SET data_transakcji = '$nowa_data', kwota_przeznaczona = '$nowa_kwota_przeznaczona' WHERE 
			id_transakcji = '$id_jedynej_transakcji'";
			$query_run22 = mysqli_query($polaczenie, $query3);
			
			//edytowanie obecnego stanu skarbonki
			$stan_skarbonki = $stan_skarbonki - $stara_kwota_przeznaczona + $nowa_kwota_przeznaczona;
			$query4 = "UPDATE uzytkownicy SET skarbonka = '$stan_skarbonki' WHERE id = '$id'";
			$query_run33 = mysqli_query($polaczenie, $query4);
			
			
			if($query_run11)
			{
				if($query_run22)
				{
					if($query_run33)
					{
						header("Location:historia_skarbonki.php");
				}
				}
			}
			else echo "no";
		}
	}
	
	
	
	
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
		<li><a class="rejestraja" href="index2.php"><i class="icon-home"></i>Ekran główny</a></li>
		<li><a class="rejestraja" href="dodawanie.php"><i class="icon-plus-circled"></i>Nowa transakcja</a></li>
		<li><a class="rejestraja" href="historia.php"><i class="icon-history"></i>Historia portfela</a></li>
		<li><a class="rejestraja" href="#"><i class="icon-bank"></i>Skarbonka</a>
					<ul>
						<li><a class="rejestraja" href="skarbonka.php">Dodaj transakcję</a></li>
						<li><a class="rejestraja" href="historia_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
				<li><a class="rejestraja" href="#"><i class="icon-chart-bar"></i>Wykresy</a>
					<ul>
						<li><a class="rejestraja" href="kategorie_wydatkow.php">Kategorie wydatków (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wydatkowprocent.php">Kategorie wydatków (kwotowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywow.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywowprocent.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="formularz">
		
		<form action='' method='post'>
		</br><input type='number' name='kwota_przeznaczona' class="dodawanie" value = <?php if(isset($_POST['cena']))echo $_POST['cena'];?>> </br></br>
		<?php
		if(isset($e_kwota_przeznaczona))
		{
			echo $e_kwota_przeznaczona;
			unset($e_kwota_przeznaczona);
		}
		if(isset($e_kwota_przeznaczona2))
		{
			echo $e_kwota_przeznaczona2;
			unset($e_kwota_przeznaczona2);
		}
		if(isset($e_kwota_przeznaczona3))
		{
			echo $e_kwota_przeznaczona3;
			unset($e_kwota_przeznaczona3);
		}
		?>
		<input type='date' name='data_transakcji' class="dodawanie" value = <?php if(isset($_POST['data']))echo $_POST['data']; ?>>  </br></br>
		<?php
		if(isset($e_data_transakcji))
		{
			echo $e_data_transakcji;
			unset($e_data_transakcji);
		}
		if(isset($e_data_transakcji2))
		{
			echo $e_data_transakcji2;
			unset($e_data_transakcji2);
		}
		?>
		<input type='submit' class="submitrej" name='edytuj' value='ZAOSZCZĘDŹ'>
		
		</form></br>
		
	</div></div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>
</html>

