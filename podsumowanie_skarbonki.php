<?php
	session_start();
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
			 
			 if (($_SESSION['skarbonka'] == true)&& ($_SESSION['oszczednosci'] == false))
			 {
				 $_SESSION['cel_oszczednosci'] = $_POST['cel_oszczednosci'] ;
				 $_SESSION['potrzebna_ilosc'] = $_POST['potrzebna_ilosc'] ;
				 echo "Cel zbieraniny: ".$_SESSION['cel_oszczednosci']."</br></br>";
				 echo "Potrzebujesz: ".$_SESSION['potrzebna_ilosc']."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';
			 }
			 else 
			 {
				 $_SESSION['kwota_przeznaczona'] = $_POST['kwota_przeznaczona'];
				 $_SESSION['stan'] = $_SESSION['stan'] - $_SESSION['kwota_przeznaczona'] ;
				 $_SESSION['data_transakcji'] = $_POST['data_transakcji'];
				 echo "Fajnie! Dodajesz do swojej skarbonki: ".$_SESSION['kwota_przeznaczona']."</br></br>";
				 echo '<a href="index2.php">Wróć na stronę główną</a>';
				 
			 }
		?>
		</br><br>
	</div>
</div>
</body>