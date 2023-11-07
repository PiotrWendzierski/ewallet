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
			  $dataczas = new DateTime();			  
			  $koniec = DateTime::createFromFormat('Y-m-d', $data);
			  $roznica = $dataczas->diff($koniec);
			  echo $kategoria."</br>".$data." ".$roznica->format('(%d dni temu)')."</br>".$cena." zł";
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
			echo "<div id='donutchart' style='width: 200px; height: 200px;'>
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
				WYDATKI 
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
				<div id="piechart" style="width: 200px; height: 150px; ">
				
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
				PRZYCHODY
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
				<div id="piechartt" style="width: 200px; height: 150px; ">
				
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
		
		<div style= "clear:both"></div>
		
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>