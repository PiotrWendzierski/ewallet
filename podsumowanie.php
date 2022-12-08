<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: login.php');
		exit();
	}
	require_once "connect.php";
	$wszystko_ok = true;
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>eWallet - twój elektroniczny portfel</title>
	<link rel="stylesheet"  href="style.css" type="text/css" / >
</head>
<body>
<div id="container">
	<div id="title">
		eWallett
	</div>
	
	<div id="pole">
		</br></br>Podsumowanie:</br></br>
		<?php
				
				try
				{
					$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
					if($polaczenie->connect_errno!=0)
					{
						throw new Exception(mysqli_connect_errno());
					}
					else 
					{
						$user = $_SESSION['user'];
						//$sql=
					}
				}
				catch (Exception $e)
				{
					
				}
		?>
		</br><br>
	</div>
</div>
</body>