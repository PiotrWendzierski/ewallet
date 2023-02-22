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
						<li><a href="podsumowanie_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
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
				$numer_transakcji = 1;
				while ($row = $rezultat -> fetch_assoc())
				{
					$kategoria = $row['kategoria'];
					$cena = $row['cena'];
					$data = $row['data'];
					//echo "<table border = '1'><tr><td>5</td></tr><table>";
					echo "<table border='1' rules='all' frame='none' style='width:100%;table-layout:fixed;'><td>".$numer_transakcji."</td><td>".$kategoria."</td><td>".$cena."</td><td>".$data."</td></tr></table>";
					$numer_transakcji++; 
				}

			}
			else echo "Brak transakcji. Zapraszamy do dodania danych do Twojego konta.";
		}
	}
	catch(Exception $e)
	{
		echo $e;
	}
	?>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>