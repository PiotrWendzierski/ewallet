<?php
	session_start();
	
	if(isset($_POST['email']))
  {
		//Udana walidacja
		$wszystko_ok = true;
		//Sprawdzenie nicku
		$nick=$_POST['nick'];
		//sprawdzenie dlugosc nicku
		if((strlen($nick))<3 ||(strlen($nick)>20))
		{
			$wszystko_ok = false;
			$_SESSION['e_nick'] = '<span style="color:red">Nick musi mieć od 3 do 20 znaków!</span>.</br>';
		}
		
		if(ctype_alnum($nick)==false)
		{
			$wszystko_ok = false;
			$_SESSION['e_nick'] = '<span style="color:red">Nick może składać się tylko z liter i cyfr (bez polskich znaków)!</span>.</br>';
		}
		//sprawdz poprawnosc adresu email
		{
			$email = $_POST['email'];
			$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
			
			if((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false)|| ($emailB!=$email))
			{
				$wszystko_ok = false;
				$_SESSION['e_email'] = '<span style="color:red">Podaj poprawny adres email!</span>.</br>';
			}
		}
		//sprawdzanie hasla
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if((strlen($haslo1))<8 ||(strlen($haslo1)>20))
		{
			$wszystko_ok = false;
			$_SESSION['e_haslo'] = '<span style="color:red">Hasło musi mieć od 8 do 20 znaków!</span></br>';
		}
		if($haslo1!=$haslo2)
		{
			$wszystko_ok = false;
			$_SESSION['e_haslo'] = '<span style="color:red">Hasła muszą być takie same!</span></br>';
		}
		
		$haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT); 
		//czy rglmn jest zaznaczony
		if(!isset($_POST['regulamin']))
		{
			$wszystko_ok = false;
			$_SESSION['e_regulamin'] = '<span style="color:red">Zaznacz regulamin!</span></br>';
		}
		//captcha
		$sekret = "6LddHz4jAAAAAIw8MXLqEb9u2t5zBZXV7ePURmYW";
		$sprawdz= file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		
		
		
		if($odpowiedz->success==false)
		{
			$wszystko_ok = false;
			$_SESSION['e_boot'] = '<span style="color:red">Potwierdź, że nie jesteś robotem!</span></br>';
		}

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email istnieje?
				$rezultat = $polaczenie ->query("SELECT id FROM uzytkownicy WHERE email='$email'");
				if(!$rezultat)throw new Exception($polaczenie->error);
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_ok = false;
					$_SESSION['e_email'] = '<span style="color:red">Istnieje w bazie tak e-mail!</span></br>';
				}
				//Czy nick istnieje?
				$rezultat = $polaczenie ->query("SELECT id FROM uzytkownicy WHERE login='$nick'");
				if(!$rezultat)throw new Exception($polaczenie->error);
				$ile_takich_nickow = $rezultat->num_rows;
				if($ile_takich_nickow>0)
				{
					$wszystko_ok = false;
					$_SESSION['e_nick'] = '<span style="color:red">Istnieje w bazie taki nick!</span></br>';
				}
				if($wszystko_ok == true)
				{
					if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$email', '$nick', '$haslo_hash', 0 )"))
					{
						$_SESSION['udana_rejestracja'] = true;
						header('Location: login.php');
					}
					else 
					{
						throw new Exception($polaczenie->error);
					}
				}
				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo "Błąd rejestracji";
			echo $e;
		}
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel - załóż darmowe konto</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</script>
</head>
<body>
	<form method="post">
		Nickname</br>		<input type="text" name="nick" /></br>
		<?php
			if(isset($_SESSION['e_nick']))
			{
				echo $_SESSION['e_nick'];
				unset($_SESSION['e_nick']);
			}
		?>
		E-mail</br>		<input type="text" name="email" /></br>
		<?php
			if(isset($_SESSION['e_email']))
			{
				echo $_SESSION['e_email'];
				unset($_SESSION['e_email']);
			}
		?>
		Twoje hasło</br>		<input type="password" name="haslo1" /></br>
		Powtórz hasło</br>		<input type="password" name="haslo2" /></br>
		<?php
			if(isset($_SESSION['e_haslo']))
			{
				echo $_SESSION['e_haslo'];
				unset($_SESSION['e_haslo']);
			}
		?>
		<label>
			<input type="checkbox" name="regulamin" /> Akceptuję regulamin
		</label>
		<?php
			if(isset($_SESSION['e_regulamin']))
			{
				echo "</br>".$_SESSION['e_regulamin'];
				unset($_SESSION['e_regulamin']);
			}
		?>
		<div class="g-recaptcha" data-sitekey="6LddHz4jAAAAAMqQ7AuhbvtSJHKdgZCnO7IBwMcN"></div>
		<?php
			if(isset($_SESSION['e_boot']))
			{
				echo "</br>".$_SESSION['e_boot'];
				unset($_SESSION['e_boot']);
			}
		?>
		<br/>
		<input type="submit" velue="Zarejestruj się!"/>
 		
	</form>
</body>

</html>

