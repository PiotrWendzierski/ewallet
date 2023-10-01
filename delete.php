<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	if(!isset($_POST['delete']))
	{
		header('Location: historia.php');
		exit();
	}
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "ewallet");

if(isset($_POST['delete']))
{
	$wszystko_ok = true;
	//to jest id uzytkownika zalogowanego
	$id_user = $_SESSION['id'];
	$id = $_POST['id'];
	$kategoria =  $_POST['kategoria'];
	$cena  =  $_POST['cena'];
	
	$zmiana = "wplyw";
	if($cena < 0) $zmiana = "wyplyw";
	
	
	$data =  $_POST['data'];
	
	$query0 = "SELECT * FROM uzytkownicy WHERE id='$id_user' ";
	$wiersz2 = mysqli_query($connection, $query0);
	$rezultat2 = mysqli_fetch_assoc($wiersz2);
	//$stan_konta = $_SESSION['stan_konta'] - $cena;
	
	$stan_konta = $rezultat2 ['stan_konta'];
	$stan_konta = $stan_konta-$cena;
	
	//tu usuwanie rekordu z tabeli transakcje
	$query1 = "DELETE FROM transakcje WHERE id = '$id' AND kategoria = '$kategoria' AND cena = '$cena' AND data = '$data' AND wplywwyplyw = '$zmiana' LIMIT 1";
	$queryrun1 = mysqli_query($connection, $query1 );
	
	//tutaj updatowanie tabeli uzytkownicy, zeby stan konta zmienił się o us
	$query2 = "UPDATE uzytkownicy SET stan_konta = '$stan_konta' WHERE id  = '$id_user'";
	$queryrun2 = mysqli_query($connection, $query2);
	

	
	//tutaj praca z tabelą rekordy, aby usunięcie transakcji, albo pomniejszalo ilosc transakcji w poszczegolnej kategorii w tabeli KATEGORIE albo usuwało kategorię z tabeli
	$query3 = "SELECT * FROM kategorie WHERE kategoria = '$kategoria' AND id = '$id_user' AND wplywwyplyw = '$zmiana'";
	$wiersz = mysqli_query($connection, $query3);
	$rezultat = mysqli_fetch_assoc($wiersz);
	
	
	
	$ilosc_transakcji_w_tej_kategorii = $rezultat['ilosc_transakcji'];
	
	if($ilosc_transakcji_w_tej_kategorii >1)
	{
		$query4 = "UPDATE kategorie SET ilosc_transakcji = '$ilosc_transakcji_w_tej_kategorii' - 1 WHERE id = '$id_user' AND kategoria = '$kategoria' AND wplywwyplyw = '$zmiana' ";
		$queryrun4 = mysqli_query($connection, $query4);
	}
	else 
	{
		$query4 = "DELETE FROM kategorie WHERE id = '$id_user' AND kategoria = '$kategoria' AND wplywwyplyw = '$zmiana'";
		$queryrun4 = mysqli_query($connection, $query4);
	}
	
	if($queryrun1)
	{
		if($query2)
		{
			if($query4)
			{
				header("Location:historia.php");
				
			}
			else echo "no";
			
		}
		else
		{
			echo "no";
		}
	}
}
	else
	{
		;
	}

?>