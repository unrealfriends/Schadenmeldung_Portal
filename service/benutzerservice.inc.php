<?php
require_once __DIR__ . '/../model/benutzer.inc.php';
class BenutzerService
{
    public PDO $con;
    function __construct(PDO $con)
    {
        $this->con = $con; 
    }

    /**
     * Legt einen neuen Benutzer in der Datenbank an
     * Gibt die ID des neuen Benutzers zurück.
     * @param $benutzer der zu erzeugende Benutzer
     */
    function createBenutzer(Benutzer $benutzer) {
        // Passwort hashen
        $passwordHash = password_hash($benutzer->passwort, PASSWORD_DEFAULT);
        // NIEMALS DAS PASSWORT IM KLARTEXT IN DER DATENBANK SPEICHERN!!

        $ps = $this->con->prepare('
                INSERT INTO benutzer 
                (rolle_id, email, passwort, vorname, nachname) 
                VALUES 
                (:rolleId, :email, :passwort, :vorname, :nachname) ');
        $ps->bindValue('rolleId', $benutzer->rolle->id);
        $ps->bindValue('email', $benutzer->email);
        // Achtung! Passwort-Hash eintragen!
        $ps->bindValue('passwort', $passwordHash);
        $ps->bindValue('vorname', $benutzer->vorname);
        $ps->bindValue('nachname', $benutzer->nachname);

        $ps->execute();

        // Die ID des Benutzers zurücgeben
        return $this->con->lastInsertId();
    }

    /**
     * Lädt Benutzer anhand der E-Mail Adresse.
     * Gibt Benutzer zurück, falls es keinen Benutzer
     * mit der E-Mail gibt wird FALSE zurückgegeben. 
     */
    function getBenutzerByEmail($email) {
        $ps = $this->con->prepare('
            SELECT *, b.id AS benutzerId, r.id AS rollenId  
            FROM benutzer b 
            INNER JOIN rolle r ON(b.rolle_id = r.id) 
            WHERE email = :email');
        $ps->bindValue('email', $email);
        $ps->execute();
        if($row = $ps->fetch()){
            // Zuerst eine Rolle erstellen, weil Benutzer eine Rolle benötigt
            $rolle = new Rolle($row['rollenId'], $row['name']);

            $benutzer = new Benutzer($row['benutzerId'], $rolle, 
                                    $row['email'], $row['passwort'], 
                                    $row['vorname'], $row['nachname']);
            return $benutzer;
        }
        return FALSE;
    }


    /**
     * Prüft ob es einen Benutzer mit der Email gibt
     * und prüft auch ob das Passwort korrekt ist. Falls ja,
     * Benutzer in der Session als logged_in = true setzen. 
     * 
     * Gibt Benutzer zurück wenn login erfolgreich, ansonsten false. 
     */
    function login($email, $passwort){
        // Gibt es einen Benutzer mit der E-Mail Adresse?
        $benutzer = $this->getBenutzerByEmail($email);
        if($benutzer === FALSE){
            return FALSE; 
        }

        // Ist das Passwort korrekt?
        if(password_verify($passwort, $benutzer->passwort)){
            // YAY! Login durchführen ... 
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $benutzer->id;

            return $benutzer;
        }
        return FALSE;
    }

    /**
     * Gibt TRUE zurück wenn Benutzer angemeldet ist, 
     * ansonsten false.
     */
    function isLoggedIn(){
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
            return true;
        } else {
            return false; 
        }
    }
}
?>