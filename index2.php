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
	<div id="meni">		
		<div class="option"><a href="index2.php">Ekran główny</a></div>
		<div class="option"><a href="dodawanie.php">Wprowadzanie transakcji</a></div>
		<div class="option"><a href="historia.php">Historia portfela</a></div>
		<div class="option"><a href="wyloguj.php">Wyloguj</a></div>
		<div style="clear:both;"></div>
	</div>
	<div id="dashboard">
		<div class="kafel">
		<?php
			echo "Obecny stan portfela"."</br></br>";
			if(isset($_SESSION['stan']))
		  {
				$stan = $_SESSION['stan'];
				echo $stan." zł";
			}
			else 
		  {				
				$stan = $_POST['stan'];
				$_SESSION['stan'] = $stan;
				echo $stan." zł";
			}
		?>
		</div>
		<div class="kafel">
		Ostatnia transakcja: </br></br>
		<?php
		if(isset($_SESSION['data_transakcji']))
		  {
				echo "Dnia: ".$_SESSION['data_transakcji']."</br>";
			}
			else 
		  {				
				echo "Brak danych";
			}
		?>
		</div>
		<div style="clear:both"></div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>