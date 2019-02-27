<!--
TODO:
- Konzept überlegen, wie man mit den PHP-Funktionen, die entweder TRUE oder FALSE returnen, Error-Handling machen kann
-->

<?php
session_start();
include "../connect.php";

//TODO: Remove, just for debugging // Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>ADIN - Domain löschen</title>
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

<?php

//Es wird geprüft, ob der Benutzer eingeloggt ist
$user_logged_in = isset($_SESSION["user"]); //true wenn Benutzer eingeloggt

if ($user_logged_in) {
    //Jetzt werden die Rechte geprüft

    $logged_in_user = $_SESSION["userid"];
    $user_has_rights = false; //Wird auf true gesetzt, wenn der Benutzer die Rechte hat, um die Domain zu löschen
    $noerror = true; //Wird auf false gesetzt, wenn ein Fehler aufgetreten ist
    $domain_id = intval($_GET["id"]); //Die ID der zu löschenden Domain, übergeben per URL (delete.php?id=3)


    if ($_SESSION["usertype"] == "superuser") {
        //Superuser können alle Domains löschen
        $user_has_rights = true;

        //Der Domain-Name muss noch aus der Datenbank ausgelesen werden
        $res = $conn->query("SELECT DomainName FROM Domains_tbl WHERE DomainId = $domain_id;");
        if ($res->num_rows == 1) {
            $domain_name = $res->fetch_assoc()["DomainName"];
        } else {
            $noerror = true;
        }
    } else {
        /*
         * Delegated Admins können nur die Domains löschen, deren Domain-Admin sie sind. Das muss hier überprüft werden.
         * SQL-Statement zum Überprüfen, ob der Benutzer die Rechte hat, die Domain zu löschen
         * Gibt die Domain-ID und den Domain-Namen aller Domains zurück, die den übergebenen Admin als Domain-Admin haben.
         * Jede Domain kann nur einen Domain-Admin haben
         */
        $sql = "SELECT Domains_tbl.DomainId, DomainName FROM Domains_tbl
        INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
        INNER JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
        WHERE Admins_tbl.AdminId = ? AND Domains_tbl.DomainId = ?";

        //Erstellen eines PreparedStatements und setzen des Parameters
        $prep_stmt = $conn->prepare($sql);
        $prep_stmt->bind_param("ii", $logged_in_user, $domain_id);

        //Ausführen und laden des Ergebnisses
        $prep_stmt->execute();
        $res = $prep_stmt->get_result();

        /*
         * Wenn das Ergebnis eine Zeile hat, ist in der Datenbank der angemeldete Benutzer als Domain-Admin der Domain
         * eingetragen. Er darf daher die Domain löschen.
         */
        if ($res->num_rows == 1) {
            $user_has_rights = true;
            $domain_name = $res->fetch_assoc()["DomainName"];
        } elseif ($res->num_rows > 1) {
            //Darf nicht passieren - hier kommt es zu einem Fehler
            $noerror = false;
        } else {
            //0 Ergebniszeilen bedeutet, dass der Benutzer die Rechte nicht hat
            $user_has_rights = false;
        }
    }

    if ($user_has_rights) {
        if ($noerror) {
            //Der Benutzer hat die Rechte und es ist kein Fehler aufgetreten - die Domain kann gelöscht werden
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
        } else {
            ?>

            <div class="container-fluid mt-3">
                <h3 class="mb-3">Fehler/h3>

                    <span class="mb-3">
                Es ist ein Fehler aufgetreten. Bitte wenden Sie sich an den Webmaster zur Lösung des Problems.<br>
                Die Domain <?php echo $domain_name ?> mit der ID <?php echo $domain_id ?> konnte nicht gelöscht werden.
            </span>
            </div>

            <?php
        }
    } else {
        ?>

        <div class="container-fluid mt-3">
            <h3 class="mb-3">Keine Berechtigung</h3>

            <span class="mb-3">
                Da Sie nicht der Domain-Admin und auch kein Superuser sind, haben Sie nicht die Rechte, die Domain mit
                der ID <?php echo $domain_id ?> zu löschen.
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