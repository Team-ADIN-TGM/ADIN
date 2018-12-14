<?php
session_start();
include '../connect.php';
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
    ?>
    <form action='testtable.php' method='post'>
        <tr>
        <td><input type="text" name="UserId"></td>
        <td><input type="text" name="DomainId"></td>
        <td><input type="text" name="password"></td>
        <td><input type="text" name="Email"></td>
        <td><button type="submit" name="submitInsert">Insert</button></td>
        </tr>

        <tr>
        <td><input type="text" name="UserId2"></td>
        <td></td>
        <td></td>
        <td></td>
        <td><button type="submit" name="submitDelete">Delete</button></td>
        </tr>
    </form>
    <?php
    echo "</table>";
    if(isset($_POST["submitInsert"])) {
        $userid = $conn->real_escape_string($_POST["UserId"]);
        $domainid = $conn->real_escape_string($_POST["DomainId"]);
        $password = $conn->real_escape_string($_POST["password"]);
        $email = $conn->real_escape_string($_POST["Email"]);

        //Durchführen der SQL-Abfrage
        $sql = "INSERT INTO Users_tbl (UserId, DomainId, password, Email) VALUES ('$userid', '$domainid', '$password', '$email')";
        if(mysqli_query($conn, $sql)){
            echo "Records inserted successfully.";
            header("Location: users.php");
        } else{
            echo "ERROR: Could not able to execute";
        }
    }
    if(isset($_POST["submitDelete"])) {
        $userid2 = $conn->real_escape_string($_POST["UserId2"]);
        $sql = "DELETE FROM Users_tbl WHERE UserId = $userid2" ;
        if (mysqli_query($conn, $sql)) {
            echo "Record deleted successfully";
            header("Location: users.php");
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }



}
?>