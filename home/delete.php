<!--
TODO:
- ID aus der URL auslesen
- Überprüfen, ob der Benutzer die Rechte hat, um darauf zuzugreifen
- Text mit Daten ausfüllen (wie ID, Email-Adresse)
-->

<?php
//TODO: Remove, just for debugging
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

session_start();
include "../connect.php";

//mysqli-Objekt erstellen
$conn = get_database_connection();
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Mailbox löschen</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	
	
</head>
	
<body>
	<?php
		//TODO: Überprüfen, ob der Benutzer die Rechte hat
		//if (rechte):
	?>
	<div class="container-fluid mt-3">
		<h3 class="mb-3">Mailbox löschen</h3>
		
		<!-- TODO: Meldung anpassen -->
		<span class="mb-3">
			Sind Sie sicher, dass sie die Mailbox für mfrank@flashbrother.net mit der ID 35 löschen wollen?
		</span>
		
		<!-- TODO: Links müssen logischerweise noch angepasst werden -->
		<div class="mt-3">
			<form method="post" action="index.php" style="display: inline;">
				<input type="hidden" name="userid" value="35">
				<input type="submit" class="btn btn-danger" name="delete" value="Ja, löschen">
			</form>
			<a class="btn adin-button" href="index.php">Nein, nicht löschen</a>
		</div>
	</div>
	
	<?php else:	?>
	
	<p>Sie sind nicht angemeldet!</p>
	<a href="../login/">Login</a>
	
	<?php endif; ?>
	
</body>
</html>
