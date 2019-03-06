<!--
TODO:
- Überprüfen, ob der Benutzer die notwendigen Rechte hat, um die Domain zu bearbeiten
- Daten aus der Datenbank auslesen und alle Eingabefelder ausfüllen
- Für Superuser: Admin-Dropdown mit allen Benutzern füllen, active
  Für Delegated Admins: Admin-Dropdown nur mit eigenem Nutzernamen füllen, inaktiv (nicht bearbeitbar), mit Anmerkung:
  Sie können keinen anderen Domain-Admin zuweisen, weil Sie nicht Superuser sind
-->
<?php 
	session_start(); 
	include "../connect.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Domain ändern</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

</head>
	
<body>
	<?php /* if(benutzer hat rechte): */ ?>
	<div class="container-fluid mt-3">
		<h3>Domain ändern</h3>
		
		<!-- TODO: Eingabefelder müssen ausgefüllt werden -->
		<form method="POST" action="index.php">
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Domain-Name</span>
				</div>
				<input type="text" class="form-control" name="domainname" value="flashbrother.net">
			</div>
			
			<!-- TODO: Dropdown muss mit existierenden Domain-Admins gefüllt werden -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Domain-Admin</span>
				</div>
				<select class="custom-select" name="usertype">
					<option value="mfrank">mfrank</option>
					<option value="dcerny" selected>dcerny</option>
				</select>
			</div>
			
			<input type="submit" class="btn adin-button" name="update" value="Änderungen speichern">
			<a class="btn btn-danger" href="../domains/">Änderungen verwerfen</a>
		</form>
	</div>
	
	<?php elseif (isset($_SESSION["user"])): ?>
	
	<!-- Benutzer angemeldet, aber nicht berechtigt -->
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
    <a href="../login/">Login</a>
	
	<?php endif; ?>
	
</body>
</html>
