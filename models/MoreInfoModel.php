<?php

/**
 * Folosita pentru a genera un fisier XML
 */
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

/**
 * Folosita pentru a asocia informatiile despre un animal ca un nod pentru document
 */
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

class MoreInfoModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Obtine informatiile despre un animal
     * Prin POST primeste numele animalului
     * Returneaza un tablou cu aceste informatii, sau un mesaj de eroare
     */
    public function get_animal_information(){

        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return "Numele animalului nu e setat sau e gol";
        }

        $animalName=htmlentities($_POST['animal_name']);
            
        $sql="select * from animale where lower(denumire_populara)=lower(:name)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            'name'=>$animalName
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return "Nu exista informatii despre acest animalul cu numele '".$animalName."'";
        }

        return $result;
    }

    /**
     * Functie pentru descarcarea informatiilor despre animale.
     * Primeste ca parametru tipul fisierului descarcat(XML sau JSON) si tipul de trimitere a numelor animalelor
     * Schimba la referinta &$content valoarea respectiva 
     * Returneaza 'OK' daca totul merge bine sau un mesaj de eroare altfel
     */
    public function download(&$response,&$content,&$fileFormat){

        $allowedFileFormats=array("xml","json");
        $response=null;
        $content=null;

        if(!(isset($_POST["file_format"])&&!empty(($_POST["file_format"])))){
            $response="Formatul fisierului nu este setat sau este gol";
            return;
        }

        if(!(isset($_POST["animals_names"])&&!empty(($_POST["animals_names"])))){
            $response="Numele pentru animalele descarcate nu este setat sau este gol";
            return;
        }

        $fileFormat=strtolower(htmlentities($_POST["file_format"]));
        if(!in_array($fileFormat,$allowedFileFormats)){
            $response="Formatul fisierului nu este unul permis(trebuie sa fie xml sau json)";
            return;
        }

        $animalsNames=explode(',',htmlentities($_POST["animals_names"]));

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