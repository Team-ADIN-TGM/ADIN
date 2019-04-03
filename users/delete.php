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
	<title>ADIN - Benutzer löschen</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
	
<body>

<?php

//Es wird geprüft, ob der Benutzer eingeloggt ist
$user_logged_in = isset($_SESSION["user"]); //true wenn Benutzer eingeloggt

if ($user_logged_in) {
    //Jetzt werden die Rechte geprüft

    $logged_in_user = $_SESSION["userid"];
    $user_to_delete = intval($_GET["id"]); //Die ID des zu löschenden Benutzeraccounts, übergeben per URL (delete.php?id=3)
    $user_has_rights = current_user_has_rights_for_user("delete", $user_to_delete);

    if ($logged_in_user == $user_to_delete) {

        if ($user_has_rights) {
            //Der angemeldete Benutzer hat die Rechte, um den zu löschenden Benutzer-Account zu löschen.
            //Es müssen noch Daten über den zu löschenden Benutzer ausgelesen werden
            $prep_stmt = $conn->prepare("SELECT FullName, Username, UserType FROM Admins_tbl WHERE AdminId = ?;");
            $prep_stmt->bind_param("i", $user_to_delete);
            $prep_stmt->execute();
            $res = $prep_stmt->get_result();
            $prep_stmt->close();

            if ($res->num_rows == 1) {
                $res_array = $res->fetch_assoc();
                $user_to_delete_full_name = $res_array["FullName"];
                $user_to_delete_username = $res_array["Username"];
                $user_to_delete_user_type = $res_array["UserType"];

                //Es müssen auch noch alle Domains ausgelesen werden, von denen der zu löschende Benutzer Domain-Admin ist
                $prep_stmt = $conn->prepare("SELECT GROUP_CONCAT(DISTINCT DomainName ORDER BY DomainName ASC SEPARATOR '</li><li>') AS Domains
                    FROM Domains_tbl
                    INNER JOIN Domains_extend_tbl
                    ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                    WHERE DomainAdmin = ?;");
                $prep_stmt->bind_param("i", $user_to_delete);
                $prep_stmt->execute();
                $res = $prep_stmt->get_result();

                $user_to_delete_domains = $res->fetch_assoc()["Domains"];

                ?>

                <div class="container-fluid mt-3">
                    <h3 class="mb-3">Benutzer löschen</h3>

                    <span class="mb-3">
                        Sind Sie sicher, dass sie den Benutzer <?php echo $user_to_delete_full_name; ?>
                        (<?php echo $user_to_delete_username; ?>) löschen wollen?<br>
                        Dieser Benutzer ist ein <?php echo(($user_to_delete_user_type == "deladmin") ? "Delegated Admin" : "Superuser") ?>
                        .

                        <?php if (!empty($user_to_delete_domains)):
                            //Wird nur angezeigt, wenn der Benutzer Domain-Admin von mindestens einer Domain ist
                            ?>
                            Wenn Sie ihn löschen, haben die folgenden Domains keinen Domain-Admin mehr:
                            <ul>
                                <li>
                                    <?php echo $user_to_delete_domains ?>
                                </li>
                            </ul>
                            Superuser können den Domains allerdings einen neuen Domain-Admin zuweisen.
                        <?php endif; ?>
                    </span>

                    <div class="mt-3">
                        <form method="post" action="index.php" style="display: inline;">
                            <input type="hidden" name="userid" value="<?php echo $user_to_delete; ?>">
                            <input type="submit" class="btn btn-danger" name="delete" value="Ja, löschen">
                        </form>
                        <a class="btn adin-button" href="index.php">Nein, nicht löschen</a>
                    </div>
                </div>

                <?php
            }
        } else {
            //Der Benutzer hat keine Berechtigung
            ?>

            <div class="container-fluid mt-3">
                <h3 class="mb-3">Keine Berechtigung</h3>

                <span class="mb-3">
                    Da Sie kein Superuser sind, können Sie keine Benutzer-Accounts für ADIN löschen. Bitte wenden Sie sich dazu an
                    <a href="mailto:bla@wtf.com">Email</a>. <!-- TODO: Kontakt-Adresse hinzufügen -->
                </span>
            </div>

            <?php
        }
    } else {
        //Der Benutzer möchte sich selbst löschen - das geht nicht!
        ?>

        <div class="container-fluid mt-3">
            <h3 class="mb-3">Sie können sich nicht selbst löschen.</h3>

            <span class="mb-3">
                Sie versuchen, den Account zu löschen, mit dem Sie gerade angemeldet sind. Das ist nicht möglich. Bitte
                melden Sie sich mit einem anderen Account an, um diesen Account zu löschen.
            </span>
        </div>

        <?php
    }
} else {
    //Der Benutzer ist nicht eingeloggt
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