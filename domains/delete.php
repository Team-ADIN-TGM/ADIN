<!--
TODO:
- Konzept überlegen, wie man mit den PHP-Funktionen, die entweder TRUE oder FALSE returnen, Error-Handling machen kann
-->

<?php
    //TODO: Remove, just for debugging
    // Turn on error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);

    session_start();
    include "../connect.php";
    include "../functions.php";

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>ADIN - Domain löschen</title>
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
    //Jetzt werden die Rechte geprüft

    $logged_in_user = $_SESSION["userid"];
    $domain_id = intval($_GET["id"]); //Die ID der zu löschenden Domain, übergeben per URL (delete.php?id=3)
    $user_has_rights = current_user_has_rights_for_domain($domain_id, "delete");

    if ($user_has_rights) {
        //Der Domain-Name muss noch aus der Datenbank ausgelesen werden
        $res = $conn->query("SELECT DomainName FROM Domains_tbl WHERE DomainId = $domain_id;");
        if ($res->num_rows == 1) {
            $domain_name = $res->fetch_assoc()["DomainName"];

            //Der Benutzer hat die Rechte - die Domain kann gelöscht werden
            ?>

            <div class="container-fluid mt-3">
                <h3 class="mb-3">Domain löschen</h3>

                <span class="mb-3">
                Sind Sie sicher, dass Sie die Domain <?php echo $domain_name ?> mit der ID <?php echo $domain_id ?>
                    löschen wollen?<br>
                <b>ACHTUNG</b> - Wenn Sie die Domain löschen, werden auch alle zugehörigen Mailboxen und Verteiler gelöscht.
                </span>

                <div class="mt-3">
                    <form method="post" action="../domains/index.php" style="display: inline;">
                        <input type="hidden" name="domainid" value="<?php echo $domain_id ?>">
                        <input type="submit" class="btn btn-danger" name="delete" value="Ja, löschen">
                    </form>
                    <a class="btn adin-button" href="index.php">Nein, nicht löschen</a>
                </div>
            </div>

            <?php
        }
    } else {
        ?>

        <div class="container-fluid mt-3">
            <h3 class="mb-3">Keine Berechtigung</h3>

            <span class="mb-3">
                Da Sie kein Superuser sind, können Sie keine Domains löschen. Bitte wenden Sie sich dazu an
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
