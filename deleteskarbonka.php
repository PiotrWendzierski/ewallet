<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	if(!isset($_POST['delete']))
	{
		header('Location: historia_skarbonki.php');
		exit();
	}
	
	require_once "connect.php";
	$polaczenie = new mysqli ($host, $db_user, $db_password, $db_name);
	$db = mysqli_select_db($polaczenie, "ewallet");
	
	if(isset($_POST['delete']))
{
	$wszystko_ok = true;
	//to jest id uzytkownika zalogowanego
	$id_user = $_SESSION['id'];
	$kwota_przeznaczona =  $_POST['cena'];
	$data = $_POST['data'];
	
	//odczytanie kwoty aktualnej w portfelu i utworzenie nowej kwoty PO USUNIĘCIU TRANSAKCJI SKARBONKI
	
	$query1 = "SELECT * FROM uzytkownicy WHERE id='$id_user' ";
	$wiersz1 = mysqli_query($polaczenie, $query1);
	$rezultat1 = mysqli_fetch_assoc($wiersz1);
	//$stan_konta = $_SESSION['stan_konta'] - $cena;
	
	$stan_konta = $rezultat1 ['stan_konta'];
	$stan_skarbonki = $rezultat1['skarbonka'];
	$stan_konta = $stan_konta + $kwota_przeznaczona;
	$stan_skarbonki -= $kwota_przeznaczona;
	
	//usuwanie rekordu z bazy
	
	$query2 = "DELETE FROM transakcji_skarbonki WHERE id = '$id_user' AND data_transakcji = '$data' AND kwota_przeznaczona = '$kwota_przeznaczona' LIMIT 1";
	$queryrun1 = mysqli_query($polaczenie, $query2 );
	
	//sprawianie, aby aktualizował się stan konta
	
	$query3 = "UPDATE uzytkownicy SET stan_konta = '$stan_konta' WHERE id  = '$id_user'";
	$queryrun2 = mysqli_query($polaczenie, $query3);
	
	//aby aktualizował się stan_skarbonki
	
	$query4 = "UPDATE uzytkownicy SET skarbonka = '$stan_skarbonki' WHERE id = '$id_user'";
	$queryrun3 = mysqli_query($polaczenie, $query4);
	
	if($queryrun1)
	{
		if($queryrun2)
		{
			if($queryrun3)
			{
				header("Location:historia_skarbonki.php");
			}
		}
	}
}

?>