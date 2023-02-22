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
	<ol>
		<li><a href="index2.php">Ekran główny</a></li>
		<li><a href="dodawanie.php">Nowa transakcja</a></li>
		<li><a href="historia.php">Historia portfela</a></li>
		<li><a href="#">Skarbonka</a>
					<ul>
						<li><a href="skarbonka.php">Dodaj transakcję</a></li>
						<li><a href="podsumowanie_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
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
						$_SESSION['stan_konta'] = $stan_konta;
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
					$id = $_SESSION['id'];
					$sql2 = "SELECT * FROM transakcje WHERE id = '$id'";
					if($rezultat = $polaczenie -> query($sql2))
					{
						$ile_transakcji = $rezultat->num_rows;
						if ($ile_transakcji > 0)
						{
							$sql3 = "SELECT * FROM transakcje WHERE id = '$id' ORDER BY data DESC LIMIT 1 ";
							if ($rezultat = $polaczenie -> query ($sql3))
							{
								$wiersz2 = $rezultat -> fetch_assoc();
								$kategoria = $wiersz2['kategoria'];
								$data = $wiersz2['data'];
								$cena = $wiersz2['cena'];
							}
						}
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
					$sql4 = "SELECT * FROM uzytkownicy WHERE id = $id";
					if($rezultat = $polaczenie -> query($sql4))
					{
						$wiersz4 = $rezultat ->fetch_assoc();
						$skarbonka = $wiersz4['skarbonka'];
						$cel_oszczednosci = $wiersz4['cel_oszczednosci'];
						$potrzebna_ilosc = $wiersz4['potrzebna_ilosc'];
						$_SESSION['skarbonka'] = $skarbonka;
						$_SESSION['cel_oszczednosci'] = $cel_oszczednosci;
						$_SESSION['potrzebna_ilosc'] = $potrzebna_ilosc;
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
			if($ile_transakcji>0)
		  {
				echo $kategoria."</br>".$data."</br>".$cena;
			}
			else 
		  {				
				echo "Brak danych";
			}
		?>
		</div>
		<div class="kafel">
		Ilość transakcji:
		<?php
		if ($ile_transakcji>0) echo "</br></br>".$ile_transakcji;
		else echo "Brak transakcji";
		?>
		</div>
		<div class="kafel">
		Łączny majątek:
		</div>
		<div style="clear:both"></div>
		<div class="kafel_duzy">
		</br>Skarbonka </br></br>
		<?php
		//jesli jeszcze nic nie było robione ze skarbonką
			if ($cel_oszczednosci == "brak")
			{
				echo "Na ten moment brak celów";
			}
			//jeśli jest dodany już cel oszczędzania
			else if(($cel_oszczednosci != "brak")&& ($skarbonka == 0) )
		  {
				echo "Cel zbieraniny: ".$cel_oszczednosci."</br></br>";
				echo "Potrzebujesz: ".$potrzebna_ilosc."</br>";
				echo "Masz już: 0zł"."</br></br>";
				echo "Łącznie: 0%"."</br></br></br>";
			}
			//jeśli jest dodany cel i dodane juz pierwsze wpłaty
			else 
			{
				$procent = ($skarbonka/$potrzebna_ilosc)*100;
				echo "Cel zbieraniny: ".$cel_oszczednosci."</br></br>";
				echo "Potrzebujesz: ".$potrzebna_ilosc."zł"."</br>";
				echo "Masz już: ".$skarbonka."zł"."</br></br>";
				echo "Łącznie: ".$procent."%"."</br></br></br>";
			}
			
		?>
		</div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>