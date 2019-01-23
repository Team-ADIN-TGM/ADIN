# ADIN
## DB Connect
$ git clone https://github.com/Team-ADIN-TGM/ADIN.git

$ cd config

$ vi connect.php

```php
<?php
    $dbhost = "localhost";
    $dbusername = "username";
    $dbpassword = "p@ssw0rd";
    $dbdatabase = "mydatabase";
    
    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbdatabase);
?>
```