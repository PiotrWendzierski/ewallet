<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	require_once "connect.php";
	
	setcookie('kwota_przeznaczona', '');
	setcookie('data', '');
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="img/fontello-9677cda3/css/fontello.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-571ab779/css/fontello.css" type="text/css" / >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
	<link rel="stylesheet"  href="style.css" type="text/css" / >
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
	
	<div id="sortowanie">
		<form action="" method = "GET">
			<select class="sorting" name = "sorting"> 
				<option value="">--Sortuj według</option> 
				<option value="data_up" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "data_up"){echo "selected";}?>>Według daty rosnąco</option> 
				<option value="data_down" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "data_down"){echo "selected";}?>>Według daty malejąco</option> 
				<option value="cena_down" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "cena_down"){echo "selected";}?>>Według ceny rosnąco</option> 
				<option value="cena_up" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "cena_up"){echo "selected";}?>>Według ceny malejąco</option> 
				<option value="default" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "default"){echo "selected";}?>>Domyślnie</option> 
			
			</select> </br></br>
			
			Od</br>
			<input type="date" class="sorting" name = "od" value="<?php if(isset($_GET['od'])){echo $_GET['od'];}?>"/> </br></br>
			Do</br>
			<input type="date" class="sorting" name = "do" value="<?php if(isset($_GET['do'])){echo $_GET['do'];}?>"/> </br></br>
			
			Cena od</br><input type="number" class="sorting" name = "cena_od" value="<?php if(isset($_GET['cena_od'])){echo $_GET['cena_od'];}?>"/> </br></br>
			Cena do</br><input type="number" class="sorting" name = "cena_do" value="<?php if(isset($_GET['cena_do'])){echo $_GET['cena_do'];}?>"/> </br>

		</br></br>

			
			</br><input type="submit" class="filtruj" value="Filtruj"></input>
		</form>

  
	</div>
	
	
	<div id="historia">
	<?php
	mysqli_report(MYSQLI_REPORT_STRICT);
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
			$rezultat = $polaczenie ->query("SELECT * FROM transakcji_skarbonki WHERE id = '$id'");
			$ile_transakcji = $rezultat -> num_rows;
			$ilosc_rekordow = $ile_transakcji;
			//jeśli wyszuka nam jakieś transakcje
			if($ile_transakcji != 0)
			{
					//jeśli ktoś zaznaczył filtrowanie
					if(isset($_GET['od']))
					{
						//jeśli ktos na formularzu nie zaznaczył dat;

						if ($_GET['od'] == "") $od = $_GET['od'] = "0000-01-01"; else $od = $_GET['od'];
						if ($_GET['do'] == "") $do = $_GET['do'] = "6000-01-01"; else $do = $_GET['do'];

						//jeśli ktoś nie zaznaczył ceny od;
						if($_GET['cena_od'] == "") $cena_od = "-100000000000"; else $cena_od = $_GET['cena_od'];
						if($_GET['cena_do'] == "") $cena_do = "100000000000"; else $cena_do = $_GET['cena_do'];
						
						$query = "SELECT * FROM transakcji_skarbonki WHERE (id='$id')AND (data_transakcji BETWEEN '$od' AND '$do')AND (kwota_przeznaczona BETWEEN '$cena_od' AND '$cena_do' )";
						if(isset($_GET['sorting'])&& $_GET['sorting'] == "data_up")
							$query = "SELECT * FROM transakcji_skarbonki WHERE (id='$id')AND (data_transakcji BETWEEN '$od' AND '$do')AND (kwota_przeznaczona BETWEEN '$cena_od' AND '$cena_do' )ORDER BY  data_transakcji ASC";
						else if (isset($_GET['sorting'])&& $_GET['sorting'] == "data_down")
							$query = "SELECT * FROM transakcji_skarbonki WHERE (id='$id')AND (data_transakcji BETWEEN '$od' AND '$do')AND (kwota_przeznaczona BETWEEN '$cena_od' AND '$cena_do' )ORDER BY  data_transakcji DESC";
						else if (isset($_GET['sorting'])&& $_GET['sorting'] == "cena_up")
							$query = "SELECT * FROM transakcji_skarbonki WHERE (id='$id')AND (data_transakcji BETWEEN '$od' AND '$do')AND (kwota_przeznaczona BETWEEN '$cena_od' AND '$cena_do' )ORDER BY  kwota_przeznaczona DESC";
						else if (isset($_GET['sorting'])&& $_GET['sorting'] == "cena_down")
							$query = "SELECT * FROM transakcji_skarbonki WHERE (id='$id')AND (data_transakcji BETWEEN '$od' AND '$do')AND (kwota_przeznaczona BETWEEN '$cena_od' AND '$cena_do' )ORDER BY  kwota_przeznaczona ASC";
						
						//tu odbywa się wyszukanie pasujących rekordów;
						$rezultat = $polaczenie->query($query);
						$ile_transakcji = $rezultat->num_rows;
						if($ile_transakcji > 0)
						{
								$numer_transakcji = 1; //echo $query; 
								if($ile_transakcji!=$ilosc_rekordow)echo "<a href ='historia_skarbonki.php' ><input type='submit' class='filtruj' value = 'Wszystkie dane' /></a>"."</br>";	
								echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left:auto; margin-right: auto;'><td>Numer transakcji</td>
								<td>Kwota przeznaczona</td><td>Data transakcji</td></table>";

								while ($row = $rezultat -> fetch_assoc())
								{
									$data_transakcji = $row['data_transakcji'];
									$kwota_przeznaczona = $row['kwota_przeznaczona']; if($kwota_przeznaczona>0)$kwota_przeznaczonaa = '<span style="color:green">'.'+'.$kwota_przeznaczona.'</span>'; else $kwota_przeznaczonaa = '<span style="color:red">'.$kwota_przeznaczona.'</span>';
									//echo "<table border = '1'><tr><td>5</td></tr><table>";
									echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left:auto; margin-right: auto;'><td>".$numer_transakcji."</td><td>".$kwota_przeznaczonaa."</td><td>".$data_transakcji."</td></tr></table>";
									$numer_transakcji++; 
									}
						}
						else 
						{
							echo "<a href ='historia_skarbonki.php' ><input type='submit' class='filtruj' value = 'Wszystkie dane' /></a>"."</br>";	
							echo $brak_danych_w_danym_czasie = '</br>'.'<span style="color:red">Nie znaleziono pasujących rekordów!</span>'.'</br>';
							unset($brak_danych_w_danym_czasie);
						}
						
					}	
					//jeśli nikt nie kliknął filtrowania to normalnie pokazać wszystkie rekordy
					else
					{
						$numer_transakcji = 1;
						echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left:auto; margin-right: auto;'><td>Numer transakcji</td><td>Kwota przeznaczona</td><td>Data transakcji</td><td>Operacja</td></table>";
						while ($row = $rezultat -> fetch_assoc())
						{
							$data_transakcji = $row['data_transakcji'];
							$kwota_przeznaczona = $row['kwota_przeznaczona'];  if($kwota_przeznaczona>0)$kwota_przeznaczonaa = '<span style="color:green">'.'+'.$kwota_przeznaczona.'</span>'; else $kwota_przeznaczonaa = '<span style="color:red">'.$kwota_przeznaczona.'</span>';
							//echo "<table border = '1'><tr><td>5</td></tr><table>";
							echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left:auto; margin-right: auto;'><td>".$numer_transakcji."</td><td>".$kwota_przeznaczonaa."</td><td>".$data_transakcji."</td>
							<form action = 'deleteskarbonka.php' method = 'post'>
									<input type = 'hidden' name = 'id' value = '$id' >
									<input type = 'hidden' name = 'cena' value = '$kwota_przeznaczona' >
									<input type = 'hidden' name = 'data' value = '$data_transakcji' >
									<td> <input type='submit' name='delete'  class='delette' value = 'Usuń'>  
								</form>
								
								<form id = 'idddd' action = 'editskarbonka.php' method = 'post'>
									<input type = 'hidden' name = 'id' value = '$id' >
									<input type = 'hidden' name = 'cena' value = '$kwota_przeznaczona' >
									<input type = 'hidden' name = 'data' value = '$data_transakcji' >
									 <input type='submit' name='delete'  class='editt' value = 'Edytuj'> </td>
								</form>
								</tr></table>";
							$numer_transakcji++; 
						}

					}
			}
			//jeśli nie wyszuka zadnych transakcji
			else echo "Brak transakcji. Zapraszamy do dodania danych do Twojego konta.";
			$polaczenie -> close();
		}
	}
	catch(Exception $e)
	{
		echo $e;
	}
	?>
	</div>
	<div style="clear:both"></div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>