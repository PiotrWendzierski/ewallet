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
		<a href="dodawanie.php">+</a>
	</div>
	<div id="dashboard">
		<div class="kafelek">
		<?php
			echo "Obecny stan portfela"."</br></br>";
			if(!isset($_SESSION['stan']))
		  {
				$stan = $_POST['stan'];
				$_SESSION['stan'] = $stan;
				echo $stan." zł";
			}
			else 
		  {				
				$stan = $_SESSION['stan'];
				echo $stan." zł";
			}
		?>
		</div>
		<div class="kafelek">Historia wydatków</div>
		<div style="clear:both"></div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>