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
	<title>ADIN - Benutzer hinzufügen</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	
</head>
	
<body>

<?php
//Es wird geprüft, ob der Benutzer eingeloggt ist
$user_logged_in = isset($_SESSION["user"]); //true wenn Benutzer eingeloggt

if ($user_logged_in) {
    //Der Benutzer ist angemeldet

    if (current_user_has_rights_for_user("new", -1)) {
        //Nur Superuser können neue Benutzer-Accounts für ADIN anlegen
        ?>

        <div class="container-fluid mt-3">
            <h3>Neuen Benutzer hinzufügen</h3>

            <form method="POST" action="index.php">
                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Voller Name</span>
                    </div>
                    <input type="text" class="form-control" name="fullname">
                </div>

                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Benutzername</span>
                    </div>
                    <input type="text" class="form-control" name="username">
                </div>

                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">E-Mail-Adresse</span>
                    </div>
                    <input type="text" class="form-control" name="email">
                </div>

                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Passwort</span>
                    </div>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Passwort wiederholen</span>
                    </div>
                    <input type="password" class="form-control" name="password-repeat">
                </div>

                <div class="input-group mb-3 col-lg-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Benutzertyp</span>
                    </div>
                    <select class="custom-select" name="usertype">
                        <option value="deladmin">Delegated Admin</option>
                        <option value="superuser">Superuser</option>
                    </select>
                </div>

                <input type="submit" class="btn adin-button" name="insert" value="Benutzer hinzufügen">
                <a class="btn btn-danger" href="../users/">Abbrechen</a>
            </form>
        </div>

        <?php
    } else {
        //Der Benutzer hat nicht die nötigen Rechte
        ?>

        <div class="container-fluid mt-3">
            <h3 class="mb-3">Keine Berechtigung</h3>

            <span class="mb-3">
                Da Sie kein Superuser sind, können Sie keinen neuen Benutzer hinzufügen. Bitte wenden Sie sich dazu an
                <a href="mailto:bla@wtf.com">Email</a>. <!-- TODO: Kontakt-Adresse hinzufügen -->
            </span>
        </div>

        <?php
    }
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
