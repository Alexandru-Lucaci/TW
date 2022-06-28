<?php
    include "../../config.php";
    include "../../utils/db.php";

    $comandaSQL = "SELECT DENUMIRE_POPULARA FROM animale ";
    $statement = Database::getConn()->prepare($comandaSQL);


    $statement ->execute();
    $rezultat = $statement->fetchAll();
    // var_dump($rezultat);
    $valori= array();
    foreach($rezultat as $value){
     
        $valori[] = $value["DENUMIRE_POPULARA"];
        
    }
    // var_dump($valori);
    // echo $rezultat[0]["DENUMIRE_POPULARA"];
    $q = $_REQUEST['q'];
    // echo $q;
    $sugestie = "";
    if($q != "")
    {

        $q      = strtolower($q);
        $len    = strlen($q);
        $sugestie = "Sugestie: ";

        foreach($valori as $valoare){
            if(stristr($q,substr($valoare,0,$len))){
                if($sugestie =="Sugestie: "){
                    $sugestie .= $valoare;
                }
                else
                {
                    $sugestie .= ", $valoare";
                }
            }
        }
    }
    if($sugestie =="Sugestie: "){
        echo "Nicio sugestie";
    }else{
        echo $sugestie;
    }

?>