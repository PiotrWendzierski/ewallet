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
</head>
<body>
<form action="zaloguj.php" method="post">
	Login: </br> <input type="text" name="login" /> </br>
	Hasło: </br> <input type="password" name="haslo" /> </br>
	<input type="submit" value="Zaloguj się!" />
</form>

<?php
	if(isset($_SESSION['blad']))
	{
		echo $_SESSION['blad'];
	}
?>
</body>

</html>