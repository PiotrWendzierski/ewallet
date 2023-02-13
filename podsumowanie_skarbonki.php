<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	require_once "connect.php";
	$cel_oszczednosci = $_SESSION['cel_oszczednosci'];
	$potrzebna_ilosc = $_SESSION['potrzebna_ilosc'];
	$skarbonka = $_SESSION['skarbonka'];
	if(isset($_SESSION['kwota_przeznaczona']))
	{
		$kwota_przeznaczona = $_SESSION['kwota_przeznaczona'];
		$stan_skarbonki = $skarbonka+$kwota_przeznaczona;
	}
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
		<li><a href="skarbonka.php">Skarbonka</a></li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	
	<div id="pole">
		</br></br>Podsumowanie:</br></br>
		<?php
			 try
			 {
				$polaczenie = new mysqli ($host, $db_user, $db_password, $db_name);
				if($polaczenie->connect_errno!=0)
			   {
					throw new Exception(mysqli_connect_errno());
			     }
				 else 
				 {
					 $id = $_SESSION['id'];
					 if($polaczenie->query("UPDATE uzytkownicy SET cel_oszczednosci = '$cel_oszczednosci' WHERE id = '$id'"))
					 {
						 ;
					 }
					 else 
					{
						throw new Exception($polaczenie->error);
					}
					if($polaczenie->query("UPDATE uzytkownicy SET potrzebna_ilosc = '$potrzebna_ilosc' WHERE id = '$id'"))
					 {
						 ;
					 }
					 else 
					{
						throw new Exception($polaczenie->error);
					}
					if(isset($kwota_przeznaczona))
					{
						if($polaczenie->query("UPDATE uzytkownicy SET skarbonka = '$stan_skarbonki' WHERE id = '$id'"))
						 {
							 ;
						}
						else 
						{
						throw new Exception($polaczenie->error);
						}
					}
				 }
				 $polaczenie -> close();

			 }
			 catch (Exception $e)
			  {
					echo $e;
			  }
			 if (($skarbonka == 0)&& (!isset($kwota_przeznaczona)))
			 {
				 echo "Cel zbieraniny: ".$cel_oszczednosci."</br></br>";
				 echo "Potrzebujesz: ".$potrzebna_ilosc."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';
			 }
			 else 
			 {
				 echo "Fajnie! Dodajesz do swojej skarbonki: ".$kwota_przeznaczona."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';			 
			 }
		?>
		</br><br>
	</div>
</div>
</body>