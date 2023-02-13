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
			if(($_POST['potrzebna_ilosc'] == 0)|| ($_POST['potrzebna_ilosc'] == ""))
			{
				$wszystko_ok = false;
				$e_potrzebna_ilosc= '</br>'.'<span style="color:red">Wprowadź kwotę inną niż zero!</span>'.'</br>';
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
		//gdy data transakcji jest pozniejsza, niz dzisiejsza data (later dopisać!)
		$_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'];
		$_SESSION['data_transakcji'] = $_POST['data_transakcji'];
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
		<li><a href="skarbonka.php">Skarbonka</a></li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>	
	<div id="pole">
	<?php
	if ($cel_oszczednosci == 'brak')
	{
		echo "</br></br>Wpisz cel twoich oszczędności: </br></br>
		<form method='post'>
			<input type='text' name='cel_oszczednosci'> </br>
			</br>Wpisz ile potrzebujesz pieniędzy: </br></br>
			<input type='number' name='potrzebna_ilosc'> </br></br>
			<input type='submit' value='Zaczynamy'>
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
		echo "</br></br>Wpisz kwotę którą przeznaczasz: </br></br>
					<form method='post'>
						<input type='number' name='kwota_przeznaczona'> </br></br>
						<input type='date' name='data_transakcji'>  </br></br>
						<input type='submit' value='Zaoszczędź'>
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
		if((isset($wszystko_ok)) && ($wszystko_ok == true))
		{
			header('Location: podsumowanie_skarbonki.php');
			$_SESSION['skarbonka'] = $skarbonka;
			$_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'] ;
		}
	 }
	?>
	</div>
</div>
</body>
</html>