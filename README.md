# ADIN
## DB Connect
$ git clone https://github.com/Team-ADIN-TGM/ADIN.git

$ vi connect.php

```
<?php
$conn = new mysqli("localhost", 'user', 'passwort' , "database");
if ($conn->connect_error) die("Connection ERROR");
?>
```
