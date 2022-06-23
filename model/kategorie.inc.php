<?php

/**
 * Die Klasse ist ein Wrapper für die Informationen
 * von genau einer Kategorie
 * --> Klasse fasst die Informationen zusammen
 * 
 * z. B. 
 * 1. Objekt: id: ST, name: Sturmschaden
 * 2. Objekt: id: FE, name: Feuerschaden
 */
class Kategorie
{
    // Klassenvariablen
    public string $id;
    public string $name;

    // Konstruktor
    // wird beim Erzeugen jedes Objekts aufgerufen
    function __construct($id, $name)
    {
        // Parameter den Klassenvariablen zuweisen
        $this->id = $id;
        $this->name = $name;

        // $this->   bezieht sich auf Klassenvariable
    }
}
?>