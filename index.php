<?php
	session_start();
	if(!isset($_SESSION['zalogowany'])|| ($_SESSION['zalogowany'] != true))
	{
		header('Location: login.php');
		exit();
	}
	if($_SESSION['stan_konta'] == true)
	{
		header('Location: index2.php');
		exit();
	}
	//else 
	//{
	//	unset ($_SESSION['udana_rejestracja']);
	//}
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
		</br></br>Wpisz stan początkowy swojego portfela: </br></br>
		<form method="post">
			<input type="number" name="stan"> </br></br>
			
			<input type="submit" value="Zaczynamy">
		</form>
		</br><br>
		<?php
			$wszystko_ok = true;
			require_once "connect.php";
			try
		  {
				$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
				if($polaczenie->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					//jeśli pusty stan konta
					if((isset ($_POST['stan']))&&($_POST['stan'] == ""))
					{
						$wszystko_ok = false;
						$_SESSION['e_stan'] = '<span style="color:red">Wpisz stan konta!</span></br>';
					}
					//jesli nie pusty
					if(($wszystko_ok == true)&&(isset($_POST['stan'])))
					{
						$stan = $_POST['stan'];
						$uzytkownik = $_SESSION['user'] ;
						$sql = "UPDATE uzytkownicy SET stan_konta = '$stan' WHERE login='$uzytkownik'";
						if($polaczenie->query($sql))
						{
							$_SESSION['stan_konta']=true;
							header('Location:index2.php');
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
				echo $e;
			}
			
		?>
		<?php
				if(isset($_SESSION['e_stan']))
				{
					echo $_SESSION['e_stan'];
					unset($_SESSION['e_stan']);
				}
			?>
	</div>
</div>
</body>

</html>