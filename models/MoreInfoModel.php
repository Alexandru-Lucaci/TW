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

class MoreInfoModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public function get_animal_information(){

        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return "Numele animalului nu e setat sau e gol";
        }

        $animalName=htmlentities($_POST['animal_name']);
            
        $sql="select * from animale where lower(denumire_populara)=:name";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            'name'=>$animalName
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return "Nu exista informatii despre acest animal";
        }

        return $result;
    }

    public function download(&$response,&$content,&$fileFormat){
        $allowedFileFormats=array("xml","json");
        $response=null;
        $content=null;

        if(isset($_POST["file_format"])&&!empty(($_POST["file_format"]))&&isset($_POST["animals_names"])&&!empty(($_POST["animals_names"]))){
            $fileFormat=strtolower(htmlentities($_POST["file_format"]));
            $animalsNames=explode(',',htmlentities($_POST["animals_names"]));

            $nrAnimals=0;

            $document=new XMLFile("menagerie");

            if(in_array($fileFormat,$allowedFileFormats)){
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

                if($nrAnimals>0){
                    $response="OK";
                    
                    if($fileFormat=="xml"){
                        $content=$document->getContent();
                    }
                    if($fileFormat=="json"){
                        $content=json_encode($document->getListAnimals());
                    }
                }
                else{
                    $response="No animals found";
                }
            }
            else{
                $$response="File format not supported for download";
            }            
        }
        else{
            $$response="The file format is either unset or empty,or the animal_names is unset or empty";
        }
    }
}

?>