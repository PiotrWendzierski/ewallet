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
				 }
				 $polaczenie -> close();

			 }
			 catch (Exception $e)
			  {
					echo $e;
			  }
			 if (($skarbonka == 0))
			 {

				 echo "Cel zbieraniny: ".$cel_oszczednosci."</br></br>";
				 echo "Potrzebujesz: ".$potrzebna_ilosc."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';
			 }
			 else 
			 {
				 $_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'];
				 $_SESSION['stan'] = $_SESSION['stan'] - $_SESSION['kwota_przeznaczona'] ;
				 $_SESSION['data_transakcji'] = $_POST['data_transakcji'];
				 echo "Fajnie! Dodajesz do swojej skarbonki: ".$_SESSION['kwota_przeznaczona']."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';			 
				 echo $cel_oszczednosci;
				 echo $potrzebna_ilosc;
				 echo $skarbonka;
			 }
		?>
		</br><br>
	</div>
</div>
</body>