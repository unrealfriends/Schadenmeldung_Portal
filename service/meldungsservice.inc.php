<?php
require_once __DIR__ . '/../model/meldung.inc.php';
class MeldungsService
{
    public PDO $con;
    function __construct(PDO $con)
    {
        $this->con = $con;
    }

    /**
     * Erzeugt eine neue Meldung in der Datenbank.
     * Gibt die ID der erzeugten Meldung zurück. 
     */
    function createMeldung(Meldung $meldung){
        $ps = $this->con->prepare('
            INSERT INTO meldung 
            (kategorie_id, schaden_datum, beschreibung, ip, browser, kundennummer, dokument_pfad, dokument_dateiname) 
            VALUES 
            (:kategorieId, :schadenDatum, :beschreibung, :ip, :browser, :kundennummer, :pfad, :dateiname) ');
        // Namend Parameter durch die eigentlichen Werte ersetzen
        $ps->bindValue('kategorieId', $meldung->kategorie->id);
        // PHP datetime in SQL DATE umwandeln
        $ps->bindValue('schadenDatum', $meldung->schadenDatum->format('Y-m-d'));
        $ps->bindValue('beschreibung', $meldung->beschreibung);
        $ps->bindValue('ip', $meldung->ip);
        $ps->bindValue('browser', $meldung->browser);
        $ps->bindValue('kundennummer', $meldung->kundennummer);
        $ps->bindValue('pfad', $meldung->dokumentPfad);
        $ps->bindValue('dateiname', $meldung->dokumentDateiname);

        // SQL Statement an die Datenbank senden
        $ps->execute();

        // Welche ID hat der neue Datensatz? --> welche ID wurde von der Datenbank erzeugt?
        $id = $this->con->lastInsertId();

        // Die generierte ID zurückgeben
        return $id; 
    }

    //function createMeldung($kundennunner, $kategorieId, $schadenDatum)
}
?>