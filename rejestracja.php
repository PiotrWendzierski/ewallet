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
		
		/*$sprawdz= file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		
		
		if($odpowiedz->success==false)
		{
			$wszystko_ok = false;
			$_SESSION['e_boot'] = '<span style="color:red">Potwierdź, że nie jesteś robotem!</span></br>';
		}*/
		if(isset($_POST['g-recaptcha-response']))
		{
			$sekret = "6LddHz4jAAAAAIw8MXLqEb9u2t5zBZXV7ePURmYW";
			$ip = $_SERVER['REMOTE_ADDR'];
			$response = $_POST['g-recaptcha-response'];
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret=$sekret&response=$response';
			$fire = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
			$odpowiedz = json_decode($fire);
			if($odpowiedz->success==false)
			{
				$wszystko_ok = false;
				$_SESSION['e_boot'] = '<span style="color:red">Potwierdź, że nie jesteś robotem!</span></br>';
			}
			
		}

		require_once "connect.php";
		//mysqli_report(MYSQLI_REPORT_STRICT);
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
					if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$email', '$nick', '$haslo_hash', 0, 0, 'brak' , 0)"))
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
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-9677cda3/css/fontello.css" type="text/css" / >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">
</script>
</head>
<body>
<div id="container">
<div id="tlo">
<div id="title">
		<a class="rejestracja" href="login.php"><i class="icon-wallet"></i>eWallett</a>
</div> 	
<div id="login">
	<form method="post" >
		</br>		<input type="text" class="rejestracja" name="nick" placeholder = "Nickname" onfocus = "this.placeholder=''" onblur="this.placeholder='Nickname'"/></br>
		<?php
			if(isset($_SESSION['e_nick']))
			{
				echo $_SESSION['e_nick'];
				unset($_SESSION['e_nick']);
			}
		?>
		</br>		<input type="text" class = "rejestracja" name="email" placeholder = "E-mail" onfocus = "this.placeholder=''" onblur="this.placeholder='E-mail'"/></br>
		<?php
			if(isset($_SESSION['e_email']))
			{
				echo $_SESSION['e_email'];
				unset($_SESSION['e_email']);
			}
		?>
		</br>		<input type="password" class="rejestracja" name="haslo1" placeholder = "Twoje hasło" onfocus = "this.placeholder=''" onblur="this.placeholder='Twoje hasło'"/></br>
		</br>		<input type="password" class="rejestracja" name="haslo2" placeholder = "Powtórz hasło" onfocus = "this.placeholder=''" onblur="this.placeholder='Powtórz hasło'"/></br>
		<?php
			if(isset($_SESSION['e_haslo']))
			{
				echo $_SESSION['e_haslo'];
				unset($_SESSION['e_haslo']);
			}
		?>
		<label>
			</br><input type="checkbox" name="regulamin" /> Akceptuję regulamin
		</label>
		<?php
			if(isset($_SESSION['e_regulamin']))
			{
				echo "</br>".$_SESSION['e_regulamin'];
				unset($_SESSION['e_regulamin']);
			}
		?>
		<div class="g-recaptcha" data-sitekey="6LddHz4jAAAAAMqQ7AuhbvtSJHKdgZCnO7IBwMcN" style="margin-left: 50px; margin-top: 10px;"></div>
		<?php
			if(isset($_SESSION['e_boot']))
			{
				echo "</br>".$_SESSION['e_boot'];
				unset($_SESSION['e_boot']);
			}
		?>
		<br/>
		<input type="submit" class="submitrej" value="Zarejestruj się!"/>
 		
	</form>
	</br>Masz już konto?<a class = "rejestracja" href="login.php">  Zaloguj się!</a>
	</div>
	</div>
	<div id="footer">Wszelkie prawa zastrzeżone
		
	</div>
	</div>
	
</body>

</html>

