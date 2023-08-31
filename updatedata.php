<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
	header('Location: login.php');
	exit();
}
if(!isset($_SESSION['stan_konta']))
{
	header('Location: index.php');
	exit();
}
if(!isset($_POST['id']))
{
	header('Location: historia.php');
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
	<div id="formularz" >
	
	
	
	
	<?php
	$id = $_POST['id'];
	$kategoria = $_POST['kategoria'];
	$cena = $_POST['cena'];
	$data = $_POST['data'];
	$zmiana = "wplyw";
	$_SESSION['stan_konta_przed_edytowaniem'] = $_SESSION['stan_konta']-$cena;

	
	if($cena<0)$zmiana = "wyplyw";
	
	$connection = mysqli_connect("localhost", "root", "");
	$db = mysqli_select_db($connection, 'ewallet');
	
	$query = "SELECT * FROM transakcje WHERE id = '$id' AND kategoria = '$kategoria' AND cena = '$cena' AND data = '$data' AND wplywwyplyw = '$zmiana' LIMIT 1";
	$query_run = mysqli_query($connection, $query);
	
	//tutaj odczytanie id jednej jedynej transakcji
	
	$rezultat = $connection->query($query);
	$ile_transakcji = $rezultat->num_rows;
	if($ile_transakcji>0)
	{
		$wiersz = $rezultat->fetch_assoc();
		$id_jedynej_transakcji = $wiersz['id_transakcji'];
		$_SESSION['id_jedynej_transakcji'] = $id_jedynej_transakcji;
	}
	
	//if($query_run)
	//{
		/*echo "<form action = '' method='post'  >
			</br>
			<input type = 'hidden' name = 'id' value = '$id'>
			Wpisz kategorię<input type='text' name='kategoria' value = '$kategoria' > </br></br>
			<input type = 'hidden' name = 'kategoriaa' value = '$kategoria'>.
			
			Wpisz cenę<input type='number' name='cena' value = '$cena' >  </br>
			<input type = 'hidden' name = 'cenaa' value = '$cena' >
			</br>
			Data <input type='date' name='data' value = '$data' >  </br>
			<input type = 'hidden' name = 'dataa' value = '$data' >
			</br>
			<input type='submit' name = 'edit' value = 'EDYTUJ'> </br></br> <a href = 'historia.php' >ANULUJ</a>
			<input type = 'hidden' name = 'zmiana' value = '$zmiana' >
			
		</form>";
		*/
		if(isset($_POST['edit']))
		{
			//echo $kategoria."</br>"; echo $cena."</br>"; echo $data."</br>";echo $id."</br>";echo $zmiana."</br>";
			$wszystko_ok = true;
			if ($kategoria == "")
		 {
				$wszystko_ok = false;
				$e_kategoria = '</br>'.'<span style="color:red">Wprowadź kategorię!</span>'.'</br>';
			}
			if(($cena == "")|| ($cena == 0))
			{
				$wszystko_ok = false;
				$e_cena= '</br>'.'<span style="color:red">Wprowadź cenę (cena nie może być równa  zeru)!</span>'.'</br>';
			}
			
			
			if (($_SESSION['stan_konta_przed_edytowaniem'] + $cena)<0)
		 {
				$wszystko_ok = false;
				$e_cena2= '</br>'.'<span style="color:red">Cena nie może być większa niz obecny stan konta!</span>'.'</br>';
				if($cena == "")
				{
					$wszystko_ok = false;
					$e_cena2 =  '</br>'.'<span style="color:red">Cena nie może być pusta!</span>'.'</br>';
				}
			}
			if($data == "")
		 {
				$wszystko_ok = false;
				$e_data = '</br>'.'<span style="color:red">Wprowadź datę transakcji!</span>'.'</br>';
		    }
			$dataczas = new DateTime();
			$koniec = DateTime::createFromFormat('Y-m-d', $data);
			if($dataczas<$koniec)
			{
				$wszystko_ok = false;
				$e_data2 = '</br>'.'<span style="color:red">Wprowadź datę dzisiejszą lub z przeszłości!</span>'.'</br>';
			}
			
			$stan_konta_po_edycji = $_SESSION['stan_konta_przed_edytowaniem'] +$cena;
			
		}
		else 
		{
			;
		}
		
		if((isset($wszystko_ok))&&($wszystko_ok == true))
		{
			$id_jedynej_transakcji = $_SESSION['id_jedynej_transakcji'];
			//echo $kategoria."</br>"; echo $cena."</br>"; echo $data."</br>";echo $id."</br>";echo $zmiana."</br>";
			//teraz updatowanie jednego konkretnego rekordu w tabeli transakcje
			$query1 = "UPDATE transakcje SET kategoria = '$kategoria', cena = '$cena', data = '$data', wplywwyplyw = '$zmiana' WHERE id = '$id' AND 
			id_transakcji = '$id_jedynej_transakcji' ";
			$query_run = mysqli_query($connection, $query1);
			$query2= "UPDATE uzytkownicy SET stan_konta = '$stan_konta_po_edycji' WHERE id='$id'";
			$query_run2 = mysqli_query($connection,$query2);
			if($query_run)
			{
				if($query_run2)
				{
					echo $_SESSION['stan_konta_przed_edytowaniem']."</br>";
					echo $stan_konta_po_edycji;
					//header("Location: historia.php");
				}
				else echo "no";
			}
			
		}
	//}
	//else
	//{
	//	echo "No record found";
	//}
	?>
	<form action = '' method='post'  >
			</br>
			
			
			<input type = 'hidden' name = 'id' value =  <?php echo $id ?>>
			Wpisz kategorię<input type='text' name='kategoria' value = <?php echo $kategoria ?> > </br>
			<input type = 'hidden' name = 'kategoriaa' value = <?php echo $kategoria ?>>
			<?php
				if(isset($e_kategoria))
				{
					echo $e_kategoria;
					unset ($e_kategoria);
				}
			?></br>
			Wpisz cenę<input type='number' name='cena' value = <?php echo $cena ?> >  </br>
			<input type = 'hidden' name = 'cenaa' value = <?php echo $cena ?> >
			<?php
				if(isset($e_cena))
				{
					echo $e_cena;
					unset ($e_cena);
				}
				if(isset($e_cena2))
				{
					echo $e_cena2;
					unset($e_cena2);
				}
			?> </br>
			Data <input type='date' name='data' value = <?php echo $data ?> >  </br>
			<input type = 'hidden' name = 'dataa' value = <?php echo $data ?> >
			<?php
			if(isset($e_data))
			{
				echo $e_data;
				unset($e_data);
			}
			if(isset($e_data2))
			{
				echo ($e_data2);
				unset ($e_data2);
			}
			?>
			</br>
			<input type='submit' name = 'edit' value = 'EDYTUJ'> </br></br> <a href = 'historia.php' >ANULUJ</a>
			<input type = 'hidden' name = 'zmiana' value = <?php echo $zmiana ?> >
			
		</form>
	
	
	
	
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>