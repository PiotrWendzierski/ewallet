<?php
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "ewallet");

if(isset($_POST['delete']))
{
	$id = $_POST['id'];
	$kategoria =  $_POST['kategoria'];
	$cena  =  $_POST['cena'];
	$data =  $_POST['data'];
	
	$query = "DELETE FROM transakcje WHERE id = '$id' AND kategoria = '$kategoria' AND cena = '$cena' AND data = '$data' LIMIT 1";
	//teraz zrobić tak , ze jak sie cos usunie, to zwieksza się/zmiejsza stan portfela i zmieniają się dane w tabeli KATEGORIE!
	$queryrun = mysqli_query($connection, $query );
	
	if($queryrun)
	{
		header("Location:historia.php");
	}
	else
	{
		echo "not";
	}
}
?>