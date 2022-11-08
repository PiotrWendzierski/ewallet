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
			 $ilosc_transakcji = $_SESSION['ilosc_transakcji'];
			 $ilosc_transakcji++;
			 $_SESSION['zmiana'] = $_POST['cena'];
			 
			 echo "Stan przed transakcją: ".$_SESSION['stan']."</br>";
			 
			 $_SESSION['stan'] = $_SESSION['stan'] + $_SESSION['zmiana'];
			 
			 echo "Zmieniono o: ".$_SESSION['zmiana']." PLN"."</br></br>";
			 echo '<a href="index2.php">Wróć na stronę główną</a>';
		
		
		?>
		</br><br>
	</div>
</div>
</body>