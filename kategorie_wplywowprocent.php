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
			 $sql = "SELECT * FROM transakcje WHERE id = '$id' AND wplywwyplyw = 'wplyw' ";
			 $rezultat = $polaczenie -> query ($sql);
			 $ile_kategorii = $rezultat -> num_rows;
			 $laczna_ilosc_dodanej_kasy= 0;
			 if($ile_kategorii !=0)
			 {
				 //tutaj liczymi ile jest wydanej kasy łącznie
				 while($row = $rezultat -> fetch_assoc())
				 {
					 $kategoria = $row['kategoria'];
					 $ilosc_wplywu = $row['cena'];
					 $laczna_ilosc_dodanej_kasy = $laczna_ilosc_dodanej_kasy + $ilosc_wplywu;
					 
				 }
				 echo "Łącznie: ".$laczna_ilosc_dodanej_kasy;
				 //tutaj ile kasy wydanej w poszczególnej kategorii;
				 $sql2 = "SELECT * FROM kategorie WHERE id = '$id' AND wplywwyplyw = 'wplyw'";
				 $rezultat2 = $polaczenie -> query ($sql2);
				 
				 while($row2 = $rezultat2 -> fetch_assoc())
				 {
					 $kategoriaa = $row2['kategoria'];
					 $sql3 = "SELECT * FROM transakcje WHERE id ='$id' AND kategoria = '$kategoriaa' AND wplywwyplyw = 'wplyw' ";
					 $rezultat3 = $polaczenie -> query($sql3);
					 $ilosc_wplynietej_kasy = 0;
					 while($row3 = $rezultat3 -> fetch_assoc())
					 {
						 $cenaa = $row3['cena'];
						 $ilosc_wplynietej_kasy = $ilosc_wplynietej_kasy + $cenaa;
						 $procent = ($ilosc_wplynietej_kasy/$laczna_ilosc_dodanej_kasy)*100;
						 $procent = round($procent);
					 }
					 echo "</br>".$kategoriaa." ".$procent."%"."</br>";
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