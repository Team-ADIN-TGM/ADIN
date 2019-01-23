<!--
TODO:
- Dynamisches Auslesen der Datenbank und füllen der Tabelle
- Setzen der Links zu delete/new/update-Seiten
- Reagieren auf Aktionen in den delete/new/update-Seiten
-->

<?php
	session_start();
	include '../connect.php';

	/*
	 * TODO: Überprüfen, ob das Parameter insert/update/delete gesetzt ist
	 * Reagieren darauf, auslesen der Parameter, Zugriff auf die Datenbank
	 */
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Benutzer</title>
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
	<!-- Navigationsleiste -->
	<nav class="navbar adin">
		<a class="navbar-brand" href="../home/">
			<img src="../img/logo.png" id="adin-navbar-logo">
		</a>
		
		<div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../users/">
					<img class="adin-navbar-item-img" src="../img/benutzer.png">
					<p class="adin-navbar-item-text">Benutzer</p>
				</a>
			</div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../users/">
					<img class="adin-navbar-item-img" src="../img/mail.png">
					<p class="adin-navbar-item-text">Mailboxen</p>
				</a>
			</div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../users/">
					<img class="adin-navbar-item-img" src="../img/personen.png">
					<p class="adin-navbar-item-text">Verteiler</p>
				</a>
			</div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../users/">
					<img class="adin-navbar-item-img" src="../img/at.png">
					<p class="adin-navbar-item-text">Domains</p>
				</a>
			</div>
		</div>
	</nav>
	
	<!-- Haupt-Container -->
	<div class="container-fluid">
		
		<h1 class="mt-3">Mailboxen</h1>
		
		<!-- Zur Auswahl der Domain, deren Mailboxen angezeigt werden sollen -->
		<!-- TODO: Links, es dürfen nur die Domains angezeigt werden für die der Benutzer Rechte
		hat (bei Delegated Admins) -->
		<div class="dropdown">
			<button class="btn adin-button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Domain auswählen
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="?domain=flashbrother.net">flashbrother.net</a>
				<a class="dropdown-item" href="?domain=flashbrother.net">test.dns.or.at</a>
			</div>
		</div>
		
		<!-- Übersichtstabelle 
			TODO: Aus der Datenbank auslesen
			- Die Domain wird entweder als GET-Parameter übergeben, oder es wird einfach die erste aus der Datenbank genommen
			- Es muss trotzdem noch geprüft werden, ob der Benutzer Zugriffsrechte hat, damit er nicht irgendwelche Domains abfragen kann
		-->
		<table class="overview-table">
			<tr>
				<th class="overview-table-content-cell">Benutzer-ID</th>
				<th class="overview-table-content-cell">Benutzername</th>
				<th class="overview-table-content-cell">Voller Name</th>
				<th class="overview-table-content-cell">Email-Adresse</th>
				<th class="overview-table-content-cell">Domain</th>
				<th class="overview-table-content-cell">Passwort</th>
				<th class="overview-table-content-cell">Weiterleiten an</th>
				<th class="overview-table-content-cell">Kopie an</th>
				<th class="overview-table-content-cell">Notiz</th>
				<th class="overview-table-button-cell"></th>
				<th class="overview-table-button-cell"></th>
			</tr>
			<tr>
				<td class="overview-table-content-cell">35</td>
				<td class="overview-table-content-cell">mfrank</td>
				<td class="overview-table-content-cell">Markus Frank</td>
				<td class="overview-table-content-cell">mfrank@flashbrother.net</td>
				<td class="overview-table-content-cell">flashbrother.net</td>
				<td class="overview-table-content-cell">************</td>
				<td class="overview-table-content-cell">mfrank@student.tgm.ac.at</td>
				<td class="overview-table-content-cell">mfrank@aon.at</td>
				<td class="overview-table-content-cell">Nur zu Testzwecken</td>
				<!-- TODO: Links müssen die ID der Mailbox enthalten, damit die Daten aus der Datenbank ausgelesen/gelöscht werden können! -->
				<td class="overview-table-button-cell">
					<a href="update.html" target="_blank">
						<img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
					</a>
				</td>
				<td class="overview-table-button-cell">
					<a href="delete.html" target="_blank">
						<img src="../img/delete.png" class="overview-table-delete-button" alt="Löschen">
					</a>
				</td>
			</tr>
		</table>
		
		<a href="new.html" class="btn mt-5 adin-button overview-table-add-button">
			<img src="../img/add.png" class="mr-3">
			Neue Mailbox hinzufügen
		</a>
	</div>
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
    <a href="../login/">Login</a>
	
	<?php endif; ?>
</body>
</html>