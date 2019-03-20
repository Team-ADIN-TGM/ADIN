<?php
/**
 * Diese Datei stellt Funktionen zur Verfügung, die zum Beispiel zum Überprüfen von Berechtigungen
 * eingesetzt werden können.
 */

session_start();
require "connect.php";

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
 * @return          boolean true, wenn der Benutzer die Rechte hat, sonst false
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
 * @return          boolean true, wenn der Benutzer die Rechte hat, sonst false
 */
function current_user_has_rights_for_domain($action, $domainid) {
    return (isset($_SESSION["userid"]) && ($_SESSION["usertype"] == "superuser"));
}


/****************************************************************************
 *                                 BENUTZER                                 *
 ****************************************************************************/

/**
 * Überprüft, ob der Benutzer die Rechte hat, um auf einen Benutzeraccount zuzugreifen.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser
 * - Aktualisieren: Superuser und der Benutzer selbst (auch, wenn er nur Delegated Admin ist)
 * - Löschen: Superuser
 * @param $action           string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                                  (entsprechend den Seitennamen)
 * @param $logged_in_user   int     ID des Benutzers (aus der Tabelle Admins_tbl aus der Datenbank), der aktuell
 *                                  angemeldet ist.
 * @param $subject_user     int     ID des Benutzers (aus der Tabelle Admins_tbl aus der Datenbank), auf den zugegriffen
 *                                  werden soll.
 * @return                  boolean true, wenn der Benutzer die Rechte hat, sonst false
 */
function user_has_rights_for_user($action, $logged_in_user, $subject_user) {
    $is_superuser = false;
    $res = $conn->query("SELECT UserType FROM Admins_tbl WHERE AdminId = $logged_in_user;");

    if ($res->num_rows == 1) {
        if ($res->fetch_assoc()["UserType"] == "superuser")
            $is_superuser = true;
    }

    if ($action == "new") {
        return $is_superuser;
    } elseif ($action == "update") {
        return ($is_superuser || ($logged_in_user == $subject_user));
    } elseif ($action == "delete") {
        return $is_superuser;
    } else return false;
}

/**
 * Überprüft, ob der aktuell angemeldete Benutzer die Rechte hat, um auf einen Benutzer zuzugreifen.
 * Das Auslesen des angemeldeten Benutzers funktioniert über die $_SESSION-Variable.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser
 * - Aktualisieren: Superuser und der Benutzer selbst (auch, wenn er nur Delegated Admin ist)
 * - Löschen: Superuser
 * @param $action           string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                                  (entsprechend den Seitennamen)
 * @param $subject_user     int     ID des Benutzers (aus der Tabelle Admins_tbl aus der Datenbank), auf den zugegriffen
 *                                  werden soll.
 * @return                  boolean true, wenn der Benutzer die Rechte hat, sonst false
 */
function current_user_has_rights_for_user($action, $subject_user) {
    if (isset($_SESSION["userid"]))
        return user_has_rights_for_user($action, $_SESSION["userid"], $subject_user);
    else return false;
}


/****************************************************************************
 *                                MAILBOXEN                                 *
 ****************************************************************************/

/**
 * Überprüft, ob der Benutzer die Rechte hat, um auf eine Mailbox zuzugreifen.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser und der Domain-Admin
 * - Aktualisieren: Superuser und der Domain-Admin
 * - Löschen: Superuser und der Domain-Admin
 * @param $action   string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                          (entsprechend den Seitennamen)
 * @param $user     int     ID des Benutzers (aus der Tabelle Admins_tbl aus der Datenbank), der aktuell angemeldet ist.
 * @param $mailbox  int     ID der Mailbox (aus der Tabelle Users_tbl aus der Datenbank), auf die zugegriffen werden
 *                          soll.
 * @return          boolean true, wenn der Benutzer die Rechte hat, sonst false
 */
function user_has_rights_for_mailbox($action, $user, $mailbox) {
    //Überprüfung, ob der Benutzer Superuser ist
    $is_superuser = false;
    $res = $conn->query("SELECT UserType FROM Admins_tbl WHERE AdminId = $user;");
    if ($res->num_rows == 1) {
        if ($res->fetch_assoc()["UserType"] == "superuser")
            $is_superuser = true;
    }

    //Auslesen des Domain-Admins für die Domain, zu der die Mailbox gehört
    $res = $conn->query("SELECT UserId, Domains_extend_tbl.DomainAdmin 
                        FROM Users_tbl INNER JOIN Domains_extend_tbl 
                        ON Users_tbl.DomainId = Domains_extend_tbl.DomainId
                        WHERE UserId = $mailbox;");
    if ($res->num_rows == 1)
        $domain_admin = intval($res->fetch_assoc()["DomainAdmin"]);
    else $domain_admin = -1;

    return $is_superuser || ($domain_admin == $user);
}

/**
 * Überprüft, ob der aktuell angemeldete Benutzer die Rechte hat, um auf eine Mailbox zuzugreifen.
 * Das Auslesen des angemeldeten Benutzers funktioniert über die $_SESSION-Variable.
 * Folgende Regeln gelten:
 * - Neu anlegen: Superuser und der Domain-Admin
 * - Aktualisieren: Superuser und der Domain-Admin
 * - Löschen: Superuser und der Domain-Admin
 * @param $action   string  "new" für neu anlegen, "update" für aktualisieren und "delete" für löschen
 *                          (entsprechend den Seitennamen)
 * @param $mailbox  int     ID der Mailbox (aus der Tabelle Users_tbl aus der Datenbank), auf die zugegriffen werden
 *                          soll.
 * @return          boolean true, wenn der Benutzer die Rechte hat, sonst false
 */
function current_user_has_rights_for_mailbox($action, $mailbox) {
    if (isset($_SESSION["userid"]))
        return user_has_rights_for_user($action, $_SESSION["userid"], $mailbox);
    else return false;
}