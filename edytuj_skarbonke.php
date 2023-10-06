<?php

	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	
	if($_SESSION['cel_oszczednosci'] == 'brak')
	{
		header('Location: index2.php');
		exit();
	}
	
		if(isset( $_POST['cel_oszczednosci']))
	{
		$wszystko_ok = true;
		
		if($_POST['cel_oszczednosci'] == "")
		{
			$wszystko_ok = false;
			$e_cel_oszczednosci = '</br>'.'<span style="color:red">Wprowadź cel skarbonki!</span>'.'</br>';
		}
		if(($_POST['potrzebna_ilosc'] == 0)|| ($_POST['potrzebna_ilosc'] == "")||  ($_POST['potrzebna_ilosc'] < 0))
		{
			$wszystko_ok = false;
			$e_potrzebna_ilosc= '</br>'.'<span style="color:red">Wprowadź kwotę większą niż zero!</span>'.'</br>';
		}
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
	<div id="pole">
	</br></br>Wpisz cel twoich oszczędności: </br></br>
		<form name = 'edycja' action = '' method='post'>
			<input type='text' name='cel_oszczednosci'  value = <?php echo $_SESSION['cel_oszczednosci'];?>> </br>
			<?php
			if(isset($e_cel_oszczednosci))
			{
				echo $e_cel_oszczednosci;
				unset($e_cel_oszczednosci);
			}
			?>
			</br>Wpisz ile potrzebujesz pieniędzy: </br></br>
			<input type='number' name='potrzebna_ilosc' value = <?php echo $_SESSION['potrzebna_ilosc']; ?>> </br></br>
			
			<?php
			if(isset($e_potrzebna_ilosc))
			{
				echo $e_potrzebna_ilosc;
				unset($e_potrzebna_ilosc);
			}
			?>
			<input type='submit' value='Zaczynamy'>
		</form>
		</br><br>
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
				if((isset($wszystko_ok))&& ($wszystko_ok == true))
				{
					$id = $_SESSION['id'];
					$cel_oszczednosci = $_POST['cel_oszczednosci'];
					$potrzebna_ilosc = $_POST['potrzebna_ilosc'];
					if($polaczenie->query("UPDATE uzytkownicy SET cel_oszczednosci = '$cel_oszczednosci', potrzebna_ilosc = '$potrzebna_ilosc' WHERE id= '$id'"))
					{
						header('Location: index2.php');
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
				}
				$polaczenie->close();
			}
		}
		catch (Exception $e)
		{
			echo $e;
		  }
	
	?>
	
	</div>
</div>
</body>
</html>