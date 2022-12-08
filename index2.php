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
	require_once "connect.php";
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
		<div class="option"><a href="index2.php">Ekran główny</a></div>
		<div class="option"><a href="dodawanie.php">Wprowadzanie transakcji</a></div>
		<div class="option"><a href="historia.php">Historia portfela</a></div>
		<div class="option"><a href="skarbonka.php">Skarbonka</a></div>
		<div class="option"><a href="wyloguj.php">Wyloguj</a></div>
		<div style="clear:both;"></div>
	</div>
	<?php
		echo "Witaj ".$_SESSION['user']." w swoim wirtualnym portfelu!</br></br>";
			  try
			{
				$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
				if($polaczenie->connect_errno!=0)
			  {
					throw new Exception(mysqli_connect_errno());
			    }
				else
				{
					$user = $_SESSION['user'];
					$sql="SELECT 	stan_konta FROM uzytkownicy WHERE login='$user'";
					if($rezultat = $polaczenie->query($sql))
					{
						$wiersz  = $rezultat->fetch_assoc();
						$stan_konta = $wiersz['stan_konta'];
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
					$polaczenie->close();
				}
			  }
			  catch (Exception $e)
			  {
					echo $e;
			  }
			
	?>
	<div id="dashboard">
		<div class="kafel">
		<?php
			echo "Obecny stan portfela"."</br></br>";
			echo $stan_konta;
		?>
		</div>
		<div class="kafel">
		Ostatnia transakcja: </br></br>
		<?php
			if(isset($_SESSION['data_transakcji']))
		  {
				echo "Dnia: ".$_SESSION['data_transakcji']."</br>";
				echo $stan_konta;
			}
			else 
		  {				
				echo "Brak danych";
			}
		?>
		</div>
		<div class="kafel">
		Ilość transakcji:
		</div>
		<div class="kafel">
		Łączny majątek:
		</div>
		<div style="clear:both"></div>
		<div class="kafel_duzy">
		</br>Skarbonka </br></br>
		<?php
			if((isset($_SESSION['skarbonka']))&&($_SESSION['oszczednosci'] == false))
		  {
				echo "Cel zbieraniny: ".$_SESSION['cel_oszczednosci']."</br></br>";
				echo "Potrzebujesz: ".$_SESSION['potrzebna_ilosc']."</br>";
				echo "Masz już: 0zł"."</br></br>";
				echo "Łącznie: 0%";
			}
			else if((isset($_SESSION['skarbonka']))&&($_SESSION['oszczednosci'] == true))
			{
				$_SESSION['stan_skarbonki'] =$_SESSION['stan_skarbonki'] + $_SESSION['kwota_przeznaczona'];
				$stan_skarbonki = $_SESSION['stan_skarbonki'] ;
				$procent_celu = ($_SESSION['stan_skarbonki']/$_SESSION['potrzebna_ilosc'])*100;
				echo "Łącznie: ".$stan_skarbonki."</br>";
				echo "Masz już: ".$procent_celu."%</br></br>";
				echo "Cel zbieraniny: ".$_SESSION['cel_oszczednosci']."</br></br>";
				$_SESSION['kwota_przeznaczona'] = 0;
				$pozostalo = $_SESSION['potrzebna_ilosc'] - $stan_skarbonki;
				echo "Pozostało do uzbierania: ".$pozostalo;
			}
			else 
		  {				
				echo "Narazie brak celów";
			}
		?>
		</div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>