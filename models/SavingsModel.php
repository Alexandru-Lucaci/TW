<?php

class XMLFile{
    private $document;
    private $rootNode;

    private $listAnimals;

    function __construct($nameRootNode){
        $this->document=new DOMDocument('1.0', 'UTF-8');
        
        $this->rootNode=$this->document->createElement($nameRootNode);

        $this->document->appendChild($this->rootNode);

        $this->listAnimals=array();
    }

    public function addAnimal($animal){
        $this->rootNode->appendChild($animal->getNode($this->document));

        array_push($this->listAnimals,$animal->getAnimalInfo());
    }

    public function getContent(){
        return $this->document->saveXML();
    }

    public function getListAnimals(){
        return $this->listAnimals;
    }
}

class Animal{
    
    private $animalInfo;

    function __construct($animalInfo){
        $animalDeletedFields=array("ID","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI","CREAT_LA","ACTUALIZAT_LA");
        
        $this->animalInfo=array();
        foreach($animalInfo as $key=>$value){
            if(!in_array($key,$animalDeletedFields)){
                $this->animalInfo[$key]=$value;
            }
        }
    }

    public function getNode($document){
        $node=$document->createElement("animal");

        foreach($this->animalInfo as $key=>$value){
            $propertyNode=$document->createElement($key);
            
            $propertyNode->appendChild($document->createTextNode($value));

            $node->appendChild($propertyNode);
        }

        return $node;
    }

    public function getAnimalInfo(){
        return $this->animalInfo;
    }

}

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

    public function download(&$response,&$content,&$fileFormat){

        $allowedFileFormats=array("xml","json");
        $response=null;
        $content=null;

        if(!(isset($_POST["file_format"])&&!empty(($_POST["file_format"])))){
            $response="Formatul fisierului nu este setat sau este gol";
            return;
        }

        if(!(isset($_SESSION["saved_animals"])&&!empty(($_SESSION["saved_animals"])))){
            $response="Nu exista animale in salvari pentru";
            return;
        }

        $animalsNames=array();
        foreach($_SESSION['saved_animals'] as $animal){
            array_push($animalsNames,$animal['DENUMIRE_POPULARA']);
        }

        $fileFormat=strtolower(htmlentities($_POST["file_format"]));
        if(!in_array($fileFormat,$allowedFileFormats)){
            $response="Formatul fisierului nu este unul permis(trebuie sa fie xml sau json)";
            return;
        }

        $nrAnimals=0;

        $document=new XMLFile("menagerie");

        $sql="select * from animale where lower(trim(denumire_populara))=lower(trim(:animalName))";
        foreach($animalsNames as $animalName){
            if(!empty($animalName)){
                $statement=Database::getConnection()->prepare($sql);

                $statement->execute([
                    "animalName"=>$animalName
                ]);

                $animalInfo=$statement->fetch();
                if(!is_null($animalInfo)&&!empty($animalInfo)){
                    $nrAnimals++;

                    $document->addAnimal(new Animal($animalInfo));
                }
            }                    
        }

        if($nrAnimals==0){
            $response="Niciun animal nu a fost gasit in baza de date din cele introduse";
            return ;
        }
            
        if($fileFormat=="xml"){
            $content=$document->getContent();
        }
        if($fileFormat=="json"){
            $content=json_encode($document->getListAnimals());
        }

        $response="OK";
    }
}

?>