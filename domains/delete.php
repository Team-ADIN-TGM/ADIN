<!--
TODO:
- Fehlersuche (Zeile 42 - 65)
- Konzept überlegen, wie man mit den PHP-Funktionen, die entweder TRUE oder FALSE returnen, Error-Handling machen kann
- ID aus der URL auslesen
- Überprüfen, ob der Benutzer die Rechte hat, um darauf zuzugreifen
- Text mit Daten ausfüllen (wie ID, Email-Adresse)
-->

<?php
session_start();
include "../connect.php";
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
$user_logged_in = isset($_SESSION["user"]);
$logged_in_username = 3; //TODO: Change to user ID from Session Variable

$user_has_rights = false;
$noerror = true;
$domain_id = intval($_GET["id"]);
$domain_name = "";

//Überprüfen, ob der Benutzer die Rechte hat, die Domain zu löschen
$sql = "SELECT Domains_tbl.DomainId, DomainName FROM Domains_tbl
INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
INNER JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
WHERE Admins_tbl.UserName = ?";

//Erstellen eines Prepared Statements
if ($prep_stmt = $conn->prepare($sql)) {

    $prep_stmt->bind_param("i", $logged_in_usernameg);
    $prep_stmt->execute();
    $prep_stmt->get_result();

    //Prüfen, ob die zu löschende Domain in der Menge der Domains enthalten ist, für die der Benutzer Rechte hat
    while ($row = $res->fetch_assoc()) {
        if ($row["DomainId"] == $domain_id) {
            $user_has_rights = true;
            $domain_name = $row["DomainName"];
        }
    }

    echo "Check for rights finished - result: ".(($user_has_rights) ? "true" : "false");
} else {
    echo "meeeeh";
}

//Wenn der Benutzer angemeldet ist, die Rechte hat und kein Fehler aufgetreten ist
if ($user_logged_in && $user_has_rights && $noerror) {
    ?>

    <div class="container-fluid mt-3">
        <h3 class="mb-3">Domain löschen</h3>

        <!-- TODO: Die Nachricht muss angepasst werden -->
        <span class="mb-3">
			Sind Sie sicher, dass Sie die Domain <?php echo $domain_name ?> mit der ID <?php echo $domain_id ?> löschen wollen?<br>
			Wenn Sie die Domain löschen, werden auch alle zugehörigen Mailboxen und Verteiler gelöscht.
		</span>

        <!-- TODO: Links müssen logischerweise noch angepasst werden -->
        <div class="mt-3">
            <form method="post" action="index.php" style="display: inline;">
                <input type="hidden" name="domainid" value="1"><!-- TODO: Wert ist immer die ID der zu löschenden Domain -->
                <input type="submit" class="btn btn-danger" name="delete" value="Ja, löschen">
            </form>
            <a class="btn adin-button" href="index.php">Nein, nicht löschen</a>
        </div>
    </div>

    <?php
} else {
    ?>

    <!-- TODO: Unterscheidung zwischen nicht angemeldet, keine Rechte und sonstigen Fehlern -->
    ES IST EIN FEHLER AUFGETRETEN!!!11!1!!1!11!

    <?php
}
?>
</body>
