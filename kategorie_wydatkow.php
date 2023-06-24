<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
	header('Location: login.php');
	exit();
}
if(!isset($_SESSION['stan_konta'])|| ($_SESSION['stan_konta'] ==false))
{
	header('Location: index.php');
	exit();
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
		<li><a href="#">Skarbonka</a>
					<ul>
						<li><a href="skarbonka.php">Dodaj transakcję</a></li>
						<li><a href="historia_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
		<li><a href="wyloguj.php">Wyloguj</a></li>
	</ol>
	</div>
	<div id="kategorie_wydatkow">
	<?php
	require_once "connect.php";
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
			 $sql = "SELECT * FROM kategorie WHERE id = '$id'";
			 $rezultat = $polaczenie -> query ($sql);
			 $ile_kategorii = $rezultat -> num_rows;
			 if($ile_kategorii !=0)
			 {
				 while($row = $rezultat -> fetch_assoc())
				 {
					 $kategoria = $row['kategoria'];
					 $ilosc_transakcji = $row['ilosc_transakcji'];
					 echo $kategoria."   ".$ilosc_transakcji."</br>";
				 }
			 }
			 else echo "Brak wydatków !";
			 
		 }
		 $polaczenie -> close();
	}
	catch (Exception $e)
	{
		echo $e;
	 }
	
	?>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>