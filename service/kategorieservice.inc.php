<?php
require_once __DIR__ . '/../model/kategorie.inc.php';
class KategorieService
{
    // Hinterlege die DB-Connection direkt im Service, 
    // damit wir in den functions gleich darauf zugreifen können!
    public PDO $con;

    // DB-Connection beim Erzeugen der Klasse übergeben
    function __construct($con)
    {
        $this->con = $con;
    }

    /**
     * Holt alle Kategorien aus der Datenbank und gibt 
     * sie als Liste von Kategorien zurück
     */
    function getKategorien() : array {
        $ps = $this->con->prepare('SELECT * 
                                   FROM kategorie ');
        // Sende SQL Statement an die Datenbank
        $ps->execute();

        // Leere Liste von Kategorien anlegen (hier kommen alle gefundenen Kategorien rein)
        $kategorien = [];

        // Über das Ergebnis des SQL-Statements iterieren
        // fetch() geht zum nächsten gefundenen Datensatz
        // ich speichere mir den Datensatz den ich mir gerade ansehen auf der Variable $row
        while($row = $ps->fetch()){
            
            // Erstelle für jeden gefundenen Datensatz ein Objekt der Klasse Kategorie
            $k = new Kategorie($row['id'], $row['name']);

            // füge das neue Objekt in die Liste ein
            $kategorien[] = $k; 
        }

        // Gebe die Liste von Kategorien zurück
        return $kategorien;
    }

    /**
     * Sucht eine Kategorie anhand der übergebenen ID
     * Liefert die Kategorie zurück, ansonsten FALSE wenn es keine
     * Kategorie mit dieser ID gibt. 
     */
    function getKategorieById(string $id) {
        $ps = $this->con->prepare('
            SELECT * 
            FROM kategorie 
            WHERE id = :id');
        // ersetze named-parameter durch den eigentlichen Wert
        $ps->bindValue('id', $id);
        // sende SQL Statement an die Datenbank
        $ps->execute();

        // wurde ein Datensatz gefunden? Wenn ja, auf $row speichern
        if($row = $ps->fetch()){
            $k = new Kategorie($row['id'], $row['name']);
            // beende die function und gebe die Kategorie zurück 
            return $k; 
        }
        // false zurückgeben wenn kein Datensatz gefunden wurde
        return FALSE;
    }
}
?>