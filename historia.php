<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
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
						<li><a href="histroria_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	
	
	<div id="sortowanie">
		<form action="" method = "GET">
			Wyszukaj po nazwie </br>
			<input type="text" name="search" value = "<?php if(isset($_GET['search'])){echo $_GET['search'];}?>"/> </br></br></br></br>
			<label>Od</label>
			<input type="date" name = "od" value="<?php if(isset($_GET['od'])){echo $_GET['od'];}?>"/> </br></br>
			<label>Do</label>
			<input type="date" name = "do" value="<?php if(isset($_GET['do'])){echo $_GET['do'];}?>"/> </br></br>

		
		</br></br>

			<input type="checkbox" name="wplywy" value="<?php if(isset($_GET['wplywy'])){echo $_GET['wplywy'] ;}?>"/> Wpływy </br>
			<input type="checkbox" name="wyplywy" value="<?php if(isset($_GET['wplywy'])){echo $_GET['wplywy'] ;}?>"> Wypływy </br></br>
			<input type="submit" value="Filtruj"></input>
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
			if ($ile_transakcji != 0)
			{
				//jeśli ktoś kliknął filtruj
				if(isset($_GET['od'] ))
				{
					//jeśli ktos na formularzu nie zaznaczył dat;
					$search = $_GET['search'];
					if ($_GET['od'] == "") $od = $_GET['od'] = "0000-01-01"; else $od = $_GET['od'];
					if ($_GET['do'] == "") $do = $_GET['do'] = "6000-01-01"; else $do = $_GET['do'];
					if($_GET['search'] == "") $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
					else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria='$search')" ;
					
					
					
					
					//jesli zaznaczył wpływy
					if ((isset($_GET['wplywy']))&& (!isset($_GET['wyplywy']))) 
					{	
						if(!isset($_GET['wyplywy']))
						{
							if ($search == "")
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0)" ;
							}
							else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena>0) AND(kategoria='$search')" ;
						}
						else
						{
							if($search == "")
							{
								$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
							}
							else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (kategoria = '$search')" ;
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
							}
							else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')AND (cena<0) AND(kategoria='$search')" ;
						}
						else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
					}		

					//jeśli zaznaczone wpływy i wypływy;
					if((isset($_GET['wpływy'])) && (isset($_GET['wyplywy'])))
					{
						if($search == "")
						{
							$query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id')" ;
						}
						else $query = "SELECT * FROM transakcje WHERE (data BETWEEN '$od' AND '$do') AND (id = '$id') AND(kategoria='$search')" ;
					}
					
					$rezultat = $polaczenie->query($query);
					$ile_transakcji = $rezultat->num_rows;
					if($ile_transakcji > 0)
					{
							echo "<a href ='historia.php' >[Wszystkie transakcje]</a>"."</br>";	
							
							$numer_transakcji = 1;
							echo $query;
							while ($row = $rezultat -> fetch_assoc())
							{
								$kategoria = $row['kategoria'];
								$cena = $row['cena'];
								$data = $row['data'];
								//echo "<table border = '1'><tr><td>5</td></tr><table>";
								echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed;'><td>".$numer_transakcji."</td><td>".$kategoria."</td><td>".$cena."</td><td>".$data."</td></tr></table>";
								$numer_transakcji++; 
								}
					}
					else 
					{
						echo $query;
						echo "<a href ='historia.php' >[Wszystkie transakcje]</a>"."</br>";	
						echo $brak_danych_w_danym_czasie = '</br>'.'<span style="color:red">Nie znaleziono pasujących rekordów!</span>'.'</br>';
						unset($brak_danych_w_danym_czasie);
					}
				}
				
				
				else //jeśli nikt nie szuka i nie istnieją zmienne od i do to pokazywać normalnie wszystkie transakcje
				{
							$numer_transakcji = 1;
							while ($row = $rezultat -> fetch_assoc())
							{
								$kategoria = $row['kategoria'];
								$cena = $row['cena'];
								$data = $row['data'];
								//echo "<table border = '1'><tr><td>5</td></tr><table>";
								echo "<table border='1' rules='all' frame='none' style='width:90%;table-layout:fixed;'><td>".$numer_transakcji."</td><td>".$kategoria."</td><td>".$cena."</td><td>".$data."</td></tr></table>";
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