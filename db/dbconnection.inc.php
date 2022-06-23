<?php
// Function die eine DB-Connection liefert
function db_connection() : PDO {
    try {
        $host = 'localhost';
        $dbName = 'schadenmeldung090222';
        $dbUser = 'root';
        $dbPassword = '';

        // Baue Verbindung zur Datenbank auf
        $con = new PDO("mysql:host=$host; dbname=$dbName", $dbUser, $dbPassword);
        // Damit alle Fehlermeldungen dargestellt werden
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $con;

    } catch(PDOException $e){
        print('Error!: ' . $e->getMessage());
        die();
    }
}
?>