<!--
TODO:
- Überprüfen, ob der Benutzer die notwendigen Rechte hat, um den Benutzer zu bearbeiten
- Daten aus der Datenbank auslesen und alle Eingabefelder ausfüllen (außer die Passwörter)
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
	<title>ADIN - Benutzer ändern</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	
</head>
	
<body>

<?php
    $user_logged_in = isset($_SESSION["user"]);

    if ($user_logged_in) {
        //Der Benutzer ist angemeldet. Es müssen die Rechte überprüft werden

        $user_to_edit_id = intval($_GET["id"]);

        if (current_user_has_rights_for_user("update", $user_to_edit_id)) {
            //Der angemeldete Benutzer hat die Rechte, um den Benutzer zu bearbeiten

            //Auslesen der Daten für den zu bearbeitenden Benutzer aus der Datenbank
            $prep_stmt = $conn->prepare("SELECT FullName, Username, Email, UserType FROM Admins_tbl WHERE AdminId = ?;");
            $prep_stmt->bind_param("i", $user_to_edit_id);
            $prep_stmt->execute();
            $res = $prep_stmt->get_result();
            $prep_stmt->close();

            if ($res->num_rows == 1) {
                //Wenn das Ergebnis nicht genau eine Zeile hat, ist etwas falsch gelaufen

                $res_array = $res->fetch_assoc();

                $full_name = $res_array["FullName"];
                $user_name = $res_array["Username"];
                $email = $res_array["Email"];
                $user_type = $res_array["UserType"];
                ?>

                <div class="container-fluid mt-3">
                    <h3>Benutzerdaten ändern</h3>

                         Ausnahme: Die Passwort-Eingabefelder werden nicht ausgefüllt! -->
                    <form method="POST" action="index.php">
                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Voller Name</span>
                            </div>
                            <input type="text" class="form-control" name="fullname" value="<?php echo $full_name; ?>">
                        </div>

                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Benutzername</span>
                            </div>
                            <input type="text" class="form-control" name="username" value="<?php echo $user_name; ?>">
                        </div>

                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">E-Mail-Adresse</span>
                            </div>
                            <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                        </div>

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

                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Benutzertyp</span>
                            </div>
                            <select class="custom-select" name="usertype">
                                <option value="deladmin" <?php if ($user_type == "deladmin") echo "selected"; ?>>Delegated Admin</option>

                                <!-- Nur Superuser können die Option "Superuser" sehen! -->
                                <?php if ($_SESSION["usertype"] == "superuser"): ?>
                                    <option value="superuser" <?php if ($user_type == "superuser") echo "selected"; ?>>Superuser</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <input type="hidden" name="userid" value="<?php echo $user_to_edit_id; ?>">

                        <input type="submit" class="btn adin-button" name="update" value="Änderungen speichern">
                        <a class="btn btn-danger" href="../users/">Änderungen verwerfen</a>
                    </form>
                </div>

                <?php
            } elseif ($res->num_rows == 0) {
                //Es ist kein Benutzer mit dieser ID vorhanden
                ?>

                <div class="container-fluid mt-3">
                    <h3 class="mb-3">Benutzer nicht vorhanden</h3>

                    <span class="mb-3">
                        Der Benutzer mit der ID <?php echo $user_to_edit_id; ?> existiert nicht.
                    </span>
                </div>

                <?php
            } elseif ($res->num_rows > 1) {
                //Mehr als eine Ergebniszeile darf nicht herauskommen!
                ?>

                <div class="container-fluid mt-3">
                    <h3 class="mb-3">Fehler</h3>

                    <span class="mb-3">
                        Beim Abfragen der Benutzer-Daten aus der Datenbank ist ein Fehler aufgetreten.
                    </span>
                </div>

                <?php
            }
        } else {
            //Der Benutzer hat keine Rechte
            ?>

            <div class="container-fluid mt-3">
                <h3 class="mb-3">Keine Berechtigung</h3>

                <span class="mb-3">
                    Da Sie kein Superuser sind, können Sie keine Benutzer-Accounts für ADIN bearbeiten. Bitte wenden Sie sich dazu an
                    <a href="mailto:bla@wtf.com">Email</a>. <!-- TODO: Kontakt-Adresse hinzufügen -->
                </span>
            </div>

            <?php
        }
    } else {
        //Nicht eingeloggt
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
