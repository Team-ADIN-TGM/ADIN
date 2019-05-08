<!--
TODO:
- Dynamisches Auslesen der Datenbank und füllen der Tabelle
- Setzen der Links zu delete/new/update-Seiten
- Reagieren auf Aktionen in den delete/new/update-Seiten
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

//Regular Expression für Domains
//siehe http://regexr.com/4cq66
$DOMAIN_REGEX = get_domain_regex();

?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Mailboxen</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <!-- JavaScript - ausnahmsweise verwendet, weil erforderlich für Bootstrap für das Domain-Auswahfeld -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

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
             * Es soll eine Mailbox gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt das, dass die Anfrage
             * von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Mailbox gelöscht werden soll.
             * Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen Rechte hat.
             */

        } elseif (isset($_POST["insert"])) {
            /*
             * Es soll eine Mailbox hinzugefügt werden. Wenn das POST-Parameter insert gesetzt ist, heißt das, dass die
             * Anfrage von new.php kommt. Es muss trotzdem noch einmal geprüft werden, ob der Benutzer die nötigen
             * Rechte hat.
             */

        } elseif (isset($_POST["update"])) {
            /*
             * Es soll eine Mailbox aktualisiert werden. Wenn das POST-Parameter update gesetzt ist, heißt das, dass
             * die Anfrage von update.php kommt. Der Benutzer hat die Änderungen also schon bestätigt. Trotzdem müssen noch
             * einmal die Rechte geprüft werden.
             */

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

            <h1 class="mt-3">Mailboxen</h1>

            <!-- Zur Auswahl der Domain, deren Mailboxen angezeigt werden sollen -->
            <!-- TODO: Links, es dürfen nur die Domains angezeigt werden für die der Benutzer Rechte
            hat (bei Delegated Admins) -->
            <div class="dropdown">
                <button class="btn adin-button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                        //Der DomainName der ausgewählten Domain soll angezeigt werden

                        if (isset($_GET["domainid"])) {
                            $domainid = $_GET["domainid"];

                            /*
                             * Überprüfen:
                             * - Existiert die per GET-Parameter angegebene Domain?
                             * - Hat der Benutzer die Rechte, um darauf zuzugreifen?
                             */

                            $domain_exists = false;
                            $user_has_rights_for_domain = false;

                            $prep_stmt = $conn->prepare("SELECT DomainName FROM Domains_tbl WHERE DomainId = ?;");
                            $prep_stmt->bind_param("i", $domainid);
                            $prep_stmt->execute();
                            $res = $prep_stmt->get_result();
                            $prep_stmt->close();

                            if ($res && $res->num_rows == 1) {
                                //Die Domain existiert
                                $domain_exists = true;
                                $domainname = $res->fetch_assoc()["DomainName"];
                                $res->close();

                                //Überprüfen, ob der Benutzer die Rechte hat, um auf die Domain zuzugreifen
                                $user_has_rights_for_domain = false;

                                if ($usertype == "superuser") {
                                    //Superuser haben auf jeden Fall die Rechte, um die Mailboxen aller Domains anzuzeigen
                                    $user_has_rights_for_domain = true;

                                } else {
                                    //Delegated Admins können nur die Domains anzeigen, deren Admin sie sind
                                    $prep_stmt = $conn->prepare("SELECT DomainAdmin FROM Domains_extend_tbl WHERE DomainId = ?;");
                                    $prep_stmt->bind_param("i", $domainid);
                                    $prep_stmt->execute();
                                    $res = $prep_stmt->get_result();
                                    $prep_stmt->close();

                                    if ($res && $res->fetch_assoc()["DomainAdmin"] == $userid) {
                                        $user_has_rights_for_domain = true;
                                    }

                                    $res->close();
                                }

                                if ($user_has_rights_for_domain) {
                                    echo $domainname;

                                } else {
                                    /*
                                     * Der Benutzer hat nicht die Rechte, die Mailboxen der Domain anzuzeigen - daher
                                     * sollte er auch nicht den Namen der Domain angezeigt bekommen.
                                     */
                                    echo "Domain auswählen";
                                }

                            } else {
                                //Die Domain existiert nicht
                                echo "Domain auswählen";
                            }

                        } else {
                            //Es wurde keine Domain-ID per GET-Parameter übergeben
                            echo "Domain auswählen";
                        }
                    ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php

                        //Laden aller Domains, für die der Benutzer Rechte hat
                        if ($usertype == "superuser") {
                            //Der Benutzer ist Superuser und hat Rechte für alle Domains - alle existierenden Domains laden
                            $res = $conn->query("SELECT DomainId, DomainName FROM Domains_tbl ORDER BY DomainName;");
                        } else {
                            //Der Benutzer ist Delegated Admin - nur Domains laden, für die er Rechte hat
                            $prep_stmt = $conn->prepare("SELECT DomainId, DomainName 
                                FROM Domains_tbl NATURAL JOIN Domains_extend_tbl 
                                WHERE Domains_extend_tbl.DomainAdmin = ?
                                ORDER BY DomainName;");
                            $prep_stmt->bind_param("i", $userid);
                            $prep_stmt->execute();
                            $res = $prep_stmt->get_result();
                            $prep_stmt->close();
                        }


                        //Einfügen der Domains ins Dropdown-Menü
                        if ($res) {
                            while ($row = $res->fetch_assoc()) {
                                $did = $row["DomainId"];
                                $dn = $row["DomainName"];
                                ?>

                                <?php if (isset($_GET["domainid"]) && $did == $_GET["domainid"]): ?>
                                    <a class="dropdown-item active" href="?domainid=<?php echo $row["DomainId"]; ?>">
                                        <?php echo $row["DomainName"]; ?>
                                    </a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="?domainid=<?php echo $row["DomainId"]; ?>">
                                        <?php echo $row["DomainName"]; ?>
                                    </a>
                                <?php endif;

                            }
                        }

                    ?>
                </div>
            </div>

            <?php
                if (isset($domainid)) {

                    if ($domain_exists) {
                        if ($user_has_rights_for_domain) {
                            ?>

                            <table class="overview-table">
                                <tr>
                                    <th class="overview-table-content-cell">Benutzername</th>
                                    <th class="overview-table-content-cell">Voller Name</th>
                                    <th class="overview-table-content-cell">Email-Adresse</th>
                                    <th class="overview-table-content-cell">Weiterleiten an</th>
                                    <th class="overview-table-content-cell">Kopie an</th>
                                    <th class="overview-table-content-cell">Notiz</th>
                                    <th class="overview-table-button-cell"></th>
                                    <th class="overview-table-button-cell"></th>
                                </tr>

                                <?php
                                //TODO: Weiterleitungen und "Kopie an" auslesen!

                                $prep_stmt = $conn->prepare("SELECT Users_tbl.UserId, Username, FullName, Email, AdminNote, Postmaster 
                                        FROM Users_tbl 
                                        INNER JOIN Users_extend_tbl ON Users_tbl.UserId = Users_extend_tbl.UserId 
                                        WHERE Users_tbl.DomainId = ?
                                        ORDER BY Postmaster DESC, Username ASC;");
                                $prep_stmt->bind_param("i", $domainid);
                                $prep_stmt->execute();
                                $res = $prep_stmt->get_result();
                                $prep_stmt->close();

                                //Hinzufügen der einzelnen Zeilen in die Tabelle - eine Zeile für jede Mailbox der Domain
                                while ($row = $res->fetch_assoc()) {
                                    $uid = $row["UserId"];
                                    $un = $row["Username"];
                                    $fn = $row["FullName"];
                                    $em = $row["Email"];
                                    $an = $row["AdminNote"];
                                    $pm = $row["Postmaster"];

                                    //Zur Zeile der Postmaster-Mailbox eine extra CSS-Klasse hinzufügen
                                    if ($pm): ?>
                                        <tr class="mailbox-postmaster">
                                    <?php else: ?>
                                        <tr>
                                    <?php endif; ?>

                                    <td class="overview-table-content-cell"><?php echo $un; ?></td>
                                    <td class="overview-table-content-cell"><?php echo $fn; ?></td>
                                    <td class="overview-table-content-cell"><?php echo $em; ?></td>
                                    <td class="overview-table-content-cell">(keine Weiterleitung)</td>
                                    <td class="overview-table-content-cell">(keine Weiterleitung)</td>
                                    <td class="overview-table-content-cell"><?php echo $an; ?></td>

                                    <td class="overview-table-button-cell">
                                        <a href="update.php?id=<?php echo $uid; ?>" target="_blank">
                                            <img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
                                        </a>
                                    </td>
                                    <td class="overview-table-button-cell">
                                        <?php if (!$pm): ?>
                                            <a href="delete.php?id=<?php echo $uid; ?>" target="_blank">
                                                <img src="../img/delete.png" class="overview-table-delete-button" alt="Löschen">
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </table>

                            <a href="new.php?domainid=<?php echo $domainid; ?>" class="btn mt-5 mb-5 adin-button overview-table-add-button">
                                <img src="../img/add.png" class="mr-3">
                                Neue Mailbox hinzufügen
                            </a>

                            <?php
                        } else {
                            //Der Benutzer hat keine Rechte, um die Mailboxen anzuzeigen - Fehlermeldung
                            ?>

                            <div class="container-fluid mt-3">
                                <h3 class="mb-3">Keine Berechtigung</h3>

                                <span class="mb-3">
                                    Da Sie weder Superuser noch der Domain-Admin sind, können Sie die Mailboxen dieser Domain
                                    nicht ansehen. Bitte wenden Sie sich an <a href="mailto:bla@wtf.com">Email</a>.
                                    <!-- TODO: Kontakt-Adresse hinzufügen -->
                                </span>
                            </div>

                            <?php
                        }

                    } else {
                        //Die Domain existiert nicht
                        ?>

                        <div class="container-fluid mt-3">
                            <h3 class="mb-3">Domain existiert nicht</h3>

                            <span class="mb-3">
                                Die Domain mit der ID <?php echo $domainid; ?> existiert nicht.<br>
                                Bitte wählen Sie über das Menü eine Domain aus.
                            </span>
                        </div>

                        <?php
                    }
                }
            ?>
        </div>

        <?php

    } else {
        //Der Benutzer ist nicht angemeldet
        ?>

        <div class="container-fluid mt-3">
            <h3 class="mb-3">Nicht angemeldet</h3>

            <span class="mb-3">
            Sie sind nicht angemeldet. Bitte melden Sie sich an, um mit ADIN zu arbeiten.<br>
            <a href="../login/">Hier geht es zum Login</a>
        </span>
        </div>
	
	    <?php
    }
?>
</body>
</html>
