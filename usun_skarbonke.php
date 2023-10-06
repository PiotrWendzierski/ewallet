<?php

	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	
	if($_SESSION['cel_oszczednosci'] == 'brak')
	{
		header('Location: index2.php');
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
						<li><a href="historia_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	<div id="pole">
	<?php
	echo "Czy chcesz usunąć cel zbieraniny? Spowoduje to "."</br>"."trwałe usunięcie wszystkich transakcji i celu oszczędności!";
	
	echo "<form action = '' method = 'post'>
									<input type = 'submit' name = 'yes' value = 'TAK' >
									<input type = 'submit' name = 'no' value = 'NIE' >
				</form>";
				
	if(isset($_POST['yes']))
	{
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
					$brak = "brak";
					$ilosc = 0;
					$sql="UPDATE uzytkownicy SET skarbonka = '0', cel_oszczednosci = '$brak', potrzebna_ilosc = '0' WHERE id='$id'";
					$sql2 = "DELETE FROM transakcji_skarbonki WHERE id='$id'";
					$lacznie = $_SESSION['skarbonka'] + $_SESSION['stan_konta'];
					$sql3 = "UPDATE uzytkownicy SET stan_konta = '$lacznie' WHERE id = '$id'";
					$queryrun = mysqli_query($polaczenie, $sql );
					$queryrun2 = mysqli_query($polaczenie, $sql2 );
					$queryrun3 = mysqli_query($polaczenie, $sql3 );

					
					if($queryrun)
					{
						if($queryrun2)
						{
							if($queryrun3)
							{
								header('Location: index2.php');
								exit();
							}
						}
					}
					
					
				}
		}
		catch (Exception $e)
		{
			echo $e;
		  }
	}
	if(isset($_POST['no']))
	{
		header('Location: index2.php');
		exit();
	}
	
	?>
	</div>
</div>
</body>
</html>