<?php

	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	
	if($_SESSION['cel_oszczednosci'] == 'brak')
	{
		header('Location: index2.php');
		exit();
	}
	
		if(isset( $_POST['cel_oszczednosci']))
	{
		$wszystko_ok = true;
		
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
						<li><a class="rejestraja" href="kategorie_wplywowprocent.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywow.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="formularz"></br>
		<form name = 'edycja' action = '' method='post'>
			<input type='text' name='cel_oszczednosci'  class="dodawanie" value = <?php echo $_SESSION['cel_oszczednosci'];?>> </br>
			<?php
			if(isset($e_cel_oszczednosci))
			{
				echo $e_cel_oszczednosci;
				unset($e_cel_oszczednosci);
			}
			?>
			</br>
			<input type='number' name='potrzebna_ilosc' class="dodawanie" value = <?php echo $_SESSION['potrzebna_ilosc']; ?>> </br></br>
			
			<?php
			if(isset($e_potrzebna_ilosc))
			{
				echo $e_potrzebna_ilosc;
				unset($e_potrzebna_ilosc);
			}
			?>
			<input type='submit' class="submitrej" value='Zaczynamy'>
			<a href = 'historia.php' ><input type='submit' class="anuluj" name = 'edit' value = 'ANULUJ'>  </a>
		</form>
		
	<?php
	

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
					$cel_oszczednosci = $_POST['cel_oszczednosci'];
					$potrzebna_ilosc = $_POST['potrzebna_ilosc'];
					if($polaczenie->query("UPDATE uzytkownicy SET cel_oszczednosci = '$cel_oszczednosci', potrzebna_ilosc = '$potrzebna_ilosc' WHERE id= '$id'"))
					{
						header('Location: index2.php');
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
	
	</div></div>
</div><div id="footer">Wszelkie prawa zastrzeżone</div>
</body>
</html>