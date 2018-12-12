<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ADIN - Home</title>
</head>
<body>
<?php
    include 'connect.php';
    session_start();
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
</body>
</html>