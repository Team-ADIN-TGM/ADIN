<?php
/**
     * Diese Datei stellt Funktionen zur Verfügung, die zum Beispiel zum Überprüfen von Berechtigungen
     * eingesetzt werden können.
     */

session_start();
include "connect.php";

/****************************************************************************
 *                                ALLGEMEIN                                 *
 ****************************************************************************/


/****************************************************************************
 *                                  DOMAINS                                 *
 ****************************************************************************/

/**
 * Überprüft, ob der Benutzer die Rechte hat, um auf eine Domain zuzugreifen.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser
 * - Aktualisieren: Superuser
 * - Löschen: Superuser
 * @param $action   string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                          (entsprechend den Seitennamen)
 * @param $userid   int     Die ID des Benutzers (aus der Tabelle Admins_tbl aus der Datenbank)
 * @param $domainid int     Die ID der Domain (aus der Tabelle Domains_tbl aus der Datenbank)
 * @return true, wenn der Benutzer die Rechte hat, sonst false
 */
function user_has_rights_for_domain($action, $userid, $domainid) {
    $res = $conn->query("SELECT UserType FROM Admins_tbl WHERE AdminId = $userid;");
    $has_rights = false;

    if ($res->num_rows == 1) {
        if ($res->fetch_assoc()["UserType"] == "superuser") {
            $has_rights = true;
        }
    }

    return $has_rights;
}

/**
 * Überprüft, ob der aktuell angemeldete Benutzer die Rechte hat, um auf eine Domain zuzugreifen.
 * Das Auslesen des angemeldeten Benutzers funktioniert über die $_SESSION-Variable.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser
 * - Aktualisieren: Superuser
 * - Löschen: Superuser
 * @param $action   string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                          (entsprechend den Seitennamen)
 * @param $domainid int     ID der Domain (aus der Tabelle Domains_tbl aus der Datenbank)
 * @return true, wenn der Benutzer die Rechte hat, sonst false
 */
function current_user_has_rights_for_domain($action, $domainid) {
    return (isset($_SESSION["userid"]) && ($_SESSION["usertype"] == "superuser"));
}