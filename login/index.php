<?php
  session_start();
  include '../connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
