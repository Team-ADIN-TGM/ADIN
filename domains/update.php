<?php
	session_start(); 
	include "../connect.php";
	include "../functions.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>ADIN - Domain ändern</title>
	<meta charset="utf-8">
	
	<!-- Stylesheets -->
	<link type="text/css" rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

</head>
	
<body>
<?php
    $user_logged_in = isset($_SESSION["user"]);
    $domain_id = intval($_GET["id"]);

    if ($user_logged_in) {
        //Der Benutzer ist angemeldet. Es müssen die Rechte überprüft werden

        if (current_user_has_rights_for_domain("edit", $domain_id)) {
            //Der Benutzer hat die Rechte, um die Domain zu bearbeiten

            $prep_stmt = $conn->prepare("SELECT Domains_tbl.DomainId, Domains_tbl.DomainName, Admins_tbl.Username, Admins_tbl.AdminId 
                FROM Domains_tbl
                INNER JOIN Domains_extend_tbl ON Domains_tbl.DomainId = Domains_extend_tbl.DomainId
                LEFT JOIN Admins_tbl ON Domains_extend_tbl.DomainAdmin = Admins_tbl.AdminId
                WHERE Domains_tbl.DomainId = ?;");
            $prep_stmt->bind_param("i", $domain_id);
            $prep_stmt->execute();
            $res = $prep_stmt->get_result();
            $prep_stmt->close();

            if ($res->num_rows == 1) {
                //Wenn das Ergebnis nicht genau eine Zeile hat, ist etwas falsch gelaufen

                $res_array = $res->fetch_assoc();

                $domain_name = $res_array["DomainName"];
                $domain_admin_id = $res_array["AdminId"];
                $domain_admin_name = $res_array["Username"];
                ?>

                <div class="container-fluid mt-3">
                    <h3>Domain ändern</h3>

                    <form method="POST" action="index.php">
                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Domain-Name</span>
                            </div>
                            <input type="text" class="form-control" name="domainname" value="<?php echo $domain_name ?>">
                        </div>

                        <div class="input-group mb-3 col-lg-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Domain-Admin</span>
                            </div>
                            <select class="custom-select" name="domainadmin">
                                <?php
                                    //Alle verfügbaren Domain-Admins werden aus der Datenbank ausgelesen
                                    $res = $conn->query("SELECT AdminId, Username FROM Admins_tbl;");

                                    if ($res) {
                                        while ($row = $res->fetch_assoc()) {
                                            $is_domain_admin = ($row["Username"] == $domain_admin_name);
                                            ?>

                                            <option value="<?php echo $row["AdminId"]; ?>" <?php if ($is_domain_admin) echo "selected" ?>>
                                                <?php echo $row["Username"]; ?>
                                            </option>

                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="domainid" value="<?php echo $domain_id ?>">
                        </div>

                        <input type="submit" class="btn adin-button" name="update" value="Änderungen speichern">
                        <a class="btn btn-danger" href="../domains/">Änderungen verwerfen</a>
                    </form>
                </div>

                <?php
            } elseif ($res->num_rows == 0) {
                ?>

                <div class="container-fluid mt-3">
                    <h3 class="mb-3">Domain nicht vorhanden</h3>

                    <span class="mb-3">
                        Die Domain mit der ID <?php echo $domain_id ?> existiert nicht.
                    </span>
                </div>

                <?php
            } elseif ($res->num_rows > 1) {
                ?>

                <div class="container-fluid mt-3">
                    <h3 class="mb-3">Fehler</h3>

                    <span class="mb-3">
                        Beim Abfragen der Domain-Daten aus der Datenbank ist ein Fehler aufgetreten.
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
                    Da Sie kein Superuser sind, können Sie keine neue Domain hinzufügen. Bitte wenden Sie sich dazu an
                    <a href="mailto:bla@wtf.com">Email</a>. <!-- TODO: Kontakt-Adresse hinzufügen -->
                </span>
            </div>

            <?php
        }
    } else {
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
