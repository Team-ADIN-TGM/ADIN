<!--
TODO:
- Reagieren auf Aktionen in den delete/new/update-Seiten
- Evtl. einen Neu-laden-Button (neu laden mit F5 sendet eventuell vorhandene GET- und POST-Parameter erneut)
- Buttons zum Löschen/bearbeiten/etc sollte man nur sehen, wenn man auch die Rechte hat, die Aktionen durchzuführen
- Was passiert, wenn eine Domain hinzugefügt werden soll, die schon existiert? Oder eine Domain, die nicht dem richtigen
  Format entspricht? Wo wird die Fehlermeldung angezeigt?
- Alle echos entfernen und durch die Fehlermeldung ersetzen
-->
<?php
session_start();
include "../connect.php";
include "../functions.php";

//TODO: Remove, just for debugging
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>ADIN - Domains</title>
    <meta charset="utf-8">

    <!-- Stylesheets -->
    <link type="text/css" rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    
</head>
<body>

<?php
    //Überprüfung, ob ein Benutzer eingeloggt ist
    $user_logged_in = isset($_SESSION["user"]);
    $userid = $_SESSION["userid"];

	if (isset($_POST["delete"])) {
	    /*
	     * Es soll eine Domain gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt das, dass die Anfrage
         * von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Domain gelöscht werden soll.
	     * Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen Rechte hat.
	     */
	    $domain_id = $_POST["domainid"];

        //Überprüfung der Rechte
        $user_has_rights = current_user_has_rights_for_domain($domain_id, "delete");

        if ($user_logged_in && $user_has_rights) {
            //Überprüfungen abgeschlossen - Domain kann gelöscht werden
            $res = $conn->query("DELETE FROM Domains_tbl WHERE DomainId = $domain_id;");
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

            // 1. ÜBERPRÜFEN, OB DIE DOMAIN SCHON VORHANDEN IST
            $res = $conn->query("SELECT * FROM Domains_tbl WHERE DomainName = '$domain_name';");
            if ($res->num_rows == 0) {
                //Die Domain ist noch nicht vorhanden
                echo "Domain noch nicht vorhanden";

                // 2. HINZUFÜGEN DER DOMAIN IN Domains_tbl
                $prep_stmt = $conn->prepare("INSERT INTO Domains_tbl (DomainName) VALUES (?)");
                $prep_stmt->bind_param("s", $domain_name);
                $res1 = $prep_stmt->execute();
                $prep_stmt->close();

                // 3. AUSLESEN DER AUTOMATISCH VERGEBENEN ID
                if ($res1) {
                    //Die Domain wurde erfolgreich in Domains_tbl hinzugefügt
                    echo "Domain wurde hinzugefügt";

                    $prep_stmt = $conn->prepare("SELECT * FROM Domains_tbl WHERE DomainName = ?");
                    $prep_stmt->bind_param("s", $domain_name);
                    $prep_stmt->execute();
                    $res2 = $prep_stmt->get_result();
                    $prep_stmt->close();

                    // 4. HINZUFÜGEN DER DOMAIN MIT DOMAIN-ADMIN IN Domains_extend_tbl
                    if ($res2) {
                        //Die Domain-ID wurde erfolgreich ausgelesen
                        echo "DomainId ausgelesen";

                        $domain_id = intval($res2->fetch_assoc()["DomainId"]);
                        $prep_stmt = $conn->prepare("INSERT INTO Domains_extend_tbl (DomainId, DomainAdmin) VALUES (?, ?)");
                        $prep_stmt->bind_param("ii", $domain_id, $domain_admin);
                        $res3 = $prep_stmt->execute();
                        $prep_stmt->close();

                        if ($res3) echo "Domain wurde hinzugefügt";
                    }
                }

                if (!$res3 || !isset($res3)) {
                    /*
                     * Fehlerfall - einer der Datenbankzugriffe ist fehlgeschlagen
                     * Es darf natürlich keine "halbe Domain" vorhanden sein (also eine Domain, die zwar in der Tabelle
                     * Domains_tbl, aber nicht in Domains_extend_tbl hinzugefügt wurde). Die Domain muss also wieder
                     * aus Domains_tbl gelöscht werden.
                     */
                    $sql = "DELETE FROM Domains_tbl WHERE DomainName = ?";
                    $prep_stmt = $conn->prepare($sql);
                    $prep_stmt->bind_param("s", $domain_name);
                    $res = $prep_stmt->execute();

                    echo "Es ist ein Fehler aufgetreten<br>";
                    echo ($res ? "Die Domain wurde erfolgreich gelöscht<br>" : "Die Domain konnte nicht gelöscht werden!<br>");
                    echo $conn->errno." // ".$conn->error;
                }

            } else {
                //Die Domain ist schon vorhanden, kann also nicht mehr hinzugefügt werden
                $noerror = false;
                echo "Domain vorhanden";
            }

            /*
             * TODO:
             * - Überprüfung ob Domain schon vorhanden (was wenn ja??)
             * - Überprüfung des Domain-Namens, ob er dem richtigen Format entspricht (evtl. mit Regex?)
             *   --> STANDARDISIERT!!! Keine eigene RegEx schreiben!
             */

        } else {
            echo "Keine Rechte";
        }
    } elseif (isset($_POST["update"])) {
        /*
	     * Es soll eine Domain aktualisiert werden. Wenn das POST-Parameter update gesetzt ist, heißt das, dass die
         * Anfrage von update.php kommt. Der Benutzer hat die Änderungen also schon bestätigt. Trotzdem müssen noch
         * einmal die Rechte geprüft werden (nur der Domain-Admin und Superuser können Domains ändern).
	     */
    }

    if ($user_logged_in) {
?>
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
	
	<?php } else { ?>
    <!-- Nicht angemeldet -->

    <div class="container-fluid mt-3">
        <h3 class="mb-3">Nicht angemeldet</h3>

        <span class="mb-3">
        Sie sind nicht angemeldet. Bitte melden Sie sich an, um mit ADIN zu arbeiten.<br>
        <a href="../login/">Hier geht es zum Login</a>
    </span>
    </div>
	
	<?php } ?>
</body>
</html>
