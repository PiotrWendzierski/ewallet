<?php

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if($polaczenie->connect_errno!=0)
	{
		echo "Zle";
	}
	else 
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$sql = "SELECT * FROM uzytkownicy WHERE login='$login' AND haslo='$haslo'";
		
		$polaczenie->close();
	}

	
	
	echo $login.$haslo;
?>