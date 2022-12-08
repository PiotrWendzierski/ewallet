<?php
	session_start();
	$wszystko_ok = true;
	//czy jest wpisana kategoria
	if((isset($_POST['kategoria']))&&($_POST['kategoria'] ==""))
	{
		$wszystko_ok = false;
		$kategoria = $_POST['kategoria'];
		$e_kategoria = '</br>'.'<span style="color:red">Wprowadź kategorię!</span>'.'</br>';
	}
	//czy jest wpisana cena i czy jest rozna od zera
	if((isset($_POST['cena']))&&(($_POST['cena'] ==  0)|| ($_POST['cena']=="")))
	{
		$wszystko_ok = false;
		//$cena = $_POST['cena'];
		$e_cena= '</br>'.'<span style="color:red">Wprowadź cenę (cena nie może być różna od zera)!</span>'.'</br>';
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - dodaj transakcję</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
</head>
<body>
<div id="container">
	<div id="title">
		eWallett
	</div> </br></br>
	<div id="formularz" >
		<form method="post" >
			Wpisz kategorię<input type="text" name="kategoria"> </br>
			<?php
				if(isset($e_kategoria))
				{
					echo $e_kategoria;
					unset ($e_kategoria);
				}
			?></br>
			Wpisz cenę<input type="number" name="cena">  </br>
			<?php
				if(isset($e_cena))
				{
					echo $e_cena;
					unset($e_cena);
				}
			?></br>
			Data <input type="date" name="data_transakcji">  </br></br>
			Zatwierdź <input type="submit">
		</form>

	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>