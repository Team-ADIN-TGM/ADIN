<?php
//TODO: Remove, just for debugging
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

session_start();
require_once '../connect.php';
require_once '../functions.php';

//mysqli-Objekt erstellen
$conn = get_database_connection();

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
<?php

    $user_logged_in = isset($_SESSION["user"]);
    if ($user_logged_in) {
        $userid = $_SESSION["userid"];

        if (isset($_POST["insert"])) {
            /*
             * Es soll ein neuer Benutzer-Account für ADIN hinzugefügt werden. Wenn das POST-Parameter insert gesetzt
             * ist, heißt das, dass die Anfrage von new.php kommt. Es muss trotzdem noch einmal geprüft werden, ob der
             * Benutzer Superuser ist, denn nur Superuser können neue Benutzer-Accounts hinzufügen.
             */

            if (current_user_has_rights_for_user("new", -1)) {
                //Der Benutzer hat die Rechte, einen neuen Benutzer hinzuzufügen

                $full_name = $_POST["fullname"];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $password_repeat = $_POST["password-repeat"];
                $user_type = $_POST["usertype"];

                // 1. ÜBERPRÜFEN, OB DIE PASSWÖRTER PASSEN
                // TODO: Passwort-Mindestanforderungen (z.B. min. 1 Zahl) mit RegEx überprüfen
                if (($password == $password_repeat) && (strlen($password) >= 6)) {

                    // 2. ÜBERPRÜFEN, OB DER BENUTZER-ACCOUNT SCHON VORHANDEN IST
                    $prep_stmt = $conn->prepare("SELECT * FROM Admins_tbl WHERE Username = ?;");
                    $prep_stmt->bind_param("s",$username);
                    $prep_stmt->execute();
                    $res = $prep_stmt->get_result();
                    $prep_stmt->close();

                    if ($res->num_rows == 0) {
                        //Der Benutzer ist noch nicht vorhanden

                        // 2. HINZUFÜGEN DES BENUTZERS IN Admins_tbl
                        $prep_stmt = $conn->prepare("INSERT INTO Admins_tbl (Username, FullName, Email, Password, UserType) VALUES (?, ?, ?, ?, ?);");
                        $prep_stmt->bind_param("sssss", $username, $full_name, $email, $password, $user_type);
                        $res1 = $prep_stmt->execute();

                        if ($prep_stmt->affected_rows < 1) echo "Beim Hinzufügen des Benutzers ist ein Fehler aufgetreten";

                        $prep_stmt->close();

                    } else {
                        //Der Benutzer ist schon vorhanden, kann also nicht mehr hinzugefügt werden
                        echo "Benutzer bereits vorhanden";
                    }
                } else {
                    echo "Passwörter nicht übereinstimmend oder zu kurz";
                }
            } else {
                echo "Keine Rechte";
            }

        } elseif (isset($_POST["update"])) {
            /*
             * Es soll ein Benutzer-Account aktualisiert werden. Wenn das POST-Parameter update gesetzt ist, heißt das,
             * dass die Anfrage von update.php kommt. Der Benutzer hat die Änderungen also schon bestätigt. Trotzdem
             * müssen noch einmal die Rechte geprüft werden (nur Superuser können Domains ändern).
             */

            $userid = intval($_POST["userid"]);

            if (current_user_has_rights_for_user("update", $userid)) {
                $full_name = $_POST["fullname"];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $password_repeat = $_POST["password-repeat"];
                $user_type = $_POST["usertype"];

                // 1. ÜBERPRÜFEN, OB DIE PASSWÖRTER PASSEN
                if (($password == $password_repeat) && ((strlen($password) >= 6) || (strlen($password) == 0))) {
                    /*
                     * Das Passwort muss beide Male gleich eingegeben worden sein und mindestens sechs Zeichen lang sein
                     * Wenn kein neues Passwort eingegeben wurde, ist die Länge von $password 0. Dann wird das Passwort
                     * nicht geändert.
                     */

                    // 2. ÜBERPRÜFEN, OB DIE AdminId VORHANDEN IST
                    $prep_stmt = $conn->prepare("SELECT * FROM Admins_tbl WHERE AdminId = ?;");
                    $prep_stmt->bind_param("i", $userid);
                    $prep_stmt->execute();
                    $res = $prep_stmt->get_result();
                    $prep_stmt->close();

                    if ($res->num_rows == 1) {
                        //Der Benutzer mit der AdminId ist vorhanden

                        // 3. ÄNDERN DER DATEN
                        if (strlen($password) != 0) {
                            //Es wurde ein neues Passwort übergeben, das gesetzt werden soll

                            $prep_stmt = $conn->prepare("UPDATE Admins_tbl 
                                SET FullName = ?, Username = ?, Email = ?, Password = ?, UserType = ? 
                                WHERE AdminId = ?;");
                            $prep_stmt->bind_param("sssssi", $full_name, $username, $email, $password, $user_type, $userid);

                            if (!$prep_stmt->execute()) {
                                //Es ist ein Fehler aufgetreten
                                echo "Fehler: ".$prep_stmt->error;
                            }

                            $prep_stmt->close();
                        } else {
                            //Es wurde kein neues Passwort übergeben - es wird nicht geändert

                            $prep_stmt = $conn->prepare("UPDATE Admins_tbl 
                                SET FullName = ?, Username = ?, Email = ?, UserType = ? 
                                WHERE AdminId = ?;");
                            $prep_stmt->bind_param("ssssi", $full_name, $username, $email, $user_type, $userid);

                            if (!$prep_stmt->execute()) {
                                //Es ist ein Fehler aufgetreten
                                echo "Fehler: ".$prep_stmt->error;
                            }

                            $prep_stmt->close();
                        }

                    } elseif ($res->num_rows == 0) {
                        echo "Benutzer nicht vorhanden";
                    } elseif ($res->num_rows > 1) {
                        echo "Fehler";
                    }
                } else {
                    echo "Passwörter nicht übereinstimmend oder zu kurz";
                }
            } else {
                //Der Benutzer hat nicht die Rechte, um den Benutzer-Account zu ändern.
                echo "Keine Rechte";
            }

        } elseif (isset($_POST["delete"])) {
            /*
             * Es soll ein Benutzer-Account für ADIN gelöscht werden. Wenn das POST-Parameter delete gesetzt ist, heißt
             * das, dass die Anfrage von delete.php kommt. Der Benutzer hat also schon bestätigt, dass die Domain
             * gelöscht werden soll. Aus Sicherheitsgründen muss trotzdem noch einmal geprüft werden, ob der Benutzer
             * die nötigen Rechte hat.
             */
            $user_to_delete = $_POST["userid"];

            //Überprüfung der Rechte
            $user_has_rights = current_user_has_rights_for_user("delete", $user_to_delete);

            if ($user_logged_in && $user_has_rights) {
                //Überprüfungen abgeschlossen - Domain kann gelöscht werden
                $prep_stmt = $conn->prepare("DELETE FROM Admins_tbl WHERE AdminId = ?;");
                $prep_stmt->bind_param("i", intval($user_to_delete));
                $prep_stmt->execute();

                if ($prep_stmt->affected_rows < 1) echo "Beim Löschen des Benutzers ist ein Fehler aufgetreten";

                $prep_stmt->close();
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
		
		<h1 class="mt-3">Benutzer</h1>
		
		<!-- Übersichtstabelle -->
		<table class="overview-table">
			<tr>
				<th class="overview-table-content-cell">Voller Name</th>
				<th class="overview-table-content-cell">Benutzername</th>
				<th class="overview-table-content-cell">Email-Adresse</th>
				<th class="overview-table-content-cell">Benutzer-Typ</th>
				<th class="overview-table-content-cell">Berechtigt für Domains</th>
				<th class="overview-table-button-cell"></th>
                <th class="overview-table-button-cell"></th>
			</tr>
			<tr>
                <?php

                if ($_SESSION["usertype"] == "superuser") {
                    //Alle Benutzer anzeigen
                    $main_query = "SELECT AdminId, FullName, Username, Email, UserType FROM Admins_tbl";
                    $main_res = $conn->query($main_query);

                } else {
                    //Delegated Admin - Nur den eigenen Benutzer anzeigen
                    $main_query = "SELECT AdminId, FullName, Username, Email, UserType FROM Admins_tbl WHERE AdminId = $userid;";
                    $main_res = $conn->query($main_query);
                }

                while ($row = $main_res->fetch_assoc()) {
                    ?>
                    <tr>

                        <td class="overview-table-content-cell"><?php echo $row["FullName"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["Username"] ?></td>
                        <td class="overview-table-content-cell"><?php echo $row["Email"] ?></td>
                        <td class="overview-table-content-cell">
                            <?php
                                if ($row["UserType"] == "superuser") echo "Superuser";
                                elseif ($row["UserType"] == "deladmin") echo "Delegated Admin";
                            ?>
                        </td>
                        <td class="overview-table-content-cell">
                            <?php
                                /*
                                 * Für Delegated Admins muss eine Liste mit allen Domains ausgelesen werden, für die sie
                                 * Domain-Admins sind.
                                 */
                                if ($row["UserType"] == "deladmin") {
                                    $adminid = $row["AdminId"];
                                    $domains_query = "SELECT DomainAdmin, 
                                        GROUP_CONCAT(DISTINCT DomainName ORDER BY DomainName ASC SEPARATOR '<br>') AS Domains
                                        FROM Domains_tbl
                                        INNER JOIN Domains_extend_tbl
                                        ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                                        WHERE DomainAdmin = $adminid;";

                                    $domains_res = $conn->query($domains_query);
                                    echo $domains_res->fetch_assoc()["Domains"];
                                } elseif ($row["UserType"] == "superuser") {
                                    echo "Alle";
                                }
                            ?>
                        </td>
                        <td class="overview-table-button-cell">
                            <a href="update.php?id=<?php echo $row["AdminId"]; ?>">
                                <img src="../img/edit.png" class="overview-table-edit-button" alt="Bearbeiten">
                            </a>
                        </td>
                        <?php
                            //Der Löschen-Button wird nur Superusern angezeigt.
                            if (current_user_has_rights_for_user("delete", intval($row["AdminId"]))): ?>
                                <td class="overview-table-button-cell">
                                    <a href="delete.php?id=<?php echo $row["AdminId"]; ?>">
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

        <?php if (current_user_has_rights_for_user("new", -1)): ?>
            <!-- Der Button zum Hinzufügen von neuen Benutzern wird nur Superusern angezeigt. -->
            <a href="new.php" class="btn mt-5 mb-5 adin-button overview-table-add-button">
                <img src="../img/add.png" class="mr-3">
                Neuen Benutzer hinzufügen
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
