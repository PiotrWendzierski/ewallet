<?php
	
	session_start();
	
	require_once "connect.php";
	
	if((!isset($_POST['login']))|| (!isset($_POST['haslo'])))
	{
		header('Location: login.php');
		exit();
	}

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
		if ($rezultat = @$polaczenie->query($sql));
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow > 0)
			{
				$_SESSION['zalogowany'] = true;
				
				$wiersz  = $rezultat->fetch_assoc();
				$_SESSION['user'] = $wiersz['login'];
				$_SESSION['stan_konta'] = $wiersz['stan_konta'];
				unset($_SESSION['blad']);
				$rezultat->free_result();
				header('Location: index2.php');
			}
			else
			{
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location:login.php');
			}
		}
		
		$polaczenie->close();
	}

	

?>