<!--
TODO:
- ID aus der URL auslesen
- Überprüfen, ob der Benutzer die Rechte hat, um darauf zuzugreifen
- Text mit Daten ausfüllen (wie ID, Email-Adresse)
-->

<?php
	session_start();
	require "../connect.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Benutzer löschen</title>
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
	<?php if (isset($_SESSION["user"])): ?>
	<div class="container-fluid mt-3">
		<h3 class="mb-3">Benutzer löschen</h3>
		
		<!-- TODO: Meldung anpassen -->
		<span class="mb-3">
			Sind Sie sicher, dass sie den Benutzer mfrank löschen wollen?<br>
			Dieser Benutzer ist ein Delegated Admin. Wenn Sie ihn löschen, haben die folgenden Domains keinen Delegated Admin mehr:<br>
			<!-- TODO: Liste der Domains -->
		</span>
		
		<!-- TODO: Links müssen logischerweise noch angepasst werden -->
		<div class="mt-3">
			<form method="post" action="index.php" style="display: inline;">
				<input type="hidden" name="username" value="">
				<input type="submit" class="btn btn-danger" name="delete" value="Ja, löschen">
			</form>
			<a class="btn adin-button" href="index.php">Nein, nicht löschen</a>
		</div>
	</div>
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
	<a href="../login/">Login</a>
	
	<?php endif; ?>
</body>
</html>