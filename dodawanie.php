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
		$_SESSION['zmiana'] = $cena;
		//czy cena jest pusta lub rowna zero
		if(($cena == "")|| ($cena == 0))
		{
			$wszystko_ok = false;
			$e_cena= '</br>'.'<span style="color:red">Wprowadź cenę (cena nie może być różna od zera)!</span>'.'</br>';
		}
		//czy cena jest nizsza lub równa stanowi konta
		if (($_SESSION['stan_konta'] .$cena)<0)
		{
			$wszystko_ok = false;
			$e_cena2= '</br>'.'<span style="color:red">Cena nie może być większa niz obecny stan konta!</span>'.'</br>';
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
				if($polaczenie->query("INSERT INTO transakcje VALUES (NULL,'$id', '$kategoria', '$cena', '$data', '0' )"))
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
				}
				else 
				{
					throw new Exception($polaczenie->error);
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
						<li><a href="histroria_skarbonki.php">Historia skarbonki</a></li>
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