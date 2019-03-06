<!--
TODO:
- Dropdown für Domains mit Domains füllen, für die der Benutzer Rechte hat
-->
<?php 
	session_start(); 
	include "../connect.php";
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
					<span class="input-group-text">Benutzername</span>
				</div>
				<input type="text" class="form-control" name="username">
			</div>
			
			<!-- TODO: Dropdown muss mit den existierenden Domains gefüllt werden, für die der Benutzer Rechte hat -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">E-Mail-Adresse</span>
				</div>
				<input type="text" class="form-control" name="email">
				<span class="input-group-text input-group-text-midinput">@</span>
				<select class="custom-select" name="usertype">
					<option value="1">test.dns.or.at</option>
					<option value="2">test1.dns.or.at</option>
				</select>
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text"> Voller Name</span>
				</div>
				<input type="text" class="form-control" name="firstname" placeholder="Vorname">
				<input type="text" class="form-control" name="lastname" placeholder="Nachname">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Passwort</span>
				</div>
				<input type="password" class="form-control" name="password">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Passwort wiederholen</span>
				</div>
				<input type="password" class="form-control" name="password-repeat">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Weiterleiten an</span>
				</div>
				<input type="text" class="form-control" name="redirect" placeholder="Leer lassen, um Mails nicht weiterzuleiten">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Kopie an</span>
				</div>
				<input type="text" class="form-control" name="copy" placeholder="Leer lassen, um keine Kopien zu senden">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Notiz</span>
				</div>
				<input type="text" class="form-control" name="note">
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
