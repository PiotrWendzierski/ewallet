<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	require_once "connect.php";
	
		setcookie('cena', '');
		setcookie('kategoria', '');
		setcookie('zmiana', '');
		setcookie('data',"");
		
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
	
	
	<div id="sortowanie">
		<form action="" method = "GET">
			<select class = "sorting" name = "sorting"> 
				<option value="">--Sortuj według</option> 
				<option value="a-z" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "a-z"){echo "selected";}?>>Od A do Z</option> 
				<option value="z-a" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "z-a"){echo "selected";}?>>Od Z do A</option> 
				<option value="data_up" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "data_up"){echo "selected";}?>>Według daty rosnąco</option> 
				<option value="data_down" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "data_down"){echo "selected";}?>>Według daty malejąco</option> 
				<option value="cena_up" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "cena_up"){echo "selected";}?>>Według ceny rosnąco</option> 
				<option value="cena_down" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "cena_down"){echo "selected";}?>>Według ceny malejąco</option> 
				<option value="default" <?php if(isset($_GET['sorting'])&& $_GET['sorting'] == "default"){echo "selected";}?>>Domyślnie</option> 
			
			</select> </br></br>
			Wyszukaj po nazwie </br>
			<input type="text" name="search" class="sorting" value = "<?php if(isset($_GET['search'])){echo $_GET['search'];}?>"/> </br></br>
			Od</br>
			<input type="date" class="sorting" name = "od" value="<?php if(isset($_GET['od'])){echo $_GET['od'];}?>"/> </br></br>
			Do</br>
			<input type="date" class="sorting" name = "do" value="<?php if(isset($_GET['do'])){echo $_GET['do'];}?>"/> </br></br>
			
			Cena od</br><input class="sorting" type="number" name = "cena_od" value="<?php if(isset($_GET['cena_od'])){echo $_GET['cena_od'];}?>" /> </br></br>
			Cena do</br><input class="sorting" type="number" name = "cena_do" value="<?php if(isset($_GET['cena_do'])){echo $_GET['cena_do'];}?>" /> </br>

		</br></br>

			<input type="checkbox" name="wplywy" value="<?php if(isset($_GET['wplywy'])){echo $_GET['wplywy'] ;}?>"/> Wpływy </br>
			<input type="checkbox" name="wyplywy" value="<?php if(isset($_GET['wplywy'])){echo $_GET['wplywy'] ;}?>"> Wypływy </br></br></br>
			<input type="submit" class="filtruj" value="Filtruj"></input>
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
			$rezultat = $polaczenie ->query("SELECT * FROM transakcje WHERE id = '$id'");
			$ile_transakcji = $rezultat -> num_rows;
			$ilosc_rekordow = $ile_transakcji;
			if ($ile_transakcji != 0)
			{
				//jeśli ktoś kliknął filtruj
				if(isset($_GET['od'] ))
				{
					$search = $_GET['search'];
					//jeśli ktos na formularzu nie zaznaczył dat;

					if ($_GET['od'] == "") $od = $_GET['od'] = "0000-01-01"; else $od = $_GET['od'];
					if ($_GET['do'] == "") $do = $_GET['do'] = "6000-01-01"; else $do = $_GET['do'];

					//jeśli ktoś nie zaznaczył ceny od;
					if($_GET['cena_od'] == "") $cena_od = "-100000000000"; else $cena_od = $_GET['cena_od'];
					if($_GET['cena_do'] == "") $cena_do = "100000000000"; else $cena_do = $_GET['cena_do'];

					//jesli ktos na formularzu nie zaznaczyl search'a
					if($_GET['search'] == "") 
					{
						$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
						if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
						else if ($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
						else if ($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
						else if ($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
						else if ($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
						else if ($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;

					}
					else 
					{
						$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
						if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
						else if ($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
						else if ($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
						else if ($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
						else if ($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
						else if ($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;
					}

					//jesli zaznaczył wpływy
					if ((isset($_GET['wplywy']))&& (!isset($_GET['wyplywy']))) 
					{	
						if(!isset($_GET['wyplywy']))
						{
							if ($search == "")
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
								else if ($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
								else if ($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
								else if ($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
								else if ($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
								else if ($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;
							}
							else 
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
								else if ($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
								else if ($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
								else if ($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
								else if ($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
								else if ($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;
							}
						}
						else
						{
							if($search == "")
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
								else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
								else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
								else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
								else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
								else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;
							}
							else 
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria ASC" ;
								else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY kategoria DESC" ;
								else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data ASC" ;
								else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY data DESC" ;
								else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena ASC" ;
								else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')AND (cena BETWEEN '$cena_od' AND '$cena_do')ORDER BY cena DESC" ;
							}
						}
					}						

  
					//jeśli zaznaczył wypływy
					if ((isset($_GET['wyplywy']))&& (!isset($_GET['wplywy']))) 
					{	
						if(!isset($_GET['wplywy']))
						{
							if($search == "")
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by kategoria ASC" ;
								else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by kategoria DESC" ;
								else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by data ASC" ;
								else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by data DESC" ;
								else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by cena ASC" ;
								else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0)ORDER by cena DESC" ;
							}
							else
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')" ;
								if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY kategoria ASC" ;
								else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY kategoria DESC" ;
								else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY data ASC" ;
								else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY data DESC" ;
								else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY cena ASC" ;
								else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')ORDER BY cena DESC" ;
							}
						}
						else 
						{
							$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
							if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY kategoria ASC" ;
							else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY kategoria DESC" ;
							else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY data ASC" ;
							else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY data DESC" ;
							else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY cenaa ASC" ;
							else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY cenaa DESC" ;
						}
					}		
					//jeśli zaznaczone wpływy i wypływy;
					if((isset($_GET['wpływy'])) && (isset($_GET['wyplywy'])))
					{
						if($search == "")
						{
							$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
							if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY kategoria ASC" ;
							else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY kategoria DESC" ;
							else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY data ASC" ;
							else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY data DESC" ;
							else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY cena ASC" ;
							else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')ORDER BY cena DESC" ;
						}
						else 
						{
							$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')" ;
							if($_GET['sorting'] == "a-z")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY kategoria ASC" ;
							else if($_GET['sorting'] == "z-a")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY kategoria DESC" ;
							else if($_GET['sorting'] == "data_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY data ASC" ;
							else if($_GET['sorting'] == "data_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY data DESC" ;
							else if($_GET['sorting'] == "cena_up")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY cena ASC" ;
							else if($_GET['sorting'] == "cena_down")$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')ORDER BY cena DESC" ;
						}
					}
					
					$rezultat = $polaczenie->query($query);
					$ile_transakcji = $rezultat->num_rows;
					if($ile_transakcji > 0)
					{

							$numer_transakcji = 1;
							if ($ile_transakcji != $ilosc_rekordow)echo "<a href ='historia.php' ><input type='submit' class='filtruj' value = 'Wszystkie dane' /></a>"."</br>";	

							
							echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left: auto; margin-right: auto;'><td>Numer transakcji</td>
							<td>Nazwa transakcji</td><td>Kwota transakcji</td><td>Data transakcji</table>";

							while ($row = $rezultat -> fetch_assoc())
							{
								$kategoria = $row['kategoria'];
								$cena = $row['cena']; if($cena>0)$cenaaaaaaaaaa = '<span style="color:green">'.'+'.$cena.'</span>'; else $cenaaaaaaaaaa = '<span style="color:red">'.$cena.'</span>';
								$data = $row['data'];
								//echo "<table border = '1'><tr><td>5</td></tr><table>";
								echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left: auto; margin-right: auto;'><td>".$numer_transakcji."</td><td>".$kategoria."</td><td>".$cenaaaaaaaaaa."</td><td>".$data."</td></tr></table>";
								$numer_transakcji++; 
								}
					}
					else 
					{
						echo "<a href ='historia.php' ><input type='submit' class='filtruj' value = 'Wszystkie dane' /></a>"."</br>";	
						echo $brak_danych_w_danym_czasie = '</br>'.'<span style="color:red">Nie znaleziono pasujących rekordów!</span>'.'</br>';
						unset($brak_danych_w_danym_czasie);
					}
				}
				
				
				else //jeśli nikt nie szuka i nie istnieją zmienne od i do to pokazywać normalnie wszystkie transakcje
				{
							$numer_transakcji = 1;
							echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed;margin-left: auto; margin-right: auto;'><td>Numer transakcji</td>
							<td>Nazwa transakcji</td><td>Kwota transakcji</td><td>Data transakcji</td><td>Operacja</td></table>";
							while ($row = $rezultat -> fetch_assoc())
							{
								$kategoria = $row['kategoria'];
								$cena = $row['cena']; if($cena>0)$cenaaaaaaaaaa = '<span style="color:green">'.'+'.$cena.'</span>'; else $cenaaaaaaaaaa = '<span style="color:red">'.$cena.'</span>';
								$_SESSION['cena'] = $cena;
								$data = $row['data'];
								$id_jedynej_transakcji = $row['id_transakcji'];
								
								//echo "<table border = '1'><tr><td>5</td></tr><table>";
								echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed; margin-left: auto; margin-right: auto;'><td>".$numer_transakcji."</td><td>".$kategoria."</td><td>".$cenaaaaaaaaaa."</td><td>".$data."</td>
								
								<td><form action = 'delete.php' method = 'post'>
									<input type = 'hidden' name = 'id' value = '$id' >
									<input type = 'hidden' name = 'kategoria' value = '$kategoria' >
									<input type = 'hidden' name = 'cena' value = '$cena' >
									<input type = 'hidden' name = 'data' value = '$data' >
									 <input type='submit' name='delete' class='delette' value = 'Usuń'>  
								</form>
								
								<form  action = 'updatedata.php' method = 'post'>
									<input type = 'hidden' name = 'id' value = '$id' >
									<input type = 'hidden' name = 'kategoria' value = '$kategoria' >
									<input type = 'hidden' name = 'cena' value = '$cena' >
									<input type = 'hidden' name = 'data' value = '$data' >
									 <input type='submit' name='delete'  class='editt' value = 'Edytuj'> </td>
								</form>
								</tr></table>";
								
								
								$numer_transakcji++; 
							}
							
				}
				
			}
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