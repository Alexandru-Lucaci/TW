<?php
    // include HOME . "utils" . DS . "db.php";W

    // $comandaSQL = "SELECT DENUMIRE_POPULARA FROM animale ";
    // $statement = Database::getConn()->prepare($comandaSQL);
    
    // $statement ->execute();
    // $rezultat = $statement->fetchAll();
    // var_dump($rezultat);
    $q = $_REQUEST['q'];
    echo $q;
    $sugestie = "";
    if($q != "")
    {

        $q      = strtolower($q);
        $len    = strlen($q);
        $sugestie = "Sugestie: ";

        
    }
    return $q;

?>