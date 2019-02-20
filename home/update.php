<!--
TODO:
- Überprüfen, ob der Benutzer die notwendigen Rechte hat, um die Mailbox zu bearbeiten
- Daten aus der Datenbank auslesen und alle Eingabefelder ausfüllen (außer die Passwörter)
- Das Domain-Dropdown mit Domains füllen, für die der Benutzer die Rechte hat
-->
<?php 
	session_start(); 
	include "../connect.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Mailbox ändern</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>
	
<body>
	<?php /* if(benutzer hat rechte): */ ?>
	<div class="container-fluid mt-3">
		<h3>Mailbox ändern</h3>
		
		<!-- TODO: Eingabefelder müssen ausgefüllt werden -->
		<form method="POST" action="index.php">
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Benutzername</span>
				</div>
				<input type="text" class="form-control" name="username" value="mfrank">
			</div>
			
			<!-- TODO: Dropdown muss mit den existierenden Domains gefüllt werden -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">E-Mail-Adresse</span>
				</div>
				<input type="text" class="form-control" name="email" value="mfrank">
				<span class="input-group-text input-group-text-midinput">@</span>
				<select class="custom-select" name="usertype">
					<option value="1">test.dns.or.at</option>
					<option value="2">test1.dns.or.at</option>
					<option value="7" selected>flashbrother.net</option>
				</select>
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Name</span>
				</div>
				<input type="text" class="form-control" name="firstname" placeholder="Vorname" value="Markus">
				<input type="text" class="form-control" name="lastname" placeholder="Nachname" value="Markus">
			</div>
			
			<!-- Die Passwort-Eingabefelder dürfen nicht ausgefüllt werden.
				 Beim Abschicken müssen sie geprüft werden. Sie müssen beide ausgefüllt und gleich sein -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Passwort</span>
				</div>
				<input type="password" class="form-control" name="password" placeholder="Zum Ändern neues Passwort eingeben">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Passwort wiederholen</span>
				</div>
				<input type="password" class="form-control" name="password-repeat" placeholder="Zum Ändern neues Passwort wiederholen">
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
				<input type="text" class="form-control" name="note" value="DER SERVER GEHT SCHON WIEDER NICHT">
			</div>
			
			<input type="submit" class="btn adin-button" name="update" value="Änderungen speichern">
			<a class="btn btn-danger" href="../home/">Änderungen verwerfen</a>
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