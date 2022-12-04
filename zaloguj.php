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
		
		$sql = "SELECT * FROM uzytkownicy WHERE login='$login'";
		if ($rezultat = @$polaczenie->query($sql));
		{
			$ilu_userow = $rezultat->num_rows;
			echo $ilu_userow;
			if($ilu_userow > 0)
			{
				$wiersz  = $rezultat->fetch_assoc();
				
				if(password_verify($haslo,$wiersz['haslo']))
				{
					$_SESSION['zalogowany'] = true;
					
					
					$_SESSION['user'] = $wiersz['login'];
					$_SESSION['stan_konta'] = $wiersz['stan_konta'];
					unset($_SESSION['blad']);
					$rezultat->free_result();
					header('Location: index.php');
				}
				else 
			  {
					$_SESSION['blad'] = '<span style="color:red">Nieprawidłowe hasło!</span>';
					header('Location:login.php');
				}
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