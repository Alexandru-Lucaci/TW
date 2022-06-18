<?php
//TODO
//find out how to include th Database connection so that it wokrs properly....
//obviosuly,autoload.php and include() don't work :(
class Database{
    //CONN TW_BD_ORACLE/TW_BD_ORACLE

    private static $connection=NULL;
    
    public static function getConnection(){
        if(is_null(self::$connection)){

            $database='oci:dbname=localhost/XE';
            $username='TW_BD_ORACLE';
            $password='TW_BD_ORACLE';

            $options = [
                // erorile sunt raportate ca exceptii de tip PDOException
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // rezultatele vor fi disponibile in tablouri asociative
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // conexiunea e persistenta
                PDO::ATTR_PERSISTENT 		 => TRUE
            ];

            try{
                self::$connection=new PDO($database,$username,$password,$options);
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }                
        }
        return self::$connection;
    }
}

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

function get_contents(&$response,&$content,$fileFormat,$animalNames){

    $allowedFileFormats=array("xml","json");
    $response=null;
    $content=null;

    if(!in_array($fileFormat,$allowedFileFormats)){
        $response="Formatul fisierului nu este unul permis(trebuie sa fie xml sau json)";
        return;
    }

    $nrAnimals=0;
    $animalsNames=explode(',',$animalNames);

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


function download($fileFormat,$animalNames){

    //get array of animals
    $response=null;
    $content=null;

    get_contents($response,$content,$fileFormat,$animalNames);
    if($response=="OK"){

        if($fileFormat=="xml"){
            header('Content-type: text/xml');
            header('Content-Disposition: attachment; filename="Animals.xml"');

            echo $content;
        }
        else if($fileFormat=="json"){
            header('Content-type: text/json');
            header('Content-Disposition: attachment; filename="Animals.json"');

            echo $content;
        }
    }
}

download($_REQUEST['file_format'],$_REQUEST['animal_names']);
?>