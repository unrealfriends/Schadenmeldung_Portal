<?php
require_once 'db/dbconnection.inc.php';
require_once 'model/benutzer.inc.php';
require_once 'service/benutzerservice.inc.php';
require_once 'service/rollenservice.inc.php';

$con = db_connection();

$benutzerService = new BenutzerService($con);
$rollenService = new RollenService($con);

// Sollte es bei der Prüfung des Formulars Fehler geben, werden die Fehlermeldungen in dieser Liste gespeichert
$errors = [];

if(isset($_POST['btregistration'])){
    
    // Welche Rolle soll Benutzer bekommen?
    if(isset($_POST['rolle']) && $_POST['rolle'] == 'ma'){
        // todo: Rolle Mitarbeiter laden
        $rolle = $rollenService->getRolleById('MA');
    } else if(isset($_POST['rolle']) && $_POST['rolle'] == 'admin'){
        // todo: Rolle Administrator laden
        $rolle = $rollenService->getRolleById('ADMIN');
    } else {
        $errors[] = 'Rolle wählen!';
    }

    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];

    // Prüfen ob tatsächlich Werte eingegeben wurden
    if(strlen(trim($email)) == 0){
        $errors[] = 'E-Mail eingeben!';
    }
    if(strlen(trim($passwort)) < 6){
        $errors[] = 'Passwort benötigt mindestens 6 Zeichen!';
    }
    if(strlen(trim($vorname)) == 0){
        $errors[] = 'Vorname eingeben!';
    }
    if(strlen(trim($nachname)) == 0){
        $errors[] = 'Nachname eingeben!';
    }

    // Ich möchte die Registrierung nur dann durchführen wenn es keine Fehler gab...
    if(count($errors) == 0){
        // Es gab keine Fehler, Registrierung durchführen!
        $benutzer = new Benutzer(0, $rolle, $email, $passwort, $vorname, $nachname);

        $benutzerId = $benutzerService->createBenutzer($benutzer);

        header('Location: ./login.php?user=created&userid='.$benutzerid);
        return;
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Registrierung</title>
</head>
<body>

<h1>Registrierung</h1>

<?php
// Ausgabe möglicher Fehlermeldungen
if(count($errors) > 0){
    echo '<ul>';
    for($i = 0; $i < count($errors); $i++){
        echo '<li>' . $errors[$i] . '</li>';
    }
    echo '</ul>';
}
?>

<form action="registrierung.php" method="POST">
    <input type="radio" name="rolle" id="rolle-ma" value="ma">
    <label for="rolle-ma">Mitarbeiter</label><br>

    <input type="radio" name="rolle" id="rolle-admin" value="admin">
    <label for="rolle-admin">Administrator</label><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" value="<?php if(isset($email)){echo htmlspecialchars($email);} ?>"><br>

    <label for="passwort">Passwort:</label><br>
    <input type="password" name="passwort" id="passwort" value="<?php if(isset($passwort)){echo htmlspecialchars($passwort);} ?>"><br>

    <label for="vorname">Vorname:</label><br>
    <input type="text" name="vorname" id="vorname" value="<?php if(isset($vorname)){echo htmlspecialchars($vorname);} ?>"><br>

    <label for="nachname">Nachname:</label><br>
    <input type="text" name="nachname" id="nachname" value="<?php if(isset($nachname)){echo htmlspecialchars($nachname);} ?>"><br>

    <button name="btregistration">Registrieren</button>
</form>

</body>
</html>