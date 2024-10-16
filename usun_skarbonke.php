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
	
	require_once "connect.php";
	if(isset($_POST['yes']))
	{
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
					$brak = "brak";
					$ilosc = 0;
					$sql="UPDATE uzytkownicy SET skarbonka = '0', cel_oszczednosci = '$brak', potrzebna_ilosc = '0' WHERE id='$id'";
					$sql2 = "DELETE FROM transakcji_skarbonki WHERE id='$id'";
					$lacznie = $_SESSION['skarbonka'] + $_SESSION['stan_konta'];
					$sql3 = "UPDATE uzytkownicy SET stan_konta = '$lacznie' WHERE id = '$id'";
					$queryrun = mysqli_query($polaczenie, $sql );
					$queryrun2 = mysqli_query($polaczenie, $sql2 );
					$queryrun3 = mysqli_query($polaczenie, $sql3 );

					
					if($queryrun)
					{
						if($queryrun2)
						{
							if($queryrun3)
							{
								header('Location: index2.php');
								exit();
							}
						}
					}
					
					
				}
		}
		catch (Exception $e)
		{
			echo $e;
		  }
	}
	if(isset($_POST['no']))
	{
		header('Location: index2.php');
		exit();
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
	<div id="z" style="min-height:600px;">
	<div id="formularz"></br>
	<?php
	echo "Czy chcesz usunąć cel zbieraniny? Spowoduje to "."</br>"."trwałe usunięcie wszystkich transakcji i celu oszczędności!";
	
	echo "<form action = '' method = 'post'>
									</br><input type = 'submit' class='edit' name = 'yes' value = 'TAK' >
									<input type = 'submit' class='delete' name = 'no' value = 'NIE' >
				</form>"."</br>";
				
	
	
	?>
	</div></div><div id="footer">Wszelkie prawa zastrzeżone
</div>
</body>
</html>