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
		session_destroy();
		header("Location: login.php");
	}
?>

<a href="login.php">Login</a>
<p><button type="submit" name="Logout">Logout</button></p>
<a href=""></a>
</body>
</html>
