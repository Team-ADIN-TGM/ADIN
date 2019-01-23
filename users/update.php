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
	<title>ADIN - Benutzer ändern</title>
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
		<h3>Benutzerdaten ändern</h3>
		
		<!-- TODO: Die einzelnen Eingabefelder müssen in PHP (mittels value-Attribut) ausgefüllt werden
			 Ausnahme: Die Passwort-Eingabefelder werden nicht ausgefüllt! -->
		<form method="POST" action="index.php">
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Benutzername</span>
				</div>
				<input type="text" class="form-control" name="username" value="mfrank">
			</div>
			
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">E-Mail-Adresse</span>
				</div>
				<input type="text" class="form-control" name="email" value="mfrank@flashbrother.net">
			</div>
			
			<!-- TODO: Die Passwort-Felder müssen geprüft werden. Sie müssen beide ausgefüllt und gleich sein -->
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
			
			<!-- TODO: Hier muss "selected" bei der richtigen <option> gesetzt werden -->
			<div class="input-group mb-3 col-lg-6">
				<div class="input-group-prepend">
					<span class="input-group-text">Benutzertyp</span>
				</div>
				<select class="custom-select" name="usertype">
					<option value="delegated-admin" selected>Delegated Admin</option>
					<option value="superuser">Superuser</option>
				</select>
			</div>
			
			<input type="submit" class="btn adin-button" name="update" value="Änderungen speichern">
			<a class="btn btn-danger" href="../users/">Änderungen verwerfen</a>
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