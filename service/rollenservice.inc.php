<?php
require_once __DIR__ . '/../model/rolle.inc.php';
class RollenService 
{
    public PDO $con;
    function __construct(PDO $con)
    {
        $this->con = $con;
    }

    function getRolleById(string $id){
        $ps = $this->con->prepare('
            SELECT * 
            FROM rolle 
            WHERE id = :id');
        $ps->bindValue('id', $id);
        $ps->execute();
        if($row = $ps->fetch()){
            // Erstelle ein Objekt der Klasse Rolle, und gebe es gleich zurück
            return new Rolle($row['id'], $row['name']);
        }
        // wurde zuvor keine Rolle gefunden, einfach FALSE zurückgeben 
        return FALSE;
    }
}
?>