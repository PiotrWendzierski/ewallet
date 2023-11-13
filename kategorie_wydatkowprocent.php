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
 $sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wyplyw' ";

$rezultat = mysqli_query($polaczenie,$sql);
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
	<div>
	<a href="kategorie_wydatkow.php">POKAŻ WYKRES ILOŚCIOWY</a>
	<form id = "okres" action="" method = "GET">
			Wybierz okres</br>
			<input type="date" name = "od" value="<?php if(isset($_GET['od'])){echo $_GET['od'];}?>"/> 
			<input type="date" name = "do" value="<?php if(isset($_GET['do'])){echo $_GET['do'];}?>"/> 
			<?php
			if((isset($_COOKIE['i'])) && ($_COOKIE['i'] == 0))
			{
				header('Location: brak.php');
				exit();
			}
			
			?>
			<input type="submit" value="Filtruj"></input>
	</form>
	</div>
	<div id="piechart" style="width: 900px; height: 500px; margin-left: auto; margin-right: auto; ">
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
				 $sql2 = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wyplyw'";
				 $rezultat2 = $polaczenie -> query ($sql2);
				 while($row2 = $rezultat2 -> fetch_assoc())
				 {
					 $kategoriaa = $row2['kategoria'];
					 $sql3 = "SELECT * FROM transakcje WHERE id ='$id' AND kategoria = '$kategoriaa' AND wplywwyplyw = 'wyplyw' AND data BETWEEN '$od' AND '$do'";
					 $rezultat3 = $polaczenie -> query($sql3);
					 $ilosc_wydanej_kasy = 0;
					 while($row3 = $rezultat3->fetch_assoc())
					{
						$i++;
						 $cenaa = $row3['cena'];
						 $ilosc_wydanej_kasy = $ilosc_wydanej_kasy + $cenaa;
						 
					 }
					 $ilosc_wydanej_kasy = $ilosc_wydanej_kasy * (-1);
					 echo "['".$kategoriaa."', ".$ilosc_wydanej_kasy."],";
					 
				 }
				 if($i == 0)
				 {
					 setcookie('i', 0);
					 header('Location: kategorie_wydatkowprocent.php');
				 }
			}

		 else echo "Brak wydatków !";
		 
		 
		 
		 ?>
        ]);

        var options = {
          legend: 'none',
		  chartArea:{top:50,width:'90%',height:'80%'},
		  backgroundColor: '#F6F3F3',
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