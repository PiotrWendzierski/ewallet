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
	
	<div id="pole">
		</br></br>Podsumowanie:</br></br>
		<?php
			 try
			 {
				$polacznie = new mysqli ($host, $db_user, $db_password, $db_name);
				if($polaczenie->connect_errno!=0)
			   {
					throw new Exception(mysqli_connect_errno());
			     }

			 }
			 catch (Exception $e)
			  {
					echo $e;
			  }
			 if (($_SESSION['cel'] == true))
			 {
				 $_SESSION['cel_oszczednosci'] = $_POST['cel_oszczednosci'];
				 echo "Cel zbieraniny: ".$_POST['cel_oszczednosci']."</br></br>";
				 echo "Potrzebujesz: "."</br></br>";
				 $_SESSION['cel'] = false;
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