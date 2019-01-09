<?php
 session_start();
 include '../connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/style.css">
    <meta charset="utf-8">
    <title>ADIN - Home</title>
</head>
<body>
<?php
   
    if(isset($_SESSION["user"])) {
?>
<nav class="adin navbar">
    <ul class="navbar-nav">
        <li class="nav-item">
                <div class="item">
                    <img src="../img/logo.png" class="img">
                </div>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="../users/users.php" class="a">
                <div class="item">
                    <img src="../img/benutzer.png" class="img">
                    <span>Benutzer</span>
                </div>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="a">
                <div class="item">
                    <img src="../img/mail.png" class="img">
                    <span>Mailboxen</span>
                </div>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="a">
                <div class="item">
                    <img src="../img/personen.png" class="img">
                    <span>Verteiler</span>
                </div>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="#" class="a">
                <div class="item">
                    <img src="../img/at.png" class="img">
                    <span>Domains</span>
                </div>
            </a>
        </li>
    </ul>

</nav>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>


<?php
    } else {
?>
        <p>Sie sind nicht angemeldet!</p>
        <a href="../login/login.php">Login</a>
<?php
    }
?>




