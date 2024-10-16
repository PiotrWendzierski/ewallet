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

require_once("connect.php");

$od = "0000-01-01"; if(isset($_GET['od']))$od = $_GET['od'];
$do =  "6000-01-01"; if(isset($_GET['do']))$do = $_GET['do']; 
if(isset($_GET['od'])&& ($_GET['od'] == ""))$od = "0000-01-01";
if(isset($_GET['do'])&& ($_GET['do'] == ""))$do = "6000-01-01";

$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
$id = $_SESSION['id'];
 $sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wplyw' ";

$rezultat = mysqli_query($polaczenie,$sql);
$rezultatt = mysqli_query($polaczenie,$sql);

$ile_kategorii = $rezultat->num_rows;

if($ile_kategorii == 0)
{
	header('Location: brak_danych.php');
	exit();
}
else
{
	$ii =0;
	while($roww = $rezultatt -> fetch_assoc())
	{
		$kategoriaaa = $roww['kategoria'];
		$sqll5 = "SELECT * FROM transakcje WHERE id ='$id' AND wplywwyplyw = 'wplyw' AND kategoria = '$kategoriaaa' AND data BETWEEN '$od'
		AND '$do'";
		$rezultatt2 = $polaczenie ->query($sqll5);
		
		while($roww2 = $rezultatt2->fetch_assoc())
		{
			$ii++;
		}
	}
	if($ii == 0)
	{
		header("Location: brak.php");
		exit();
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
	<div class="filtring">
		<a href="kategorie_wplywow.php"><input type='submit' class='filtruj' value = 'POKAŻ WYKRES ILOŚCIOWY' /></a>
	</div>
	<div class="filtring">
	<form id = "okres" action="" method = "GET">
			Wybierz okres</br>
			<input type="date" class="sorting" name = "od" value="<?php if(isset($_GET['od'])){echo $_GET['od'];}?>"/> 
			<input type="date" class="sorting" name = "do" value="<?php if(isset($_GET['do'])){echo $_GET['do'];}?>"/> 
			<?php
			if((isset($_COOKIE['i'])) && ($_COOKIE['i'] == 0))
			{
				header('Location: brak.php');
				exit();
			}
			
			?>
			</br><input type="submit" class="filtruj" value="Filtruj"></input>
	</form>
	</div>
	<div id="kategorie_wydatkow">
	<div id="piechart" style="width: 60%; height: 500px; margin-left: auto; margin-right: auto; ">
	<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          <?php
			$ile_kategorii = $rezultat -> num_rows; 
			if($ile_kategorii >0)
			{
				$i = 0;
				 $sql2 = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wplyw'";
				 $rezultat2 = $polaczenie -> query ($sql2);
				 while($row2 = $rezultat2 -> fetch_assoc())
				 {
					 $kategoriaa = $row2['kategoria'];
					 $sql3 = "SELECT * FROM transakcje WHERE id ='$id' AND kategoria = '$kategoriaa' AND wplywwyplyw = 'wplyw' AND data BETWEEN '$od' AND '$do'";
					 $rezultat3 = $polaczenie -> query($sql3);
					 $ilosc_wydanej_kasy = 0;
					 while($row3 = $rezultat3->fetch_assoc())
					{
						$i++;
						 $cenaa = $row3['cena'];
						 $ilosc_wydanej_kasy = $ilosc_wydanej_kasy + $cenaa;
						 
					 }
					
					 echo "['".$kategoriaa."', ".$ilosc_wydanej_kasy."],";
					 
				 }
				 if($i == 0)
				 {
					 setcookie('i', 0);
					 header('Location: kategorie_wplywowprocent.php');
				 }
			}

		 else 
		 {
				header('Location: brak_danych.php');
				exit();
		 }
		 ?>
        ]);

        var options = {
          legend: 'none',
		  chartArea:{top:50,width:'90%',height:'80%'},
		  backgroundColor: '#141415',
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
	

	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>