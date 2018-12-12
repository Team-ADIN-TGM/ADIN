<?php
include 'connect.php';
  session_start();
if(isset($_SESSION["benutzer"])) {
?>
<html>
	<head>
	
	</head>
<body>
	
	Hallo Benutzer
	
	
</body>
</html>
<?php
} else {
  echo "Sie müssen angemeldet sein";
}
?>
