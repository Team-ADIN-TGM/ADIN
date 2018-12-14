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
    ?>
    <form action='testtable.php' method='post'>
        <td><input type="text" name="UserId"></td>
        <td><input type="text" name="DomainId"></td>
        <td><input type="text" name="password"></td>
        <td><input type="text" name="Email"></td>
        <button type="submit" name="submitInsert">Insert</button>
        <br>
        <input type="text" name="UserId2">
        <button type="submit" name="submitDelete">Delete</button>

    </form>
    <?php

    echo "</table>";


}
?>