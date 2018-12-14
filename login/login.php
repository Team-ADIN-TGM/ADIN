<?php
  session_start();
  include '../connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ADIN - Login</title>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="login.php" method="post">
            <p>E-Mail:</p>
            <input type="text" name="username">
            <p>Passwort:</p>
            <input type="password" name="password">

            <?php
                if(isset($_POST["submitLogin"])) {
                    $username = $conn->real_escape_string($_POST["username"]);
                    $password = $conn->real_escape_string($_POST["password"]);

                    //Durchführen der SQL-Abfrage
                    $sql = "SELECT * FROM Users_tbl WHERE Email='$username' AND password='$password';";
                    $result_obj = $conn->query($sql);
                    $userdata = $result_obj->fetch_assoc();

                    //Überprüfen ob der Login richtig ist
                    if ($result_obj->num_rows > 0) {
                        
                        $_SESSION["user"] = $userdata["UserId"];
                        header("Location: ../home/index.php");
                    } else {
                        ?>
                        <p style="color:red;">Benutzername oder Passwort inkorrekt</p>
                        <?php
                    }
                }
            ?>


            <p><button type="submit" name="submitLogin">Login</button></p>
        </form>
    </body>
</html>
