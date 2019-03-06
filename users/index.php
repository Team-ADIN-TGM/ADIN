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
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	
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
				<a class="adin-navbar-link" href="../home/">
					<img class="adin-navbar-item-img" src="../img/mail.png">
					<p class="adin-navbar-item-text">Mailboxen</p>
				</a>
			</div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../groups/">
					<img class="adin-navbar-item-img" src="../img/personen.png">
					<p class="adin-navbar-item-text">Verteiler</p>
				</a>
			</div>
			<div class="navbar-item adin-navbar-item">
				<a class="adin-navbar-link" href="../domains/">
					<img class="adin-navbar-item-img" src="../img/at.png">
					<p class="adin-navbar-item-text">Domains</p>
				</a>
			</div>
            <div class="navbar-item adin-navbar-item">
                <a class="adin-navbar-link" href="../login/logout.php">
                    <img class="adin-navbar-item-img" src="../img/logout.png">
                    <p class="adin-navbar-item-text">Logout</p>
                </a>
            </div>
		</div>
	</nav>
	
	<!-- Haupt-Container -->
	<div class="container-fluid">
		
		<h1 class="mt-3">Benutzer</h1>
		
		<!-- Übersichtstabelle -->
		<table class="overview-table">
			<tr>
				<th class="overview-table-content-cell">Voller Name</th>
				<th class="overview-table-content-cell">Benutzername</th>
				<th class="overview-table-content-cell">Email-Adresse</th>
				<th class="overview-table-content-cell">Passwort</th>
				<th class="overview-table-content-cell">Benutzer-Typ</th>
				<th class="overview-table-content-cell">Berechtigt für Domains</th>
				<th class="overview-table-button-cell"></th>
				<th class="overview-table-button-cell"></th>
			</tr>
			<tr>
				<td class="overview-table-content-cell">Markus Frank</td>
				<td class="overview-table-content-cell">mfrank</td>
				<td class="overview-table-content-cell">mfrank@flashbrother.net</td>
				<td class="overview-table-content-cell">************</td>
				<td class="overview-table-content-cell">Delegated Admin</td>
				<td class="overview-table-content-cell">
					flashbrother.net<br>
					test.dns.or.at<br>
				</td>
				<!-- TODO: Links müssen die ID der Domain enthalten, damit die Daten aus der Datenbank ausgelesen/gelöscht werden können! -->
				<td class="overview-table-button-cell">
					<a href="update.php" target="_blank">
						<img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
					</a>
				</td>
				<td class="overview-table-button-cell">
					<a href="delete.php" target="_blank">
						<img src="../img/delete.png" class="overview-table-delete-button" alt="Löschen">
					</a>
				</td>
			</tr>
		</table>
		
		<a href="new.php" class="btn mt-5 adin-button overview-table-add-button">
			<img src="../img/add.png" class="mr-3">
			Neuen Benutzer hinzufügen
		</a>
	</div>
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
    <a href="../login/">Login</a>
	
	<?php endif; ?>
</body>
</html>
