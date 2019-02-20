<!--
TODO:
- Dynamisches Auslesen der Datenbank und füllen der Tabelle
- Setzen der Links zu delete/new/update-Seiten
- Reagieren auf Aktionen in den delete/new/update-Seiten
-->

<?php
	session_start();
	include "../connect.php";

	if (isset($_POST["delete"])) {
	    /*
	     * Es soll eine Domain gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt das, dass die Anfrage
         * von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Domain gelöscht werden soll.
	     * Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen Rechte hat.
	     */
	    $domainid = $_POST["domainid"];
	    $userid = $_SESSION["userid"];

	    //TODO: Für Fehlerbehandlung
        $noerror = true;

	    //Überprüfung, ob ein Benutzer eingeloggt ist
	    $user_logged_in = isset($_SESSION["user"]);

	    //Schnelle Überprüfung auf Berechtigungen
        $sql = "SELECT Domains_tbl.DomainId FROM Domains_tbl
        INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
        INNER JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
        WHERE Admins_tbl.AdminId = $userid;";
        $res = $conn->query($sql);

        $user_has_rights = false;
        while ($row = $res->fetch_assoc()) {
            if ($row["DomainId"] == $domainid) {
                //Domain wurde gefunden - Benutzer hat Rechte
                $user_has_rights = true;
                $domain_name = $row["DomainName"];
                break;
            }
        }

        if ($user_logged_in && $user_has_rights && $noerror) {
            //Überprüfungen abgeschlossen - Domain kann gelöscht werden
            $res = $conn->query("DELETE FROM Domains_tbl WHERE DomainId = $domainid;");
            if (!$res) echo "Beim Löschen der Domain ist ein Fehler aufgetreten";
        }
    }
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
		
		<h1 class="mt-3">Domains</h1>
		
		<!-- Übersichtstabelle -->
		<table class="overview-table">
			<tr>
				<th class="overview-table-content-cell">Domain-ID</th>
				<th class="overview-table-content-cell">Domain-Name</th>
				<th class="overview-table-content-cell">Domain-Admin</th>
				<th class="overview-table-content-cell">Email-Adresse des Domain-Admins</th>
				<th class="overview-table-button-cell"></th>
				<th class="overview-table-button-cell"></th>
			</tr>
            <?php
                //TODO: Hier müssen alle Domains, für die der Benutzer Rechte hat, ausgelesen und angezeigt werden
                //TODO: Links müssen die ID der Domain enthalten, damit die Daten aus der Datenbank ausgelesen/gelöscht werden können!
            ?>
            <!--
			<tr>
				<td class="overview-table-content-cell">1</td>
				<td class="overview-table-content-cell">test.dns.or.at</td>
				<td class="overview-table-content-cell">mfrank</td>
				<td class="overview-table-content-cell">mfrank@flashbrother.net</td>
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
            -->
		</table>
		
		<a href="new.php" class="btn mt-5 adin-button overview-table-add-button">
			<img src="../img/add.png" class="mr-3">
			Neue Domain hinzufügen
		</a>
	</div>
	
	<?php else: ?>
	
	<p>Sie sind nicht angemeldet!</p>
    <a href="../login/">Login</a>
	
	<?php endif; ?>	
</body>
</html>