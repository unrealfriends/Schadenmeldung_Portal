<?php
require_once 'rolle.inc.php';
class Benutzer
{
    public int $id;
    public Rolle $rolle;
    public string $email;
    public string $passwort;
    public string $vorname;
    public string $nachname;

    function __construct($id, $rolle, $email, 
                         $passwort, $vorname, $nachname)
    {
        $this->id = $id;
        $this->rolle = $rolle;
        $this->email = $email;
        $this->passwort = $passwort;
        $this->vorname = $vorname;
        $this->nachname = $nachname;
    }
}

?>