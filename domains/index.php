<!--
TODO:
- Evtl. einen Neu-laden-Button (neu laden mit F5 sendet eventuell vorhandene GET- und POST-Parameter erneut)
- Was passiert, wenn eine Domain hinzugefügt werden soll, die schon existiert? Oder eine Domain, die nicht dem richtigen
  Format entspricht? Wo wird die Fehlermeldung angezeigt?
- Alle echos entfernen und durch die Fehlermeldung ersetzen
- Spalte mit ID entfernen, Spalte mit Vollem Namen des Admins hinzufügen
- In der Spalte mit Email des Admins einen Link mit "mailto:bla@wtf.com" einfügen
-->
<?php

//TODO: Remove, just for debugging
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

session_start();
require_once "../connect.php";
require_once "../functions.php";

//mysqli-Objekt erstellen
$conn = get_database_connection();

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

    if ($user_logged_in) {
        $userid = $_SESSION["userid"];
        $usertype = $_SESSION["usertype"];

        /****************************************************************************
         * BEHANDELN VON AKTIONEN AUS DEN SEITEN new.php, update.php UND delete.php *
         ****************************************************************************/
        if (isset($_POST["delete"])) {
            /*
             * Es soll eine Domain gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt das, dass die Anfrage
             * von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Domain gelöscht werden soll.
             * Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen Rechte hat.
             */

            $domain_id = $_POST["domainid"];

            //Überprüfung der Rechte
            $user_has_rights = current_user_has_rights_for_domain("delete", $domain_id);

            if ($user_has_rights) {
                //Überprüfungen abgeschlossen - Domain kann gelöscht werden
                $res = $conn->query("DELETE FROM Domains_tbl WHERE DomainId = $domain_id;");
                if (!$res) echo "Beim Löschen der Domain ist ein Fehler aufgetreten";
            }
        } elseif (isset($_POST["insert"])) {
            /*
             * Es soll eine Domain hinzugefügt werden. Wenn das POST-Parameter insert gesetzt ist, heißt das, dass die von
             * new.php kommt. Es muss trotzdem noch einmal geprüft werden, ob der Benutzer Superuser ist, denn nur
             * Superuser können neue Domains hinzufügen.
             */

            if (current_user_has_rights_for_domain("new", -1)) {
                //Der Benutzer hat die Rechte, eine neue Domain hinzuzufügen
                $domain_name = $_POST["domainname"];
                $domain_admin = intval($_POST["domainadmin"]);

                // 1. ÜBERPRÜFEN, OB DIE DOMAIN SCHON VORHANDEN IST
                //TODO: Überprüfen, ob Domain der Regex entspricht
                $res = $conn->query("SELECT * FROM Domains_tbl WHERE DomainName = '$domain_name';");
                if ($res->num_rows == 0) {
                    //Die Domain ist noch nicht vorhanden

                    // 2. HINZUFÜGEN DER DOMAIN IN Domains_tbl
                    $prep_stmt = $conn->prepare("INSERT INTO Domains_tbl (DomainName) VALUES (?)");
                    $prep_stmt->bind_param("s", $domain_name);
                    $res1 = $prep_stmt->execute();
                    $prep_stmt->close();

                    // 3. AUSLESEN DER AUTOMATISCH VERGEBENEN ID
                    if ($res1) {
                        //Die Domain wurde erfolgreich in Domains_tbl hinzugefügt

                        $prep_stmt = $conn->prepare("SELECT * FROM Domains_tbl WHERE DomainName = ?");
                        $prep_stmt->bind_param("s", $domain_name);
                        $prep_stmt->execute();
                        $res2 = $prep_stmt->get_result();
                        $prep_stmt->close();

                        // 4. HINZUFÜGEN DER DOMAIN MIT DOMAIN-ADMIN IN Domains_extend_tbl
                        if ($res2) {
                            //Die Domain-ID wurde erfolgreich ausgelesen

                            $domain_id = intval($res2->fetch_assoc()["DomainId"]);
                            $prep_stmt = $conn->prepare("INSERT INTO Domains_extend_tbl (DomainId, DomainAdmin) VALUES (?, ?)");
                            $prep_stmt->bind_param("ii", $domain_id, $domain_admin);
                            $res3 = $prep_stmt->execute();
                            $prep_stmt->close();

                            if (!$res3) echo "Domain wurde nicht hinzugefügt";
                        } else {
                            //Die Domain ID wurde nicht ausgelesen
                            echo "Die Domain-ID konnte nicht ausgelesen werden";
                        }
                    } else {
                        //Die Domain konnte nicht in Domains_tbl hinzugefügt werden
                        echo "Domain konnte nicht hinzugefügt werden";
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
                    echo "Domain bereits vorhanden";
                }

                /*
                 * TODO:
                 * - Überprüfung des Domain-Namens, ob er dem richtigen Format entspricht (evtl. mit Regex?)
                 *   --> STANDARDISIERT!!! Keine eigene RegEx schreiben!
                 */

            } else {
                echo "Keine Rechte";
            }
        } elseif (isset($_POST["update"])) {
            /*
             * Es soll ein Benutzer-Account aktualisiert werden. Wenn das POST-Parameter update gesetzt ist, heißt das, dass
             * die Anfrage von update.php kommt. Der Benutzer hat die Änderungen also schon bestätigt. Trotzdem müssen noch
             * einmal die Rechte geprüft werden (nur Superuser können Benutzer-Accounts ändern).
             */

            $domain_id = intval($_POST["domainid"]);

            if (current_user_has_rights_for_domain("update", $domain_id)) {
                $domain_name = $_POST["domainname"];
                $domain_admin = $_POST["domainadmin"];

                // 1. ÜBERPRÜFEN, OB DIE DOMAIN ÜBERHAUPT VORHANDEN IST
                $prep_stmt = $conn->prepare("SELECT * FROM Domains_tbl
                INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
                WHERE Domains_tbl.DomainId = ?;");
                $prep_stmt->bind_param("i", $domain_id);
                $prep_stmt->execute();
                $res1 = $prep_stmt->get_result();
                $prep_stmt->close();

                if ($res1->num_rows == 1) {
                    //Die Domain ist vorhanden

                    // 2. UPDATEN DES DOMAIN-NAMENS
                    $prep_stmt = $conn->prepare("UPDATE Domains_tbl SET DomainName = ? WHERE DomainId = ?");
                    $prep_stmt->bind_param("si", $domain_name, $domain_id);
                    $res2 = $prep_stmt->execute();
                    $prep_stmt->close();

                    if ($res2) {
                        //Der Domain-Name wurde aktualisiert

                        // 3. UPDATEN DES DOMAIN-ADMINS
                        $prep_stmt = $conn->prepare("UPDATE Domains_extend_tbl SET DomainAdmin = ? WHERE DomainId = ?");
                        $prep_stmt->bind_param("ii", $domain_admin, $domain_id);
                        $res3 = $prep_stmt->execute();
                        $prep_stmt->close();

                        if (!isset($res3) || !$res3) {
                            //Beim Aktualisieren des Domain-Admins ist ein Fehler aufgetreten
                            echo "Beim Aktualisieren des Domain-Admins ist ein Fehler aufgetreten";
                        }
                    } else {
                        //Beim Aktualisieren des Domain-Namens ist ein Fehler aufgetreten
                        echo "Beim Aktualisieren des Domain-Namens ist ein Fehler aufgetreten";
                    }

                } elseif ($res->num_rows < 1) {
                    echo "Domain nicht vorhanden";
                } elseif ($res->num_rows > 1) {
                    echo "Fehler";
                }

            } else {
                //Der Benutzer hat nicht die Rechte für die Domains
                echo "Keine Rechte";
            }
        }

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
				<th class="overview-table-content-cell">Domain-Name</th>
				<th class="overview-table-content-cell">Domain-Admin</th>
                <th class="overview-table-content-cell">Name des Domain-Admins</th>
				<th class="overview-table-content-cell">Email-Adresse des Domain-Admins</th>
                <th class="overview-table-button-cell"></th>
                <th class="overview-table-button-cell"></th>
			</tr>
            <?php

                if ($usertype == "superuser") {
                    //Alle Domains anzeigen
                    $sql = "SELECT Domains_tbl.DomainId, Domains_tbl.DomainName, Admins_tbl.Username, Admins_tbl.FullName, Admins_tbl.Email FROM Domains_tbl
                    INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                    LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId;";
                } else {
                    //Delegated Admin - Nur die Domains auslesen, für die der Benutzer Domain-Admin ist
                    $sql = "SELECT Domains_tbl.DomainId, Domains_tbl.DomainName, Admins_tbl.Username, Admins_tbl.FullName, Admins_tbl.Email FROM Domains_tbl
                    INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                    LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
                    WHERE Admins_tbl.AdminId = $userid;";
                }

                $res = $conn->query($sql);

                while ($row = $res->fetch_assoc()) {
                    ?>

                    <tr>
                        <td class="overview-table-content-cell"><?php echo $row["DomainName"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["Username"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["FullName"] ?></td>
                        <td class="overview-table-content-cell">
                            <a href="mailto:<?php echo $row["Email"]; ?>"><?php echo $row["Email"] ?></a>
                        </td>
                        <?php if (current_user_has_rights_for_domain("update", $row["DomainId"])): ?>
                            <!-- Buttons zum Bearbeiten und Löschen werden nur angezeigt, wenn der Benutzer die Rechte dafür hat -->
                            <td class="overview-table-button-cell">
                                <a href="update.php?id=<?php echo $row["DomainId"]; ?>">
                                    <img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
                                </a>
                            </td>
                        <?php
                            endif;
                            if (current_user_has_rights_for_domain("delete", $row["DomainId"])):
                                ?>
                                <td class="overview-table-button-cell">
                                    <a href="delete.php?id=<?php echo $row["DomainId"]; ?>">
                                        <img src="../img/delete.png" class="overview-table-delete-button" alt="Löschen">
                                    </a>
                                </td>
                                <?php
                            endif;
                        ?>
                    </tr>

                    <?php
                }
            ?>

		</table>

        <?php if (current_user_has_rights_for_domain("new", -1)): ?>
            <!-- Der Button zum Hinzufügen von Domains wird nur angezeigt, wenn man die Recht dafür hat -->
            <a href="new.php" class="btn mt-5 mb-5 adin-button overview-table-add-button">
                <img src="../img/add.png" class="mr-3">
                Neue Domain hinzufügen
            </a>
        <?php endif; ?>
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
