<?php
 session_start();
 include 'connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ADIN - Home</title>
</head>
<body>
<?php
   
    if(isset($_SESSION["user"])) {
?>
        <p>Sie sind erfolgreich angemeldet</p>
<?php
    } else {
?>
        <p>Sie sind nicht angemeldet!</p>
<?php
    }
?>

<?php
	if(isset($_POST["logout"])) {
		echo "hallo";
		session_destroy();
		unset($_SESSION['user']);
		header("Location: login.php");
	}
?>

<a href="login.php">Login</a>
<form action="index.php" method="post">
    <p><button type="submit" name="logout">Logout</button></p>
</form>
</body>
</html>
