<!--
TODO:
- Reagieren auf Aktionen in den delete/new/update-Seiten
- Evtl. einen Neu-laden-Button (neu laden mit F5 sendet eventuell vorhandene GET- und POST-Parameter erneut)
- Buttons zum Löschen/bearbeiten/etc sollte man nur sehen, wenn man auch die Rechte hat, die Aktionen durchzuführen
- Was passiert, wenn eine Domain hinzugefügt werden soll, die schon existiert? Oder eine Domain, die nicht dem richtigen
  Format entspricht? Wo wird die Fehlermeldung angezeigt?
-->

<?php
	session_start();
	include "../connect.php";

    $userid = $_SESSION["userid"];


    if (isset($_POST["delete"])) {
        echo "_POST[delete] is set";
    } else {
        echo "_POST[delete] is not set";
    }

	if (isset($_POST["delete"])) {
	    /*
	     * Es soll eine Domain gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt das, dass die Anfrage
         * von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Domain gelöscht werden soll.
	     * Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen Rechte hat.
	     */
	    $domainid = $_POST["domainid"];
	    echo "DELETE $domainid";

	    //TODO: Fehlerbehandlung
        $noerror = true;

	    //Überprüfung, ob ein Benutzer eingeloggt ist
	    $user_logged_in = isset($_SESSION["user"]);
        echo ($user_logged_in ? "logged in" : "not logged in");

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

        echo ($user_has_rights ? "user has rights" : "user has no rights");

        if ($user_logged_in && $user_has_rights && $noerror) {
            //Überprüfungen abgeschlossen - Domain kann gelöscht werden
            $res = $conn->query("DELETE FROM Domains_tbl WHERE DomainId = $domainid;");
            if (!$res) echo "Beim Löschen der Domain ist ein Fehler aufgetreten";
        }
    } elseif (isset($_POST["insert"])) {
        /*
	     * Es soll eine Domain hinzugefügt werden. Wenn das POST-Parameter insert gesetzt ist, heißt das, dass die von
         * new.php kommt. Es muss trotzdem noch einmal geprüft werden, ob der Benutzer Superuser ist, denn nur
         * Superuser können neue Domains hinzufügen
	     */

        if ($_SESSION["usertype"] == "superuser") {
            //Der Benutzer hat die Rechte, eine neue Domain hinzuzufügen
            $domain_name = $_POST["domainname"];
            $domain_admin = intval($_POST["domainadmin"]);

            /*
             * TODO:
             * - Überprüfung ob Domain schon vorhanden (was wenn ja??)
             * - Überprüfung des Domain-Namens, ob er dem richtigen Format entspricht (evtl. mit Regex?)
             *   --> STANDARDISIERT!!! Keine eigene RegEx schreiben!
             * - INSERT in Domains_tbl:
             *   INSERT INTO Domains_tbl (DomainName) VALUES ('example.contoso.com');
             * - Auslesen der ID, die der Domain zugewiesen wurde
             *   SELECT DomainId FROM Domains_tbl WHERE DomainName = 'example.contoso.com';
             * - INSERT in Domains_extend_tbl:
             *   INSERT INTO Domains_extend_tbl (DomainId, DomainAdmin) VALUES (id, admin);
             */

        }
    } elseif (isset($_POST["update"])) {
        /*
	     * Es soll eine Domain aktualisiert werden. Wenn das POST-Parameter update gesetzt ist, heißt das, dass die
         * Anfrage von update.php kommt. Der Benutzer hat die Änderungen also schon bestätigt. Trotzdem müssen noch
         * einmal die Rechte geprüft werden (nur der Domain-Admin und Superuser können Domains ändern).
	     */
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

                if ($_SESSION["usertype"] == "superuser") {
                    //Alle Domains anzeigen
                    $sql = "SELECT Domains_tbl.DomainId, Domains_tbl.DomainName, Admins_tbl.Username, Admins_tbl.Email FROM Domains_tbl
                    INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                    LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId;";
                } else {
                    //Delegated Admin - Nur die Domains auslesen, für die der Benutzer Domain-Admin ist
                    $sql = "SELECT Domains_tbl.DomainId, Domains_tbl.DomainName, Admins_tbl.Username, Admins_tbl.Email FROM Domains_tbl
                    INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                    LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
                    WHERE Admins_tbl.AdminId = $userid;";
                }

                $res = $conn->query($sql);

                while ($row = $res->fetch_assoc()) {
                    ?>

                    <tr>
                        <td class="overview-table-content-cell"><?php echo $row["DomainId"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["DomainName"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["Username"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["Email"] ?></td>
                        <td class="overview-table-button-cell">
                            <a href="update.php?id=<?php echo $row["DomainId"]; ?>">
                                <img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
                            </a>
                        </td>
                        <td class="overview-table-button-cell">
                            <a href="delete.php?id=<?php echo $row["DomainId"]; ?>">
                                <img src="../img/delete.png" class="overview-table-delete-button" alt="Löschen">
                            </a>
                        </td>
                    </tr>

                    <?php
                }
            ?>

		</table>

		<a href="new.php" class="btn mt-5 adin-button overview-table-add-button">
			<img src="../img/add.png" class="mr-3">
			Neue Domain hinzufügen
		</a>
	</div>
	
	<?php else: ?>

    <div class="container-fluid mt-3">
        <h3 class="mb-3">Nicht angemeldet</h3>

        <span class="mb-3">
        Sie sind nicht angemeldet. Bitte melden Sie sich an, um mit ADIN zu arbeiten.<br>
        <a href="../login/">Hier geht es zum Login</a>
    </span>
    </div>
	
	<?php endif; ?>	
</body>
</html>