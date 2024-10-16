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


$wszystko_ok = true;
$connection = mysqli_connect("db", "php_docker", "password");
$db = mysqli_select_db($connection, "ewallet");
	
	if(isset($_POST['kategoria']))
	{
		$id = $_POST['id'];
		$cena = $_POST['cena'];
		$kategoria = $_POST['kategoria'];
		$data = $_POST['data'];
		$zmiana = "wplyw";
		if($cena <0)$zmiana = "wyplyw";

		setcookie('cena', $cena);
		setcookie('kategoria', $kategoria);
		setcookie('zmiana',$zmiana);
		setcookie('data',$data);

	}
	if(isset($_COOKIE['cena']))
	{
		$pierwsza_cena = $_COOKIE['cena'];
		$pierwsza_kategoria = $_COOKIE['kategoria'];
		$pierwsza_zmiana = $_COOKIE['zmiana'];
		$pierwsza_data = $_COOKIE['data'];
	}

	if(isset($pierwsza_data))
	{
		setcookie('cena', $pierwsza_cena);
		setcookie('kategoria', $pierwsza_kategoria);
		setcookie('zmiana',$pierwsza_zmiana);
		setcookie('data',$pierwsza_data);
	}
	
	if(isset($_POST['edit']))
	{
		$wszystko_ok = true;
		$query0 = "SELECT * FROM uzytkownicy WHERE id='$id' ";
		$wiersz0 = mysqli_query($connection, $query0);
		$rezultat = mysqli_fetch_assoc($wiersz0);
		$stan_konta = $rezultat ['stan_konta'];				
		$stan_konta_przed_edytowaniem = $stan_konta - $pierwsza_cena;
		
		//teraz pierwsza flaga, ze jak nowa cena przekracza stan obecnego portfela to wyskakuje błąd
		if($cena =="") $cena =0;
		if(($cena == "")|| ($cena == 0))
		{
			$wszystko_ok = false;
			$e_cena= '</br>'.'<span style="color:red">Wprowadź cenę (cena nie może być równa  zeru)!</span>'.'</br>';
		}
		if($stan_konta_przed_edytowaniem +$cena < 0)
		{
			$wszystko_ok = false;
			$e_cena2= '</br>'.'<span style="color:red">Nie masz tyle pieniędzy!</span>'.'</br>';
		}
		if ($kategoria == "")
		{
			$wszystko_ok = false;
			$e_kategoria = '</br>'.'<span style="color:red">Wprowadź kategorię!</span>'.'</br>';
		}
		//czy wpisana jest data
		if($data == "")
		{
			$wszystko_ok = false;
			$e_data = '</br>'.'<span style="color:red">Wprowadź datę transakcji!</span>'.'</br>';
		}
		//czy data nie jest z przeszłości
		$dataczas = new DateTime();
		$koniec = DateTime::createFromFormat('Y-m-d', $data);
		if($dataczas<$koniec)
		{
			$wszystko_ok = false;
			$e_data2 = '</br>'.'<span style="color:red">Wprowadź datę dzisiejszą lub z przeszłości!</span>'.'</br>';
		}
		
		if((isset($wszystko_ok))&&($wszystko_ok == true))
		{
			$stan_konta_po_edytowaniu = $stan_konta_przed_edytowaniem + $cena;
			//zmiana stanu konta uzytkownika
			$query1 = "UPDATE uzytkownicy SET stan_konta = '$stan_konta_po_edytowaniu' WHERE id='$id'";
			$query_run1 = mysqli_query($connection, $query1 );
			//zmiana w transakcjach (wyciągniecie id jedynej transakcji i potem edytowanie tej jedynej transakcji
			$query2= "SELECT * FROM transakcje WHERE id='$id' AND kategoria = '$pierwsza_kategoria' AND cena = '$pierwsza_cena' AND 
			data = '$pierwsza_data' AND wplywwyplyw = '$pierwsza_zmiana' LIMIT 1";
			$rezultat2 = $connection->query($query2);
			$wiersz2 = $rezultat2 -> fetch_assoc();
			$id_jedynej_transakcji = $wiersz2['id_transakcji'];
			$query3 = "UPDATE transakcje SET kategoria = '$kategoria' , cena = '$cena', data = '$data', wplywwyplyw = '$zmiana' WHERE id_transakcji = '$id_jedynej_transakcji'";
			$query_run3 = mysqli_query($connection, $query3);
			
			//zmiana w kategoriach, jeśli była zmianiona TYLKO data
			if(($pierwsza_cena == $cena)&&($pierwsza_kategoria == $kategoria))
			{
				//po prostu zadna zmiana
				$query4 = "SELECT * FROM transakcje";
				$query_run4 = mysqli_query($connection, $query4);
				$query_run5 = mysqli_query($connection, $query4);
			}
			//jeśli była inna nazwa kategorii
			else if(($pierwsza_kategoria != $kategoria)&& ($pierwsza_cena == $cena))
			{
				$query4 = "SELECT * FROM kategorie WHERE id = '$id' AND kategoria = '$kategoria' AND wplywwyplyw = '$zmiana'";
				$rezultat4 = $connection->query($query4);
				$ile_takowych_kategorii = $rezultat4->num_rows;
				//jęsli są jakies takie kategorie, to zobaczyc ile tam transakcji i dodać jedną i z tej starej odjąc, a jesli jest tam jedna tylko to usunąć tą kategorię
				if($ile_takowych_kategorii == 1)
				{
					$wiersz4 = $rezultat4->fetch_assoc();
					$ilosc_transakcji_w_tej_kategorii = $wiersz4['ilosc_transakcji'];
					$id_jedynej_kategorii = $wiersz4['numer_kategorii'];
					$ilosc_transakcji_w_tej_kategorii = $ilosc_transakcji_w_tej_kategorii+1;
					
					//teraz zapytanie, aby tą jedną kategorie aktualizowało ze ilosc transakcji +1
					$query5 = "UPDATE kategorie SET kategoria = '$kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_tej_kategorii', wplywwyplyw = '$zmiana'
					WHERE numer_kategorii = '$id_jedynej_kategorii'";
					$query_run4 = mysqli_query($connection, $query5);
					//tera napisać $query_run5, aby albo usuwało starą kategrię, albo ilosc transakcji w niej zmiejszało o 1
					
					$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
					$rezultat6 = $connection->query($query6);
					$wiersz6 = $rezultat6->fetch_assoc();
					$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
					$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
					$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
					//teraz jeśli będzie 0 to całkiem usunąc kategorie, a jak nie to pomniejszyć transakcje o 1
					if($ilosc_transakcji_w_starej_kategorii == 0)
					{
						$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
					else 
					{
						$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
						wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}

				}
				else
				{
					//tu napisać, ze jak jest edytwanie i jest nowa kategoria to dodać nową do tabeli kategorie i ilosc transakcji w niej =1 a w starej odjąć jedną
					//i jesli była 1 transakcja to usunac starą kategorię, a jak wiecej to pomniejsyc ilosc transakcji o 1
					
					//tutaj dodwanie nowej kategorii
					$query5 = "INSERT INTO kategorie VALUES (NULL, '$kategoria', '$id', 1, '$zmiana')";
					$query_run4 = mysqli_query($connection, $query5);
					//tutaj odczytanie ile bylo transakcji w starej kategorii i albo pomniejszenie o 1 albo usuniecie 
					$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
					$rezultat6 = $connection->query($query6);
					$wiersz6 = $rezultat6->fetch_assoc();
					$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
					$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
					$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
					//tutaj albo usniecie kategorii albo pomniejszenie ilosci transakcji w tej kategorii
					if($ilosc_transakcji_w_starej_kategorii == 0)
					{
						$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
					else 
					{
						$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
						wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
				}
			}
			else if(($pierwsza_kategoria == $kategoria)&& ($pierwsza_cena != $cena))
			{
				$query4 = "SELECT * FROM kategorie WHERE id = '$id' AND kategoria = '$kategoria' AND wplywwyplyw = '$zmiana'";
				$rezultat4 = $connection->query($query4);
				$ile_takowych_kategorii = $rezultat4->num_rows;
				
				//jęsli są jakies takie kategorie, to zobaczyc ile tam transakcji i dodać jedną i z tej starej odjąc, a jesli jest tam jedna tylko to usunąć tą kategorię
				if($ile_takowych_kategorii == 1)
				{
					$wiersz4 = $rezultat4->fetch_assoc();
					$ilosc_transakcji_w_tej_kategorii = $wiersz4['ilosc_transakcji'];
					$id_jedynej_kategorii = $wiersz4['numer_kategorii'];
					$ilosc_transakcji_w_tej_kategorii = $ilosc_transakcji_w_tej_kategorii+1;
					
					//teraz zapytanie, aby tą jedną kategorie aktualizowało ze ilosc transakcji +1
					$query5 = "UPDATE kategorie SET kategoria = '$kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_tej_kategorii', wplywwyplyw = '$zmiana'
					WHERE numer_kategorii = '$id_jedynej_kategorii'";
					$query_run4 = mysqli_query($connection, $query5);
					//tera napisać $query_run5, aby albo usuwało starą kategrię, albo ilosc transakcji w niej zmiejszało o 1
					
					$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
					$rezultat6 = $connection->query($query6);
					$wiersz6 = $rezultat6->fetch_assoc();
					$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
					$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
					$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
					//teraz jeśli będzie 0 to całkiem usunąc kategorie, a jak nie to pomniejszyć transakcje o 1
					if($ilosc_transakcji_w_starej_kategorii == 0)
					{
						$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
					else 
					{
						$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
						wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}

				}
				else
				{
					//tu napisać, ze jak jest edytwanie i jest nowa kategoria to dodać nową do tabeli kategorie i ilosc transakcji w niej =1 a w starej odjąć jedną
					//i jesli była 1 transakcja to usunac starą kategorię, a jak wiecej to pomniejsyc ilosc transakcji o 1
					
					//tutaj dodwanie nowej kategorii
					$query5 = "INSERT INTO kategorie VALUES (NULL, '$kategoria', '$id', 1, '$zmiana')";
					$query_run4 = mysqli_query($connection, $query5);
					//tutaj odczytanie ile bylo transakcji w starej kategorii i albo pomniejszenie o 1 albo usuniecie 
					$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
					$rezultat6 = $connection->query($query6);
					$wiersz6 = $rezultat6->fetch_assoc();
					$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
					$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
					$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
					//tutaj albo usniecie kategorii albo pomniejszenie ilosci transakcji w tej kategorii
					if($ilosc_transakcji_w_starej_kategorii == 0)
					{
						$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
					else 
					{
						$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
						wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
						$query_run5 = mysqli_query($connection, $query7);
					}
				}
			}
				else
				{
					$query4 = "SELECT * FROM kategorie WHERE id = '$id' AND kategoria = '$kategoria' AND wplywwyplyw = '$zmiana'";
					$rezultat4 = $connection->query($query4);
					$ile_takowych_kategorii = $rezultat4->num_rows;
					
					//jęsli są jakies takie kategorie, to zobaczyc ile tam transakcji i dodać jedną i z tej starej odjąc, a jesli jest tam jedna tylko to usunąć tą kategorię
					if($ile_takowych_kategorii == 1)
					{
						$wiersz4 = $rezultat4->fetch_assoc();
						$ilosc_transakcji_w_tej_kategorii = $wiersz4['ilosc_transakcji'];
						$id_jedynej_kategorii = $wiersz4['numer_kategorii'];
						$ilosc_transakcji_w_tej_kategorii = $ilosc_transakcji_w_tej_kategorii+1;
						
						//teraz zapytanie, aby tą jedną kategorie aktualizowało ze ilosc transakcji +1
						$query5 = "UPDATE kategorie SET kategoria = '$kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_tej_kategorii', wplywwyplyw = '$zmiana'
						WHERE numer_kategorii = '$id_jedynej_kategorii'";
						$query_run4 = mysqli_query($connection, $query5);
						//tera napisać $query_run5, aby albo usuwało starą kategrię, albo ilosc transakcji w niej zmiejszało o 1
						
						$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
						$rezultat6 = $connection->query($query6);
						$wiersz6 = $rezultat6->fetch_assoc();
						$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
						$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
						$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
						//teraz jeśli będzie 0 to całkiem usunąc kategorie, a jak nie to pomniejszyć transakcje o 1
						if($ilosc_transakcji_w_starej_kategorii == 0)
						{
							$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
							$query_run5 = mysqli_query($connection, $query7);
						}
						else 
						{
							$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
							wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
							$query_run5 = mysqli_query($connection, $query7);
						}

					}
					else
					{
						//tu napisać, ze jak jest edytwanie i jest nowa kategoria to dodać nową do tabeli kategorie i ilosc transakcji w niej =1 a w starej odjąć jedną
						//i jesli była 1 transakcja to usunac starą kategorię, a jak wiecej to pomniejsyc ilosc transakcji o 1
						
						//tutaj dodwanie nowej kategorii
						$query5 = "INSERT INTO kategorie VALUES (NULL, '$kategoria', '$id', 1, '$zmiana')";
						$query_run4 = mysqli_query($connection, $query5);
						//tutaj odczytanie ile bylo transakcji w starej kategorii i albo pomniejszenie o 1 albo usuniecie 
						$query6 = "SELECT * FROM kategorie WHERE kategoria = '$pierwsza_kategoria' AND id = '$id' AND wplywwyplyw = '$pierwsza_zmiana'";
						$rezultat6 = $connection->query($query6);
						$wiersz6 = $rezultat6->fetch_assoc();
						$ilosc_transakcji_w_starej_kategorii = $wiersz6['ilosc_transakcji'];
						$ilosc_transakcji_w_starej_kategorii = $ilosc_transakcji_w_starej_kategorii -1;
						$id_jedynej_starej_kategorii = $wiersz6['numer_kategorii'];
						//tutaj albo usniecie kategorii albo pomniejszenie ilosci transakcji w tej kategorii
						if($ilosc_transakcji_w_starej_kategorii == 0)
						{
							$query7 = "DELETE FROM kategorie WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
							$query_run5 = mysqli_query($connection, $query7);
						}
						else 
						{
							$query7 = "UPDATE kategorie SET kategoria = '$pierwsza_kategoria', id='$id', ilosc_transakcji = '$ilosc_transakcji_w_starej_kategorii',
							wplywwyplyw = '$pierwsza_zmiana' WHERE numer_kategorii = '$id_jedynej_starej_kategorii'";
							$query_run5 = mysqli_query($connection, $query7);
						}
					}
				}
			
			if($query_run1)
			{
				if($query_run3)
				{
					if($query_run4)
					{
						if($query_run5)
						{
								header("Location:index2.php");
						}
						else echo "no1";
						
					}
					
					else echo "no3";
				}
				else echo "no3";
			}
			else
			{
				echo "no5";
			}
		}
	}
	

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-9677cda3/css/fontello.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-571ab779/css/fontello.css" type="text/css" / >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
</head>
<body>
<div id="container">
	<div id="title">
		<a class="rejestracja" href="login.php"><i class="icon-wallet"></i>eWallett</a>
	</div>
	<div id="meni">		
	<ol>
		<li><a class="rejestraja" href="index2.php"><i class="icon-home"></i>Ekran główny</a></li>
		<li><a class="rejestraja" href="dodawanie.php"><i class="icon-plus-circled"></i>Nowa transakcja</a></li>
		<li><a class="rejestraja" href="historia.php"><i class="icon-history"></i>Historia portfela</a></li>
		<li><a class="rejestraja" href="#"><i class="icon-bank"></i>Skarbonka</a>
					<ul>
						<li><a class="rejestraja" href="skarbonka.php">Dodaj transakcję</a></li>
						<li><a class="rejestraja" href="historia_skarbonki.php">Historia skarbonki</a></li>
					</ul>
				</li>
				<li><a class="rejestraja" href="#"><i class="icon-chart-bar"></i>Wykresy</a>
					<ul>
						<li><a class="rejestraja" href="kategorie_wydatkow.php">Kategorie wydatków (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wydatkowprocent.php">Kategorie wydatków (kwotowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywow.php">Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" href="kategorie_wplywowprocent.php">Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" href="stan_portfela.php">Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="formularz" >

	<form action = '' method='post'  >
			</br>
			<input type = 'hidden' name = 'id' value =  <?php echo $id ?>>
			<input type='text' class="dodawanie" name='kategoria' value = <?php echo $kategoria ?> > </br>
			<input type = 'hidden' name = 'kategoriaa' value = <?php echo $kategoria ?>>
			<?php
				if(isset($e_kategoria))
				{
					echo $e_kategoria;
					unset ($e_kategoria);
				}
			?></br>
			<input type='number'  class="dodawanie" name='cena' value = <?php echo $cena ?> >  </br>
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
			 <input type='date' class="dodawanie" name='data' value = <?php echo $data ?> >  </br>
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
			<input type='submit' class="submitrej" name = 'edit' value = 'EDYTUJ'></br></br>
			<input type = 'hidden' name = 'zmiana' value = <?php echo $zmiana ?> >
			
		</form>
	
	
	
	
	</div></div>
	<div id="footer">Wszelkie prawa zastrzeżone</div>
</div>
</body>

</html>