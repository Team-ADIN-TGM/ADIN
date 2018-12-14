<?php
    session_start();
    include 'connect.php';
    if(isset($_SESSION["user"])) {

      echo "<b>Users_tbl</b><br />";
      $sql = "SELECT * FROM Users_tbl";
      echo "<table border=5>";
      echo "<th>UserId</th><th>DomainID</th><th>password</th><th>Email</th>";
      foreach ($conn->query($sql) as $row) {
         echo "<tr>";
         echo "<td align='left'>" . $row['UserId'] . "</td>";
         echo "<td align='left'>" . $row['DomainId'] . "</td>";
         echo "<td align='left'>" . $row['password'] . "</td>";
         echo "<td align='left'>" . $row['Email'] . "</td>";

         echo "</tr>";
      }
      echo "</table>";

      echo "<b>Alias_tbl</b><br />";
      echo "<table border=5>";
      echo "<th>AliasId</th><th>DomainID</th><th>Source</th><th>Destination</th><th>Created</th><th>Modified</th><th>Active</th>";
      $sql2 = "SELECT * FROM Alias_tbl";
      foreach ($conn->query($sql2) as $row) {
         echo "<tr>";
         echo "<td align='left'>" . $row['AliasId'] . "<br />";
         echo "<td align='left'>" . $row['DomainId'] . "<br />";
         echo "<td align='left'>" . $row['Source'] . "<br />";
         echo "<td align='left'>" . $row['Destination'] . "<br />";
         echo "<td align='left'>" . $row['Created'] . "<br />";
         echo "<td align='left'>" . $row['Modified'] . "<br />";
         echo "<td align='left'>" . $row['Active'] . "<br /><br />";
         echo "</tr>";
      }
      echo "</table>";

      echo "<b>Domains_tbl</b><br />";
      echo "<table border=5>";
      echo "<th>DomainID</th><th>DomainName</th>";
      $sql2 = "SELECT * FROM Domains_tbl";
      foreach ($conn->query($sql2) as $row) {
         echo "<tr>";
         echo "<td align='left'>" . $row['DomainId'] . "<br />";
         echo "<td align='left'>" . $row['DomainName'] . "<br /><br />";
         echo "</tr>";
      }
      echo "</table>";
      ?>

      <form action='testtable.php' method='post'>
        <input type="text" name="UserId">
        <input type="text" name="DomainId">
        <input type="text" name="password">
        <input type="text" name="Email">
        <button type="submit" name="submitInsert">Insert</button>
      </form>

      <?php
      if(isset($_POST["submitInsert"])) {
         $userid = $conn->real_escape_string($_POST["UserId"]);
         $domainid = $conn->real_escape_string($_POST["DomainId"]);
         $password = $conn->real_escape_string($_POST["password"]);
         $email = $conn->real_escape_string($_POST["Email"]);

         //Durchführen der SQL-Abfrage
         $sql = "INSERT INTO Users_tbl (UserId, DomainId, password, Email) VALUES ('$userid', '$domainid', '$password', '$email')";
         if(mysqli_query($link, $sql)){
            echo "Records inserted successfully.";
         } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
         }

      }

   }
?>

