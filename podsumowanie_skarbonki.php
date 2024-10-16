<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}

	require_once "connect.php";
	$cel_oszczednosci = $_SESSION['cel_oszczednosci'];
	$potrzebna_ilosc = $_SESSION['potrzebna_ilosc'];
	$skarbonka = $_SESSION['skarbonka'];
	if(isset($_SESSION['kwota_przeznaczona']))
	{
		$kwota_przeznaczona = $_SESSION['kwota_przeznaczona'];
		$stan_skarbonki = $skarbonka+$kwota_przeznaczona;
		$data_transakcji_skarbonki = $_SESSION['data_transakcji_skarbonki'];
	}
	
	//zrobić, ze usuniecie celu skarbonki zwieksza stan konta, jeśli usuwa transakcje skarbonki
	//i gdzieś tam zrobić unset kwoty przeznaczonej i to co ponizej ten komentarz!!!
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
		<li><a class="rejestraja"><i class="icon-home"></i>Ekran główny</a></li>
		<li><a class="rejestraja"><i class="icon-plus-circled"></i>Nowa transakcja</a></li>
		<li><a class="rejestraja"><i class="icon-history"></i>Historia portfela</a></li>
		<li><a class="rejestraja"><i class="icon-bank"></i>Skarbonka</a>
					<ul>
						<li><a class="rejestraja" >Dodaj transakcję</a></li>
						<li><a class="rejestraja" >Historia skarbonki</a></li>
					</ul>
				</li>
				<li><a class="rejestraja" href="#"><i class="icon-chart-bar"></i>Wykresy</a>
					<ul>
						<li><a class="rejestraja" >Kategorie wydatków (ilościowy)</a></li>
						<li><a class="rejestraja" >Kategorie wydatków (kwotowy)</a></li>
						<li><a class="rejestraja" >Kategorie przychodów (ilościowy)</a></li>
						<li><a class="rejestraja" >Kategorie przychodów (kwotowy)</a></li>
						<li><a class="rejestraja" >Stan portfela</a></li>
					</ul>
		</li>
		<li><a class="rejestraja" href="wyloguj.php"><i class="icon-logout"></i>Wyloguj</a></li>
	</ol>
	</div>
	<div id="z" style="min-height:600px;">
	<div id="formularz">
		</br>Podsumowanie:</br></br>
		<?php
			 try
			 {
				$polaczenie = new mysqli ($host, $db_user, $db_password, $db_name);
				if($polaczenie->connect_errno!=0)
			   {
					throw new Exception(mysqli_connect_errno());
			     }
				 else 
				 {
					 $id = $_SESSION['id'];
					 if($polaczenie->query("UPDATE uzytkownicy SET cel_oszczednosci = '$cel_oszczednosci' WHERE id = '$id'"))
					 {
						 ;
					 }
					 else 
					{
						throw new Exception($polaczenie->error);
					}
					if($polaczenie->query("UPDATE uzytkownicy SET potrzebna_ilosc = '$potrzebna_ilosc' WHERE id = '$id'"))
					 {
						 ;
					 }
					 else 
					{
						throw new Exception($polaczenie->error);
					}
					if(isset($kwota_przeznaczona))
					{
						if($polaczenie->query("UPDATE uzytkownicy SET skarbonka = '$stan_skarbonki' WHERE id = '$id'"))
						 {
							 
							 $a = "INSERT INTO transakcji_skarbonki VALUES (NULL, '$id' , '$data_transakcji_skarbonki' , '$kwota_przeznaczona' )";
							 if ($polaczenie->query($a))
							{
								;
							}
							else echo "Nie dodano nic";
							$stan_konta = $_SESSION['stan_konta'];
							$stan_konta = $stan_konta-$kwota_przeznaczona;
							$b = "UPDATE uzytkownicy SET stan_konta = '$stan_konta' WHERE id='$id'";
							if($polaczenie->query($b))
							{
								;
							}
							else echo "Nie dodano nic";
						}
						else 
						{
						throw new Exception($polaczenie->error);
						}
					}
				 }
				 $polaczenie -> close();

			 }
			 catch (Exception $e)
			  {
					echo $e;
			  }
			  
			  
			  if($cel_oszczednosci == 'brak')
			  {
				  echo "Nie ustaliłeś jeszcze celu zbieraniny."."</br>";
				  echo '<a href="skarbonka.php"></br><input type="submit" class="submitrej" value="Dodaj cel zbieraniny"></a>';
			  }
			  
			 else if (($skarbonka == 0)&& !(isset($kwota_przeznaczona)))//tutaj coś dopisać aby po dodanie transakcji skarbonki pojawiał robił sie ten else ponizej!!!!
			 {
				 echo "Cel zbieraniny: ".$cel_oszczednosci."</br></br>";
				 echo "Potrzebujesz: ".$potrzebna_ilosc."</br></br>";
				 echo '<a href="index2.php"><input type="submit" class="submitrej" value="Wróć na stronę główną"></a>'."</br>";
				 
			 }
			 else 
			 {
				if($kwota_przeznaczona<0)
				{
					echo "Stan skarbonki zmniejszył się o: ".$kwota_przeznaczona."</br></br>";
				}
				else
				{
					echo "Fajnie! Dodajesz do swojej skarbonki: ".$kwota_przeznaczona."</br></br>";
				}
				 echo '<a href="index2.php"><input type="submit" class="submitrej" value="Wróć na stronę główną"></a>';			 
			 }
		?>
		</br><br>
	</div></div><div id="footer">Wszelkie prawa zastrzeżone

</div>
</body>