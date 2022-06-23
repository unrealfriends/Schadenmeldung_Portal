<?php
// Inkludiere Datei mit DB-Connection
// require_once: Inkludiere die Datei nur 1x!
// require: Sollte die Datei nicht existieren 
//          --> Mit einer Fehlermeldung abbrechen
require_once 'db/dbconnection.inc.php';
// Inkludiere die Klasse KategorieService
require_once 'service/kategorieservice.inc.php';
require_once 'model/meldung.inc.php';
require_once 'service/meldungsservice.inc.php';

// $con "hält" die Verbindung zur Datenbank
$con = db_connection();

// Objekt der Klasse KategorieService erzeugen
$kategorieService = new KategorieService($con);
// Objekt der Klasse MeldungsService erzeugen
$meldungsService = new MeldungsService($con);

// Hole alle Kategorien als Liste (Liste von Objekten der Klasse Kategorie)
$kategorien = $kategorieService->getKategorien();


// wurde das Formular für die Schadensmeldung abgeschickt?
// wurde der Button mit name='btsubmit' gedrückt?
if(isset($_POST['btsubmit']))
{
    $kundennummer = $_POST['kundennummer'];
    $kategorieId = $_POST['kategorie'];
    $schadenDatumString = $_POST['schaden_datum'];
    $beschreibung = $_POST['beschreibung'];

    // schadenDatumString in ein datetime-Objekt umwandeln
    // 10.02.2022
    $schadenDatum = DateTime::createFromFormat('d.m.Y', $schadenDatumString);

    // hole die Kategorie (aus dem Formular bekommen wir nur die KategorieID)
    $kategorie = $kategorieService->getKategorieById($kategorieId);
    // todo: prüfen ob hier tatsächlich eine Kategorie gefunden wurde...
    // if($kategorie === FALSE){  Kategorie nicht gefunden ...  }
    
    // IP und Browser
    $ip = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];

    /**
     * File Upload
     * Die Datei selbst wird im Dateisytem des Servers gespeichert (hier im uploads-Ordner)
     * Der Pfad zur Datei, sowie der Name der Datei am Dateisytem wird in der Datenbank zur Meldung gespeichert.
     * Man speichert praktisch NIE die Datei selbst in der Datenbank!
     */
    $originalFileName = $_FILES['dokument']['name'];
    // soll die Datei am Server umbenannt werden?
    $targetFileName = $originalFileName; // an dieser Stelle könnte man neuen eindeutigen Dateinamen generieren lassen
    // in welchen Ordner soll die Datei hochgeladen werden?
    $uploadDirectory = 'uploads\\';
    // gesamter Pfad der hochzuladenden Datei?
    $uploadFile = $uploadDirectory . $targetFileName;
    // speichere die Datei nun als $uploadFile
    if(move_uploaded_file($_FILES['dokument']['tmp_name'], $uploadFile)){
        // upload success!
        //echo 'Upload erfolgreich! ' . $uploadDirectory . $targetFileName;
    } else {
        echo 'Datei konnte nicht hochgeladen werden?!';
    }

    // Objekt der Klasse Meldung erzeugen (aus den eingegebenen Daten)
    $meldung = new Meldung(0, $kategorie, $schadenDatum, 
                        $beschreibung, $ip, $browser, $kundennummer,
                        $uploadDirectory, $targetFileName);

    $meldungId = $meldungsService->createMeldung($meldung);

    // Redirect um POST Request --> GET-Request
    // damit das Formular mit den Daten nicht versehentlich noch einmal abgeschickt werden kann (F5!)
    header('Location: ./index.php?meldung=created&meldungId='.$meldungId);
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Neuer Schaden</title>
</head>
<body>

<h1>Schaden melden</h1>
<form enctype="multipart/form-data" action="index.php" method="POST">
    <label for="kundennummer">Kundennummer:</label><br>
    <input type="text" name="kundennummer" id="kundennummer"><br>

    <label for="kategorie">Kategorie:</label><br>
    <select name="kategorie" id="kategorie">
        <?php  
        for($i = 0; $i < count($kategorien); $i++){
            $kategorie = $kategorien[$i];
            echo '<option value="' . $kategorie->id .'">' . $kategorie->name . ' (' . $kategorie->id . ')' .'</option>';
        }
        ?>
    </select><br>

    <label for="schaden_datum">Schadensdatum (tt.mm.jjjj):</label><br>
    <input type="text" name="schaden_datum" id="schaden_datum"><br>

    <label for="beschreibung">Beschreibung des Schadenfalls:</label><br>
    <textarea name="beschreibung" id="beschreibung" rows="10"></textarea><br>

    <label for="dokument">Datei upload:</label><br>
    <input type="file" name="dokument" id="dokument"><br>

    <button name="btsubmit">Meldung absenden</button>
</form>

<p>
    <a href="registrierung.php">Registrierung: Werden Sie noch heute zum Admin!</a>
</p>

</body>
</html>