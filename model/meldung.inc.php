<?php
// Importiere die Klasse Kategorie weil eine Meldung
// eine Kategorie enthält.
require_once 'kategorie.inc.php';
class Meldung
{
    public int $id;
    //public string $kategorieId;
    // Ich ersetze Foreign Keys durch das referenzierte Objekt
    public Kategorie $kategorie;
    public datetime $schadenDatum;
    public string $beschreibung;
    public string $ip;
    public string $browser;
    public int $kundennummer;
    public string $dokumentPfad;
    public string $dokumentDateiname;

    function __construct($id, $kategorie, $schadenDatum, 
                         $beschreibung, $ip, $browser,
                         $kundennummer, $dokumentPfad,
                         $dokumentDateiname)
    {
        $this->id = $id;
        $this->kategorie = $kategorie;
        $this->schadenDatum = $schadenDatum;
        $this->beschreibung = $beschreibung;
        $this->ip = $ip;
        $this->browser = $browser;
        $this->kundennummer = $kundennummer;
        $this->dokumentPfad = $dokumentPfad;
        $this->dokumentDateiname = $dokumentDateiname;
    }
}

?>