<?php
 session_start();
 include '../connect.php';
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
    <form action="index.php" method="post">

        <button type="submit" name="mailbox">Mailboxen Verwaltung</button>
        <button type="submit" name="userconf">Benutzer Verwaltung</button>
        <button type="submit" name="groupconf">Gruppenmailbox Verwaltung</button>
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>

<?php
if(isset($_POST["logout"])) {
    session_destroy();
    unset($_SESSION['user']);
    header("Location: ../login/login.php");
}
if(isset($_POST["mailbox"])) {
    header("Location: mail.php");
}
if(isset($_POST["userconf"])) {
    header("Location: user.php");
}
if(isset($_POST["groupconf"])) {
    header("Location: group.php");
}
?>

<?php
    } else {
?>
        <p>Sie sind nicht angemeldet!</p>
        <a href="../login/login.php">Login</a>
<?php
    }
?>




