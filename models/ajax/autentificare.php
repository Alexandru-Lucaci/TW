<?php
    include "../../config.php";
    include "../../utils/db.php";


    $comandaSQL = "SELECT NUME_UTILIZATOR from utilizatori";
    $statement = Database::getConn()->prepare($comandaSQL);


    $statement ->execute();
    $rezultat = $statement->fetchAll();
    // var_dump($rezultat);
    $valori= array();
    foreach($rezultat as $value){
    
        $valori[] = $value["NUME_UTILIZATOR"];
        
    }
    // var_dump($valori);
    // echo $rezultat[0]["DENUMIRE_POPULARA"];
    $q = $_REQUEST['q'];
    
    // echo $q;
    $sugestie = "";
    if($q != "")
    {

        // $q      = strtolower($q);
        $len    = strlen($q);
        // $sugestie = "Sugestie: ";

        foreach($valori as $valoare){

            if($q == $valoare){
                
                $sugestie = '';
                break;
            }
            else{
                $sugestie ='numele nu este in BD';
            }
        }
    }
    echo $sugestie;

?>