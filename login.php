<?php
// Die Session sollte immer als erstes gestartet werden!
session_start();
require_once 'db/dbconnection.inc.php';
require_once 'service/benutzerservice.inc.php';

$con = db_connection();
$benutzerService = new BenutzerService($con);

$errors = [];

if(isset($_POST['btlogin'])){
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    // todo: wurden email / passwort eingegeben? Fehlermeldungen, ... 

    $benutzer = $benutzerService->login($email, $passwort);
    if($benutzer !== FALSE){
        // Login erfolgreich!

        if($benutzer->rolle->id == 'MA'){
            header('Location: ./mitarbeiter.php');
            return;
        } else if($benutzer->rolle->id == 'ADMIN'){
            header('Location: ./admin.php');
            return;
        }
    } else {
        $errors[] = 'Email / Passwort ungültig';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Login</title>
</head>
<body>
    
<h1>Login</h1>

<form action="login.php" method="POST">
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email"><br>

    <label for="passwort">Passwort:</label><br>
    <input type="password" name="passwort" id="passwort"><br>

    <button name="btlogin">Login</button>
</form>

</body>
</html>