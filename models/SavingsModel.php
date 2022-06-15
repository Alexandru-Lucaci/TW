<?php

class SavingsModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Save to session the animals saved and the starting page
     */
    public static function save_to_session($animals){
        $_SESSION['saved_animals']=$animals;
        $_SESSION['savings_page_number']=1;
    }

    public function get_saved_animals(){
        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"]))&&$_SESSION["loggedIn"]==1){
            return "Nu esti autentificat intr-un cont";
        }

        if(!(isset($_SESSION["username"])&&!empty($_SESSION["username"]))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=htmlentities($_SESSION["username"]);

        $sql="select denumire_populara,denumire_stintifica,mini_descriere
        from animale 
        join salvari on id_utilizator=obtine_id_utilizator(?) and id=id_animal";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);

        $statement->execute();

        $results=$statement->fetchAll();

        if(is_null($results)){
            return "Eroare la executarea sql";
        }

        $this->save_to_session($results);

        return $results;
    }
    
    public function change_savings_page(){
        if(!(isset($_SESSION["saved_animals"])&&!empty($_SESSION["saved_animals"]))){
            return "Nu exista animale salvate sau variabila este goala";
        }

        if(!(isset($_SESSION["savings_page_number"])&&!empty($_SESSION["savings_page_number"]))){
            return "Nu este setat numarul paginii sau este gol";
        }

        if(!(isset($_POST["change_value"])&&!empty($_POST["change_value"]))){
            return "Valoarea cu care se schimba pagina nu este setata sau este goala";
        }

        $nrAnimals=count($_SESSION["saved_animals"]);
        $currentPageNr=$_SESSION["savings_page_number"];
        $changeValue=$_POST["change_value"];

        if($changeValue==-1){
            if(!($currentPageNr>1)){
                return "Aceasta este prima pagina";
            }
            $currentPageNr--;
        }
        else if($changeValue==1){
            if(!(isset($_SESSION["savings_page_number"])&&!empty($_SESSION["savings_page_number"]))){
                return "Nu este setat numarul de rezultate pe pagina";    
            }

            $nrAnimalsPerPage=$_POST["results_per_page"];
            if(!($currentPageNr*$nrAnimalsPerPage<$nrAnimals)){
                return "Aceasta este ultima pagina";
            }
            $currentPageNr++;
        }
        $_SESSION['savings_page_number']=$currentPageNr;

        return "OK";
    }
}

?>