<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	
	if (isset($_POST['kategoria']))
  {
		$wszystko_ok = true;
		//czy jest podana kategoria
		$kategoria = $_POST['kategoria'];
		if ($kategoria == "")
		{
			$wszystko_ok = false;
			$e_kategoria = '</br>'.'<span style="color:red">Wprowadź kategorię!</span>'.'</br>';
		}
		$cena = $_POST['cena'];
		$_SESSION['zmianaa'] = $cena;
		//czy cena jest pusta lub rowna zero
		if(($cena == "")|| ($cena == 0))
		{
			$wszystko_ok = false;
			$e_cena= '</br>'.'<span style="color:red">Wprowadź cenę (cena nie może być równa  zeru)!</span>'.'</br>';
		}
		//czy cena jest nizsza lub równa stanowi konta
		if (($_SESSION['stan_konta'] + $cena)<0)
		{
			$wszystko_ok = false;
			$e_cena2= '</br>'.'<span style="color:red">Cena nie może być większa niz obecny stan konta!</span>'.'</br>';
			if($cena == "")
			{
				$wszystko_ok = false;
				$e_cena2 =  '</br>'.'<span style="color:red">Cena nie może być pusta!</span>'.'</br>';
			}
		}
		
		//czy wpisana jest data
		$data = $_POST['data_transakcji'];
	    $_SESSION['data_transakcji'] = $data;
		if($data == "")
		{
			$wszystko_ok = false;
			$e_data = '</br>'.'<span style="color:red">Wprowadź datę transakcji!</span>'.'</br>';
		}
		//czy data nie jest z przeszłości
		$dataczas = new DateTime();
		$koniec = DateTime::createFromFormat('Y-m-d', $data);
		if($dataczas<$koniec)
		{
			$wszystko_ok = false;
			$e_data2 = '</br>'.'<span style="color:red">Wprowadź datę dzisiejszą lub z przeszłości!</span>'.'</br>';
		}
	}
	require_once "connect.php";
	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			if((isset($wszystko_ok))&& ($wszystko_ok == true))
			{
				$id = $_SESSION['id'];
				//wkładanie do tabeli transakcje rekordu
				$zmiana = "wplyw";
				if($cena < 0)$zmiana = "wyplyw";
				if($polaczenie->query("INSERT INTO transakcje VALUES (NULL,'$id', '$kategoria', '$cena', '$data', '0' , '$zmiana')"))
				{
					$stanek = $_SESSION['stan_konta'] + $cena;
					$user = $_SESSION['user'];
					if ($polaczenie->query("UPDATE uzytkownicy SET stan_konta = $stanek WHERE login = '$user'" ))
					{
						header('Location: podsumowanie.php');
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
				 //wkładanie do tabeli kategorie nazwy nowej kategorii, tutaj dowiadujemy się czy dana kategoria juz istnieje czy jeszcze nie 
				 $rezultat = $polaczenie ->query("SELECT * FROM kategorie WHERE kategoria='$kategoria' AND id='$id' AND wplywwyplyw = '$zmiana'");
				 //tu ponizej mamy odpowiedz, czy w bazie KATEGORIE jest juz taka kategoria, jaką w tym momencie w tej konkretnej transakcji wprowadza uzytkownik;
				 $ilosc_kategorii = $rezultat->num_rows;
				 
				 $_SESSION['ilosc_kategorii'] = $ilosc_kategorii ;
				 if(!$rezultat)throw new Exception($polaczenie->error);
				 else
				 {
					 if($ilosc_kategorii <1)
					 {
						 $rezultat = $polaczenie -> query("INSERT INTO kategorie VALUES (NULL, '$kategoria', '$id', 1, '$zmiana')");
					 }
					 else //tu jeśli dodana transakcja ma juz swoją kategorięa
					 {
						 //teraz w bazie "kategorie" sprawdzamy ile transakcji w było w tej jednej konkretnej kategorii i w podsumowanie.php
						 //będziemy dodawać za pomocą UPDATE kolejną transakcje, zeby np z 4 zwiększyło się na 5 transakcji tej konkretnej kategorii; 
						 $rezultat = $polaczenie->query("SELECT * FROM kategorie WHERE kategoria = '$kategoria' AND id = '$id' AND wplywwyplyw = '$zmiana'");
						 $row = $rezultat -> fetch_assoc();
						 $_SESSION['ilosc_transakcji'] = $row['ilosc_transakcji'];
						 $_SESSION['ilosc_transakcji'] = $_SESSION['ilosc_transakcji']+1;
						 $ilosc_transakcji = $_SESSION['ilosc_transakcji'] ;
						 $kategoria = $row['kategoria'];
						 if( $polaczenie ->query("UPDATE kategorie SET ilosc_transakcji = '$ilosc_transakcji'  WHERE kategoria='$kategoria' AND id='$id' AND wplywwyplyw = '$zmiana' "))
						 {
							 ;
						 }
						 
					 }
				 }
				}
			}
			$polaczenie->close();
		}
	}
	catch (Exception $e)
	{
		echo $e;
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - dodaj transakcję</title>
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
	</br></br>
	<div id="formularz" >
		<form method="post"  >
			Wpisz kategorię<input type="text" name="kategoria"> </br>
			<?php
				if(isset($e_kategoria))
				{
					echo $e_kategoria;
					unset ($e_kategoria);
				}
			?></br>
			Wpisz cenę<input type="number" name="cena">  </br>
			<?php
				if(isset($e_cena))
				{
					echo $e_cena;
					unset($e_cena);
				}if(isset($e_cena2))
				{
					echo $e_cena2;
					unset($e_cena2);
				}
			?></br>
			Data <input type="date" name="data_transakcji">  </br>
			<?php
			if(isset($e_data))
				{
					echo $e_data;
					unset($e_data);
				}
			if(isset($e_data2))
			{
				echo ($e_data2);
				unset ($e_data2);
			}
			?></br>
			Zatwierdź <input type="submit">
		</form>

	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>