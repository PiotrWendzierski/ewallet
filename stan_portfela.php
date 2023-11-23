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
			$rezultat3 = mysqli_query($polaczenie, $sql3);
			$row3 = $rezultat3->fetch_assoc();
			$pierwsza_data = $row3['data'];
			$teraz=gmmktime();		  
			$dzienn = strtotime($pierwsza_data);
			$sekund = abs($teraz-$dzienn);
			$minut = (int)($sekund/60);
			$godzin = (int)($minut/60);
			$roznica = (int)($godzin/24);
			 $tasowanie = $roznica/20;
			 if(is_int($tasowanie))$dodanie = 0; else $dodanie =1;
			 settype($tasowanie, "integer");
			 $tasowanie = $tasowanie+$dodanie;
			for($j =0; $j<$roznica; $j++)
			{
				$datan[$j] = date("Y-m-d", strtotime("- $j day"));
			}
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
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	
	<div id="chart_div" style="width: 100%; height: 500px;">
				<script type="text/javascript">
					  google.charts.load('current', {'packages':['corechart']});
					  google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['', 'kwota'],

						  <?php 
						  for($j =0; $j<$roznica; $j++)
						{
							$dzien[$j] = date("d-m	", strtotime("- $j day"));
						}
						  
						  for ($i=$roznica-1; $i>0; $i=$i-$tasowanie)
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
						  backgroundColor: '#F6F3F3',
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
	