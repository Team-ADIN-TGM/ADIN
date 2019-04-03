<!--
TODO:
- Dropdown für Domains mit Domains füllen, für die der Benutzer Rechte hat
-->
<?php
//TODO: Remove, just for debugging
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

session_start();
require_once "../connect.php";

//mysqli-Objekt erstellen
$conn = get_database_connection();
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Mailbox hinzufügen</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

</head>
	
<body>
	<?php if (isset($_SESSION["user"])): ?>
	<div class="container-fluid mt-3">
		<h3>Neue Mailbox hinzufügen</h3>
		
		<form method="POST" action="index.php">
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="username">Benutzername</label>
				</div>
				<input type="text" id="username" class="form-control" name="username">
			</div>
			
			<!-- TODO: Dropdown muss mit den existierenden Domains gefüllt werden, für die der Benutzer Rechte hat -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="email">E-Mail-Adresse</label>
				</div>
				<input type="text" id="email" class="form-control" name="email">
				<span class="input-group-text input-group-text-midinput">@</span>
				<select class="custom-select" name="emaildomain">
					<option value="1">test.dns.or.at</option>
					<option value="2">test1.dns.or.at</option>
				</select>
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Voller Name</span>
				</div>
				<input type="text" class="form-control" name="firstname" placeholder="Vorname">
				<input type="text" class="form-control" name="lastname" placeholder="Nachname">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="password">Passwort</label>
				</div>
				<input type="password" id="password" class="form-control" name="password">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="password-repeat">Passwort wiederholen</label>
				</div>
				<input type="password" id="password-repeat" class="form-control" name="password-repeat">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="redirect">Weiterleiten an</label>
				</div>
				<input type="text" id="redirect" class="form-control" name="redirect" placeholder="Leer lassen, um Mails nicht weiterzuleiten">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="copy">Kopie an</label>
				</div>
				<input type="text" id="copy" class="form-control" name="copy" placeholder="Leer lassen, um keine Kopien zu senden">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<label class="input-group-text" for="note">Notiz</label>
				</div>
				<input type="text" id="note" class="form-control" name="note">
			</div>
			
			<input type="submit" class="btn adin-button" name="insert" value="Mailbox hinzufügen">
			<a class="btn btn-danger" href="../home/">Abbrechen</a>
		</form>
	</div>
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
    <a href="../login/">Login</a>
	
	<?php endif; ?>
</body>
</html>
