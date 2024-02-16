<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	//sprawdzanie poprawności wprowadzenia danych w przypadku pierwszego dodanie celu oszczędności
	if (isset($_POST['cel_oszczednosci']))
	{
			$wszystko_ok = true;
			$_SESSION['cel_oszczednosci'] = $_POST['cel_oszczednosci'] ;
			if($_POST['cel_oszczednosci'] == "")
			{
				$wszystko_ok = false;
				$e_cel_oszczednosci = '</br>'.'<span style="color:red">Wprowadź cel skarbonki!</span>'.'</br>';
			}
			if(($_POST['potrzebna_ilosc'] == 0)|| ($_POST['potrzebna_ilosc'] == "")||  ($_POST['potrzebna_ilosc'] < 0))
			{
				$wszystko_ok = false;
				$e_potrzebna_ilosc= '</br>'.'<span style="color:red">Wprowadź kwotę większą niż zero!</span>'.'</br>';
			}
			$_SESSION['cel_oszczednosci'] = $_POST['cel_oszczednosci'];
			$_SESSION['potrzebna_ilosc'] = $_POST['potrzebna_ilosc'];
	}
	//sprawdzenie poprawności danych w przypadku, gdy cel jest już ustawiony i wpisujemy pierwszą i kolejną kwotę przeznaczoną na cel
	if (isset($_POST['kwota_przeznaczona']))
	{
		$wszystko_ok = true;
		$_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'];
		//gdy kowta przeznaczona jest wieksza niz kwota naszego portfela
		if($_POST['kwota_przeznaczona'] > $_SESSION['stan_konta'])
		{
			$wszystko_ok = false;
			$e_kwota_przeznaczona = '</br>'.'<span style="color:red">Kwota przeznaczona musi być niewiększa niż obecny stan portfela!</span>'.'</br>';
		}
		//gdy kwota prenacona jest pusta
		if(($_POST['kwota_przeznaczona'] == "")|| ($_POST['kwota_przeznaczona'] == 0))
		{
			$wszystko_ok = false;
			$e_kwota_przeznaczona2 = '</br>'.'<span style="color:red">Kwota przeznaczona musi rózna od 0!</span>'.'</br>';
		}
		//gdy nie wprowadono daty transakcji
		if($_POST['data_transakcji'] =="")
		{
			$wszystko_ok = false;
			$e_data_transakcji= '</br>'.'<span style="color:red">Wpisz datę transakcji!</span>'.'</br>';
		}
		//gdy data transakcji jest pozniejsza, niz dzisiejsza data 
		$_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'];
		$_SESSION['data_transakcji'] = $_POST['data_transakcji'];
		$data = $_POST['data_transakcji'];
		$dataczas = new DateTime();
		$koniec = DateTime::createFromFormat('Y-m-d', $data);
		if($dataczas<$koniec)
		{
			$wszystko_ok = false;
			$e_data_transakcji2 = '</br>'.'<span style="color:red">Wpisz datę transakcji dzisiejszą lub z przeszłości!</span>'.'</br>';
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
			$id = $_SESSION['id'];
			$sql="SELECT 	* FROM uzytkownicy WHERE id='$id'";
			if($rezultat = $polaczenie->query($sql))
			{
				$wiersz  = $rezultat->fetch_assoc();
				$cel_oszczednosci = $wiersz['cel_oszczednosci'];
				$skarbonka = $wiersz['skarbonka'];
			}
			else 
			{
				throw new Exception($polaczenie->error);
			}
		}
		$polaczenie->close();
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
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
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
						<li><a class="rejestraja" href="kategorie_wplywowprocent.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywow.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="formularz">
	<?php
	if ($cel_oszczednosci == 'brak')
	{
		echo "</br>
		<form method='post'>
			<input type='text' class='dodawanie' name='cel_oszczednosci' placeholder='Wpisz cel zbieraniny' onfocus = 'this.placeholder=''' onblur='this.placeholder='Wpisz cel zbieraniny'> </br>
			</br>
			<input type='number' class='dodawanie' name='potrzebna_ilosc' placeholder='Potrzebna kwota' > </br></br>
			<input type='submit' class='submitrej' value='Zaczynamy'>
		</form>
		</br><br>";
		if(isset($e_cel_oszczednosci))
		{
			echo $e_cel_oszczednosci;
			unset($e_cel_oszczednosci);
		}
		if(isset($e_potrzebna_ilosc))
		{
			echo $e_potrzebna_ilosc;
			unset($e_potrzebna_ilosc);
		}
		if((isset($wszystko_ok)) && ($wszystko_ok == true))
		{
			header('Location: podsumowanie_skarbonki.php');
			$_SESSION['cel_oszczednosci'] = $_POST['cel_oszczednosci'];
			$_SESSION['potrzebna_ilosc'] = $_POST['potrzebna_ilosc'];
			$_SESSION['skarbonka'] = $skarbonka;
		}
	}
	else 
	{
		echo "</br>
					<form method='post'>
						<input type='number' class='dodawanie' name='kwota_przeznaczona' placeholder = 'Wpisz kwotę' onfocus = 'this.placeholder=''' onblur='this.placeholder='Wpisz kategorię'> </br></br>
						<input type='date' class='dodawanie' name='data_transakcji'>  </br></br>
						<input type='submit' class='submitrej' value='Zaoszczędź'>
					</form>
					</br><br>";
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
		if(isset($e_data_transakcji))
		{
			echo $e_data_transakcji;
			unset($e_data_transakcji);
		}
		if(isset($e_data_transakcji2))
		{
			echo ($e_data_transakcji2);
			unset($e_data_transakcji2);
		}
		if((isset($wszystko_ok)) && ($wszystko_ok == true))
		{
			$_SESSION['skarbonka'] = $skarbonka;
			$_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'] ;
			$_SESSION['data_transakcji_skarbonki'] = $_POST['data_transakcji'];
			$_SESSION['stan_konta'] = $_SESSION['stan_konta'] - $_SESSION['kwota_przeznaczona'] ;
			header('Location: podsumowanie_skarbonki.php');
		}
	 }
	?>
	</div></div>
</div><div id="footer">Wszelkie prawa zastrzeżone</div>
</body>
</html>