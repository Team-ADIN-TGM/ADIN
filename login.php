
<?php
  session_start();
  include 'connect.php';
?>
<html>
<body>
<form action="login.php" method="post">
  <p>Benutzername:</p>
  <input type="text" name="benutzername">
  <p>Passwort:</p>
  <input type="password" name="passwort">


  <?php
  if(isset($_POST["submitLogin"])) {
    $benutzer = mysqli_real_escape_string($conn, $_POST["benutzername"]);
    $passwort = mysqli_real_escape_string($conn, $_POST["passwort"]);
    
      //Durchführen der SQL-Abfrage
      $sql = "SELECT Email, password FROM Users_tbl WHERE Email='$benutzer' AND password='$passwort'";
      $result = $conn->query($sql);
      $user= mysqli_fetch_assoc($result);
      $resultCheck = mysqli_num_rows($result);

      //Überprüfen ob der Login richtig ist
      if($resultCheck == 0) {
      ?>
      <p style="color:red;">Benutzername oder Passwort inkorrekt</p>
      <?php
      }  else {
        $_SESSION["benutzer"] = $user["UserId"];
        header("Location: index.php");
      }

  }
?>

  <p><button type="submit" name="submitLogin">Login</button></p>
</form>
</body>
</html>


