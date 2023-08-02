<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "ewallet");

if(isset($_POST['delete']))
{
	//to jest id uzytkownika zalogowanego
	$id_user = $_SESSION['id'];
	$id = $_POST['id'];
	$kategoria =  $_POST['kategoria'];
	$cena  =  $_POST['cena'];
	$data =  $_POST['data'];
	
	$stan_konta = $_SESSION['stan_konta'] - $cena;
	
	//tu usuwanie rekordu z tabeli transakcje
	$query1 = "DELETE FROM transakcje WHERE id = '$id' AND kategoria = '$kategoria' AND cena = '$cena' AND data = '$data' AND id = '$id_user' LIMIT 1";
	$queryrun1 = mysqli_query($connection, $query1 );
	
	//tutaj updatowanie tabeli uzytkownicy, zeby stan konta zmienił się o us
	$query2 = "UPDATE uzytkownicy SET stan_konta = '$stan_konta' WHERE id  = '$id_user'";
	$queryrun2 = mysqli_query($connection, $query2);
	
	//tutaj praca z tabelą rekordy, aby usunięcie transakcji, albo pomniejszalo ilosc transakcji w poszczegolnej kategorii w tabeli KATEGORIE albo usuwało kategorię z tabeli
	$query3 = "SELECT * FROM kategorie WHERE kategoria = '$kategoria' AND id = '$id_user'";
	$wiersz = mysqli_query($connection, $query3);
	$rezultat = mysqli_fetch_assoc($wiersz);
	
	
	
	//zastanowić się czy usuwanie, dodawanie transakcji wpływa jeszcze na coś niz tabele KATEGORIE i ilosc transakcji w poszczegolnych kategoriach,
	//na stan konta i na wyświetlanie się w zakładce historia.php
	//sprawdzić czy usuwanie i doddawanie rekordow dodaje sie, usuwa się, zmienia się w tabeli uzytkownicy i kolumnie stan_konta, w tabeli transakcje i w tabeli kategorie
	// jak to wszystko zrobię to koniec pracy na 01.08
	//sprawdzić czy dotychczas wszystko z skarbonką działa, wprowadzanie rekordów, sortowanie, filtrowanie i integracja z bazą
	$ilosc_transakcji_w_tej_kategorii = $rezultat['ilosc_transakcji'];
	
	if($ilosc_transakcji_w_tej_kategorii >1)
	{
		$query4 = "UPDATE kategorie SET ilosc_transakcji = '$ilosc_transakcji_w_tej_kategorii' - 1 WHERE id = '$id_user' AND kategoria = '$kategoria'";
		$queryrun4 = mysqli_query($connection, $query4);
	}
	else 
	{
		$query4 = "DELETE FROM kategorie WHERE id = '$id_user' AND kategoria = '$kategoria'";
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
	else
	{
		echo "not";
	}
}
?>