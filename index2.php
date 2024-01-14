<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	if(!isset($_SESSION['stan_konta']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "connect.php";
	
	setcookie('i', 1);
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
						<li><a class="rejestraja" href="kategorie_wplywowprocent.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywow.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
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
						
						$brakuje = $potrzebna_ilosc - $skarbonka;
						
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
			echo $stan_konta." zł";
		?>
		</div>
		<div class="kafel">
		Ostatnia transakcja: </br></br>
		<?php
			if($ile_transakcji>0)
		  {
			 $teraz=gmmktime();
			 $dzienn = strtotime($data);
			$sekund = abs($teraz-$dzienn);
			$minut = (int)($sekund/60);
			$godzin = (int)($minut/60);
			$dni = (int)($godzin/24);
			  
			  echo $kategoria."</br>".$data." "."(".$dni." dni temu)"."</br>".$cena." zł";
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
		<?php
			$lacznie = $skarbonka+$stan_konta;
			echo "</br></br>".$lacznie;
			unset($_SESSION['kwota_przeznaczona']);
			
		?>
		</div>
		<div style="clear:both"></div>
		<div class="kafel_duzy">
		Moja Skarbonka
		<?php
		function wykres($cel_oszczednosci, $skarbonka, $brakuje )
		{
			echo "<div id='donutchart' style='width: 100%; height: 200px;'>
				<script type='text/javascript'>
				  google.charts.load('current', {packages:['corechart']});
				  google.charts.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Task', 'Hours per Day'],
					  ['Wpłacone środki', $skarbonka],
					  ['Do celu brakuje', $brakuje]
					]);

					var options = {
					  legend: 'none',
					  pieHole: 0.6,
					  chartArea:{top:10,width:'100%',height:'85%'},
					  colors:['green','red'],
					};

					var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
					chart.draw(data, options);
					}
				</script></div>";
		}
		//jesli jeszcze nic nie było robione ze skarbonką
			if ($cel_oszczednosci == "brak")
			{
				echo "</br>"."Na ten moment brak celów";
			}
			//jeśli jest dodany już cel oszczędzania
			else if(($cel_oszczednosci != "brak")&& ($skarbonka == 0) )
		  {
				echo "</br>CEL: ".$cel_oszczednosci;			  
				wykres($cel_oszczednosci, $skarbonka, $brakuje);
				echo "</br>".'<a href="usun_skarbonke.php">USUŃ cel zbieraniny!</a>'."</br>";
				echo '<a href="edytuj_skarbonke.php">EDYTUJ cel zbieraniny!</a>'."</br>";
			}
			//jeśli jest dodany cel i dodane juz pierwsze wpłaty i ilość zaoszczedzonej kasy nie przewyzsza tego ile potrzeba
			else if(($cel_oszczednosci != "brak")&& ($skarbonka != 0)&& ($brakuje >0 ))
			{
				echo "</br>CEL: ".$cel_oszczednosci;
				wykres($cel_oszczednosci, $skarbonka, $brakuje);
				echo '<a href="usun_skarbonke.php">USUŃ cel zbieraniny!</a>'."</br>";
				echo '<a href="edytuj_skarbonke.php">EDYTUJ cel zbieraniny!</a>'."</br>";
			}
			
			//jeśli jest dodany cel i dodane juz pierwsze wpłaty i ilość zaoszczedzonej kasy jest ile potrzeba
			else if(($cel_oszczednosci != "brak")&& ($skarbonka != 0)&& ($brakuje ==0 ))
			{
				echo "</br>"."Gratulacje!  Uzbierałeś pieniądze na "."$cel_oszczednosci";
				wykres($cel_oszczednosci, $skarbonka, $brakuje);
				echo '<a href="usun_skarbonke.php">USUŃ cel zbieraniny!</a>'."</br>";
				echo '<a href="edytuj_skarbonke.php">EDYTUJ cel zbieraniny!</a>'."</br>";
			}
			
			//jeśli jest dodany cel i dodane juz pierwsze wpłaty i ilość zaoszczedzonej kasy jest wieksza niz potrzeba
			else if(($cel_oszczednosci != "brak")&& ($skarbonka != 0)&& ($brakuje <0 ))
			{
				echo "</br>"."Gratulacje!  Uzbierałeś pieniądze na "."$cel_oszczednosci";
				wykres($cel_oszczednosci, $skarbonka, $brakuje=0);
				echo '<a href="usun_skarbonke.php">USUŃ cel zbieraniny!</a>'."</br>";
				echo '<a href="edytuj_skarbonke.php">EDYTUJ cel zbieraniny!</a>'."</br>";
			}
			
		?>

		</div>
		<div id="wplywyiwyplywy">
		
			<a href = "kategorie_wydatkow.php" >
				<div class = "wykres">
				Wydatki 
				<?php
				require_once("connect.php");

				$miesiac = date('m'); $rok = date('Y');
				$poczatek_miesiaca = $rok."-".$miesiac."-01";
				$koniec_miesiaca = $rok."-".$miesiac."-31";
				echo $miesiac.".".$rok;
				$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

				 $sql = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wyplyw' ";

				$rezultat = mysqli_query($polaczenie,$sql);
				?>
				<div id="piechart" style="width: 100%; height: 150px; ">
				
				<script type='text/javascript'>
					  google.charts.load('current', {'packages':['corechart']});
					  google.charts.setOnLoadCallback(drawChart);
					  
					  function drawChart() {

						var data = google.visualization.arrayToDataTable([
						  ['Task', 'Hours per Day'],
						 <?php
							$ile_kategorii = $rezultat -> num_rows; 
							if($ile_kategorii >0)
							{
								$i=0;
								 while($row = $rezultat -> fetch_assoc())
								 {
									 $kategoria = $row['kategoria'];
									 $sql5 = "SELECT * FROM transakcje WHERE id ='$id' AND wplywwyplyw = 'wyplyw' AND kategoria = '$kategoria' AND data BETWEEN '$poczatek_miesiaca'
									 AND '$koniec_miesiaca'";
									 $rezultat2 = $polaczenie ->query($sql5);
									 $laczna_ilosc_transakcji = 0;
									 while($row2 = $rezultat2->fetch_assoc())
									{
										$laczna_ilosc_transakcji=$laczna_ilosc_transakcji+1;
									 }
									 echo "['".$row['kategoria']."', ".$laczna_ilosc_transakcji."],";
									 $i++;
									 if($i == 5) break;
								 }
								 
							}
							else echo "ok";
						 ?>
						]);

						var options = {
						  legend: 'none',
						  chartArea:{top:10,width:'100%',height:'85%'},
						};

						var chart = new google.visualization.PieChart(document.getElementById('piechart'));

						chart.draw(data, options);
					  }
					</script>
				</div>
				</div>
			</a>
			<a href = "kategorie_wplywow.php" >
				<div class = "wykres">
				Przychody
				<?php
				require_once("connect.php");

				$miesiac = date('m'); $rok = date('Y');
				$poczatek_miesiaca = $rok."-".$miesiac."-01";
				$koniec_miesiaca = $rok."-".$miesiac."-31";
				echo $miesiac.".".$rok;
				$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

				 $sql = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wplyw' ";

				$rezultat = mysqli_query($polaczenie,$sql);
				?>
				<div id="piechartt" style="width: 100%; height: 150px; ">
				
				<script type='text/javascript'>
					  google.charts.load('current', {'packages':['corechart']});
					  google.charts.setOnLoadCallback(drawChart);
					  
					  function drawChart() {

						var data = google.visualization.arrayToDataTable([
						  ['Task', 'Hours per Day'],
						 <?php
							$ile_kategorii = $rezultat -> num_rows; 
							if($ile_kategorii >0)
							{
								$i=0;
								 while($row = $rezultat -> fetch_assoc())
								 {
									 $kategoria = $row['kategoria'];
									 $sql5 = "SELECT * FROM transakcje WHERE id ='$id' AND wplywwyplyw = 'wplyw' AND kategoria = '$kategoria' AND data BETWEEN '$poczatek_miesiaca'
									 AND '$koniec_miesiaca'";
									 $rezultat2 = $polaczenie ->query($sql5);
									 $laczna_ilosc_transakcji = 0;
									 while($row2 = $rezultat2->fetch_assoc())
									{
										$laczna_ilosc_transakcji=$laczna_ilosc_transakcji+1;
									 }
									 echo "['".$row['kategoria']."', ".$laczna_ilosc_transakcji."],";
									 $i++;
									 if($i == 5) break;
								 }
								 
							}
							else echo "ok";
						 ?>
						]);

						var options = {
						  legend: 'none',
						  chartArea:{top:10,width:'100%',height:'85%'},
						};

						var chart = new google.visualization.PieChart(document.getElementById('piechartt'));

						chart.draw(data, options);
					  }
					</script>
					</div>
				
				
				
				</div>
			</a>
			<div style="clear:both"></div>
			<div class = "wplywyywyplywy">Wydatki
			<?php
				echo date('m-Y'); echo "</br></br>";
				 $sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wyplyw' AND data BETWEEN '$poczatek_miesiaca' AND '$koniec_miesiaca'";
				 $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
				 $rezultat = $polaczenie -> query ($sql);
				 $ile_kategorii = $rezultat -> num_rows;
				 $laczna_ilosc_wydanej_kasy= 0;
				 if($ile_kategorii !=0)
				 {
					 //tutaj liczymi ile jest wydanej kasy łącznie
					 while($row = $rezultat -> fetch_assoc())
					 {
						 $kategoria = $row['kategoria'];
						 $ilosc_wydanej_kasy = $row['cena'];
						 $laczna_ilosc_wydanej_kasy = $laczna_ilosc_wydanej_kasy + $ilosc_wydanej_kasy;
						 
					 }
					 echo $laczna_ilosc_wydanej_kasy." zł";
				 }
				 else echo "Brak wydatków w tym miesiącu!";
				 $polaczenie->close();
			?>
			</div>
			<div class = "wplywyywyplywy">Wpływy
			<?php
				echo date('m-Y'); echo "</br></br>";
				 $sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wplyw' AND data BETWEEN '$poczatek_miesiaca' AND '$koniec_miesiaca'";
				 $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
				 $rezultat = $polaczenie -> query ($sql);
				 $ile_kategorii = $rezultat -> num_rows;
				 $laczna_ilosc_wydanej_kasy= 0;
				 if($ile_kategorii !=0)
				 {
					 //tutaj liczymi ile jest wydanej kasy łącznie
					 while($row = $rezultat -> fetch_assoc())
					 {
						 $kategoria = $row['kategoria'];
						 $ilosc_wydanej_kasy = $row['cena'];
						 $laczna_ilosc_wydanej_kasy = $laczna_ilosc_wydanej_kasy + $ilosc_wydanej_kasy;
						 
					 }
					 echo "+ ".$laczna_ilosc_wydanej_kasy." zł";
				 }
				 else echo "Brak wpływów w tym miesiącu!";
				 $polaczenie -> close();
			?>
			</div>
			<div style= "clear:both"></div>
		</div>
		
		<div class="wykres_duzy"><a href="stan_portfela.php">Stan portfela (ostatnie 7 dni)</a>
		<?php
			require_once("connect.php");

			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$sql = "SELECT * FROM uzytkownicy WHERE id = '$id' ";
			$rezultat = mysqli_query($polaczenie,$sql);
			$row = $rezultat->fetch_assoc();
			$stan_konta = $row['stan_konta'];
			for($j =0; $j<7; $j++)
			{
				$datan[$j] = date("Y-m-d", strtotime("- $j day"));
			}
			for ($j=0; $j<7; $j++)
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
			for ($j = 6; $j>=0; $j--)
			{
				$wydatkidnia[$j+1] = 0;
				for ($i=0; $i<$j; $i++)
				{
					$wydatkidnia[$j+1] = $wydatkidnia[$j+1]+$wydatki[$i];
				}
				$standnia[$j] = $stan_konta - $wydatkidnia[$j+1];
			}
		?>
			<div id="chart_div" style="width: 300px; height: 250px; text-align: center;">
				<script type="text/javascript">
					  google.charts.load('current', {'packages':['corechart']});
					  google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['', 'kwota'],

						  <?php 
						  for($j =0; $j<7; $j++)
						{
							$dzien[$j] = date("d-m	", strtotime("- $j day"));
						}
						  
						  for ($i=6; $i>=0; $i--)
						  {
							  echo "['$dzien[$i]',  $standnia[$i]],";
						  }
						   ?>
						  
						]);

						var options = {
							legend: 'none',
							chartArea:{top:30,width:'75%',height:'70%'},
						  vAxis: {minValue: 0}
						  
						};

						var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
						chart.draw(data, options);
					  }
				</script>
			</div>
		</div>
		<div class="wykres_duzy">Wydatki (ostatnie pół roku)
				<?php
				for($i=0; $i<6; $i++)
				{
					$dzis[$i] = date('Y-m-15', strtotime("- $i month"));
					$miesia[$i] = date('m.y',strtotime("- $i month"));
				}

				for($i=0; $i<6; $i++)
				{
					$first[$i] = date("Y-m-01", strtotime($dzis[$i])); 
					$last[$i] = date("Y-m-t", strtotime($dzis[$i])); 
				}
				
				
				for ($i =0; $i<6; $i++)
				{
					$sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wyplyw' AND data BETWEEN '$first[$i]' AND '$last[$i]'";
					$rezultat = $polaczenie ->query($sql);
					$wydatki[$i] = 0;
					while($row = $rezultat->fetch_assoc())
					{
						$wydatek = $row['cena'];
						$wydatek = $wydatek * (-1);
						$wydatki[$i] = $wydatki[$i] + $wydatek;
						$wydatki[$i] = abs($wydatki[$i]);
					}
					
					$sql2 = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wplyw' AND data BETWEEN '$first[$i]' AND '$last[$i]'";
					$rezultat2 = $polaczenie ->query($sql2);
					$wplywy[$i] = 0;
					while($row2 = $rezultat2->fetch_assoc())
					{
						$wplyw = $row2['cena'];
						$wplywy[$i] = $wplywy[$i] + $wplyw;
					}
					
				}
				?>
				 <script type="text/javascript">
					  google.charts.load('current', {'packages':['bar']});
					  google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['',  'Przychody', 'Wydatki'],
						  <?php
						  for ($i=5; $i>=0; $i--)
						  {	  
							echo "['$miesia[$i]',".$wplywy[$i].",".$wydatki[$i]."],";
						  }
						  ?>
						 
						]);

						var options = {
						  legend: { position: "none" },
						  colors:['green','red'],
						  animation: {"startup": true}
						  
						};

						var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

						chart.draw(data, options);
					  }
			</script>
			<div id="columnchart_material" style="width: 300px; height: 230px;"></div>
		</div>
		<div style= "clear:both"></div>
		
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone
	</div>
</div>
</body>

</html>