<?php
	session_start();
	if ((isset($_SESSION['zalogowany']))&& ($_SESSION['zalogowany'] == true))
	{
		header('Location:index2.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
	<link rel="stylesheet"  href="img/fontello-9677cda3/css/fontello.css" type="text/css" / >
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">

</head>
<body>

<div id = "container">
	<div id="tlo">
	
	<div id="title">
		<i class="icon-wallet"></i>eWallett
	</div> 	
	<?php
		if(isset($_SESSION['udana_rejestracja']))
		{
			echo "Udana rejestracja! Zaloguj się na swoje nowe konto :) "."</br>";
			unset ($_SESSION['udana_rejestracja']);
		}
	?>
	<div id="login">
		
		<form action="zaloguj.php" method="post">
			</br> <input type="text" name="login" placeholder = "login" onfocus = "this.placeholder=''" onblur="this.placeholder='login'"/> </br>
			</br> <input type="password" name="haslo" placeholder = "hasło" onfocus = "this.placeholder=''" onblur="this.placeholder='hasło'"//> </br></br>
			<?php
			if(isset($_SESSION['blad']))
			{	
				echo $_SESSION['blad']."</br>";
			}
		?>
			<input type="submit" value="Zaloguj się!" />
		</form>

		</br>
		Nie masz konta?<a class = "rejestracja" href="rejestracja.php">  Zarejestruj się!</a>
	</div>
	</div>
	
	<div id="footer">Wszelkie prawa zastrzeżone
	</div>
</div>


</body>

</html>