# ADIN
## DB Connect
$ git clone https://github.com/Team-ADIN-TGM/ADIN.git

$ vi connect.php

```
<?php
    $dbhost = "localhost";
    $dbusername = "username";
    $dbpassword = "p@ssw0rd";
    $dbdatabase = "mydatabase";
    
    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbdatabase);
?>
```
