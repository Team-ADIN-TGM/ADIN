<?php
include '../connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="../style/style.css">
        <title>ADIN - Login</title>
        <meta charset="utf-8">
    </head>
    <body class="adin center">
        <div class="container">
            
            <form action="index.php" method="post">
                <div class="form-group">
                    <img src="../img/logo.png" id="adin-login-logo">
                    <p class="logintext">Benutzername</p>
                        <input type="text" class="form-control logininput mx-auto" name="username" placeholder="Benutzername">
                    <p class="logintext">Passwort:</p>
                    <input type="password" class="form-control logininput mx-auto" name="password" placeholder="Passwort">

                    <?php
                        if (isset($_POST["submitLogin"])) {
                            $username = $conn->real_escape_string($_POST["username"]);
                            $password = $conn->real_escape_string($_POST["password"]);

                            //Durchführen der SQL-Abfrage
                            $sql = "SELECT * FROM Admins_tbl WHERE Username = ? AND Password = ?;";
                            $prep_stmt = $conn->prepare($sql);
                            $prep_stmt->bind_param("ss", $username, $password);

                            //TODO: Entfernen, nur zum Debuggen
                            echo "SELECT * FROM Admins_tbl WHERE Username = '$username' AND Password = '$password';";

                            $prep_stmt->execute();
                            $res = $prep_stmt->get_result();

                            //Überprüfen ob der Login richtig ist - dann müsste eine Zeile zurückgegeben werden
                            if ($res->num_rows == 1) {
                                $userdata = $res->fetch_assoc();
                                session_start();
                                $_SESSION["user"] = $userdata["Username"];
                                $_SESSION["userid"] = $userdata["AdminId"];
                                header("Location: ../home/index.php");
                            } elseif ($res->num_rows == 0) {
                                //Es gibt keine Übereinstimmung, Benutzername oder Passwort falsch
                                ?>
                                <p style="color:red;">Benutzername oder Passwort inkorrekt</p>
                                <?php

                            } elseif ($res->num_rows > 1) {
                                //Fehler in der Abfrage! -> Sicherheitshalber nicht einloggen
                                ?>
                                <p style="color:red;">
                                    Fehler bei der Überprüfung der Login-Daten - Bitte Kontaktieren Sie den Webmaster!
                                </p>
                                <?php
                            }
                        }
                    ?>

                    <p><button class="btn btn-light loginbtn" type="submit" name="submitLogin">Login</button></p>
                </div>
            </form>
            </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
