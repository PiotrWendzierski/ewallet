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
			$id = $_SESSION['id'];
			require_once("connect.php");

			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$sql = "SELECT * FROM uzytkownicy WHERE id = '$id' ";
			$rezultat = mysqli_query($polaczenie,$sql);
			$row = $rezultat->fetch_assoc();
			$stan_konta = $row['stan_konta'];
			//znalezienie, ile dni temu była pierwsza transakcja, aby od tego momentu pokazać zmiane stanu portfela
			$sql3 = "SELECT * FROM transakcje WHERE id = '$id' ORDER BY data ASC LIMIT 1";
			$rezultat8 = $polaczenie ->query($sql3);
			$ile_transakcji = $rezultat8 -> num_rows;
			if($ile_transakcji == 0)
			{
				header('Location: brak_danych.php');
				exit();
			}
			$rezultat3 = mysqli_query($polaczenie, $sql3);
			$row3 = $rezultat3->fetch_assoc();
			$pierwsza_data = $row3['data'];
			$teraz=time();		  
			$dzienn = strtotime($pierwsza_data);
			$sekund = abs($teraz-$dzienn);
			$minut = (int)($sekund/60);
			$godzin = (int)($minut/60);
			$roznica = (int)($godzin/24); if((isset($_GET['sorting']))&&($_GET['sorting']== "miesiac"))$roznica = (int)($roznica/30);
																		if((isset($_GET['sorting']))&&($_GET['sorting']== "rok"))$roznica = (int)($roznica/30/12);
																		
				 if($roznica == 1) $roznica = 2;
			 $tasowanie = $roznica/20;
			 if(is_int($tasowanie))$dodanie = 0; else $dodanie =1;
			 settype($tasowanie, "integer");
			 $tasowanie = $tasowanie+$dodanie;
			 if((isset($_GET['sorting']))&&($_GET['sorting']== "miesiac"))
			 {
				 $m = 0;
				 for($j =0; $j<$roznica; $j++)
				{
					 $datan[$j] = date("Y-m-d", strtotime("- $m day"));
					 $m = $m+30;
				}
			 }
			 else if((isset($_GET['sorting']))&&($_GET['sorting']== "rok"))
			 {
				 $m = 0;
				 for($j =0; $j<$roznica; $j++)
				{
					 $datan[$j] = date("Y-m-d", strtotime("- $m day"));
					 $m = $m+365;
				}
			 }
			 else
			 {
				for($j =0; $j<$roznica; $j++)
				{
					$datan[$j] = date("Y-m-d", strtotime("- $j day"));
				}
			 }
			 if((isset($_GET['sorting']))&&($_GET['sorting'] == "miesiac"))
			 {
				 for ($j=0; $j<$roznica; $j++)
				{
					$wydatki[$j] = 0;
					$k =$j+1; if($k>= $roznica)$k = $k -1;
					$sql2 = "SELECT * FROM transakcje WHERE id = '$id' AND (data BETWEEN '$datan[$k]' AND '$datan[$j]')";
					$rezultat2 = mysqli_query($polaczenie,$sql2);
					while($row2 = $rezultat2->fetch_assoc())
					{
						$kwota = $row2['cena'];
						$wydatki[$j] = $wydatki[$j] + $kwota;
						
					}
					$kwota = 0;
				}
				for ($j = $roznica; $j>=0; $j--)
				{
					$wydatkidnia[$j+1] = 0;
					for ($i=0; $i<$j; $i++)
					{
						$wydatkidnia[$j+1] = $wydatkidnia[$j+1]+$wydatki[$i];
						
					}
					$standnia[$j] = $stan_konta - $wydatkidnia[$j+1];
				}				
			 }
			 else if ((isset($_GET['sorting']))&&($_GET['sorting'] == "rok"))
			 {

				 for ($j=0; $j<$roznica; $j++)
					{
						$wydatki[$j] = 0;
						$k =$j+1; if($k>= $roznica)$k = $k -1;
						$sql2 = "SELECT * FROM transakcje WHERE id = '$id' AND (data BETWEEN '$datan[$k]' AND '$datan[$j]')";
						$rezultat2 = mysqli_query($polaczenie,$sql2);
						while($row2 = $rezultat2->fetch_assoc())
						{
							$kwota = $row2['cena'];
							$wydatki[$j] = $wydatki[$j] + $kwota;
						}
						$kwota = 0;
					}
					for ($j = $roznica; $j>=0; $j--)
				{
					$wydatkidnia[$j+1] = 0;
					for ($i=0; $i<$j; $i++)
					{
						if(isset($wydatki[$i]))
						$wydatkidnia[$j+1] = $wydatkidnia[$j+1]+$wydatki[$i];
					}
					$standnia[$j] = $stan_konta - $wydatkidnia[$j+1];
				}
			 }
			 else
			 {
					for ($j=0; $j<$roznica; $j++)
					{
						$wydatki[$j] = 0;
						$sql2 = "SELECT * FROM transakcje WHERE id = '$id' AND data = '$datan[$j]'";
						$rezultat2 = mysqli_query($polaczenie,$sql2);
						while($row2 = $rezultat2->fetch_assoc())
						{
							$kwota = $row2['cena'];
							$wydatki[$j] = $wydatki[$j] + $kwota;
						}
						$kwota = 0;
					}
					for ($j = $roznica; $j>=0; $j--)
				{
					$wydatkidnia[$j+1] = 0;
					for ($i=0; $i<$j; $i++)
					{
						$wydatkidnia[$j+1] = $wydatkidnia[$j+1]+$wydatki[$i];
					}
					$standnia[$j] = $stan_konta - $wydatkidnia[$j+1];
				}
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
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
	<form id = "okres" action="" method = "GET">
			<select class="sorting" name = "sorting"> 
				<option value="" >--Cały przekrój</option> 
				<option value="miesiac" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "miesiac"){echo "selected";}?>>Miesięczny</option> 
				<option value="rok" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "rok"){echo "selected";}?>>Roczny</option> 
			</select>
			</br><input type="submit" class="filtruj" value="Filtruj"></input>
			<?php
			//echo $wydatkidnia[0]."</br>".$wydatkidnia[4];
			?>
	</form>
	
	<div id="chart_div" style="width: 100%; height: 500px;">
				<script type="text/javascript">
					  google.charts.load('current', {'packages':['corechart']});
					  google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['', 'kwota'],

						  <?php 
						  if((isset($_GET['sorting']))&& ($_GET['sorting'] == "miesiac"))
						{
							  $m = 0;
							  for($j =0; $j<=$roznica; $j++)
							{
								$dzien[$j] = date("d-m-y	", strtotime("- $m day"));
								$m = $m+30;
							}
						}
						else if ((isset($_GET['sorting']))&& ($_GET['sorting'] == "rok"))
						{
							
							$m = 0;
							  for($j =0; $j<=$roznica; $j++)
							{
								$dzien[$j] = date("d-m-y	", strtotime("- $m day"));
								$m = $m+365;
							}
						}
						
						else 
						{
							for($j =0; $j<=$roznica; $j++)
							{
								$dzien[$j] = date("d-m-y	", strtotime("- $j day"));
							}
						}
						  
						  for ($i=$roznica; $i>0; $i=$i-$tasowanie)
						  {
							  echo "['$dzien[$i]',  $standnia[$i]],";
						  }
						  echo "['$dzien[0]',  $standnia[0]],"
						   ?>
						  
						]);

						var options = {
							legend: 'none',
							chartArea:{top:30,width:'80%',height:'70%'},
						  vAxis: {minValue: 0},
						  colors:['229CD1'],
						  hAxis: {textStyle: {color: '#F2F2F2'} },
						  vAxis: {textStyle: {color: '#F2F2F2'},gridlines:{count: -1},format: '# ' },
						  backgroundColor: '#141415',
						};

						var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
						chart.draw(data, options);
					  }
				</script>
			</div>
  <div id="footer">
  Wszelkie prawa zastrzeżone</div>

  <body>
  </body>
</html>
	