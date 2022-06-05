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

class AnimalsModel extends Model{

    function __construct(){
        parent::__construct();
    }

            /**
     * Helper function that takes a string of the format:
     * key1=value1,key2=value2,..#key1=value1,key2=value2,...
     * and returns an array of arrays containing the information
     */
    private static function get_array($text,$lineSeparator,$valueSeparator,$equalSeparator){
        $result=array();
        
        $lines=explode($lineSeparator,$text);
        foreach($lines as $line){

            $associative_array=array();

            $fields=explode($valueSeparator,$line);

            foreach($fields as $field){
                
                $set=explode($equalSeparator,$field);

                $key=$set[0];
                $value=$set[1];

                $associative_array[$key]=$value;
            }

            array_push($result,$associative_array);
        }

        return $result;
    }


    /**
     * Function that ,given a string with the values for the criterias and,using a helper function,returns/gives the values as an associative array
     * Input string format:criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
     */
    public function multicriterial_search(){
        
        //TODO
        //1.restrict the size of the query string ,maybe not bigger than 1000
        //2.since such a query might return more than 4000 characters(the limit for varchar2) we should use a large object 
        //to return the characters(in plsql)
        //3.make the result gotten from the procedure to be given as an associative array(would be nice to :) )


        $result='';

        if(isset($_POST['query'])&&!empty($_POST['query'])){
            $searchCriterias=$_POST['query'];

            $response=null;

            $sql="call cautare_multicriteriala(?,?,?)";

            $statement=Database::getConnection()->prepare($sql);

            $statement->bindParam(1,$searchCriterias,PDO::PARAM_STR,1000);
            $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100000);
            $statement->bindParam(3,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

            $statement->execute();

            if(is_null($response)){
                $result="Unexpected  error after sql statement";
            }
            else if($response!="OK"){
                $result=$response;
            }
            else{
                $result=$this::get_array($result,'#',',','=');
            }                
        }
        else{
            $result="Value for query is not set or empty";
        }

        return $result;
    }

    public function get_animal_information(){
        $result=null;

        if(isset($_POST['animal_name'])&&!empty($_POST['animal_name'])){
            $animalName=$_POST['animal_name'];
            
            $sql="select * from animale where lower(denumire_populara)=:name";

            $statement=Database::getConnection()->prepare($sql);

            $statement->execute([
                'name'=>$animalName
            ]);

            $result=$statement->fetchAll();
        }
        else{
            $result="Value for animal name not set or empty";
        }

        return $result;
    }
    
    public function score_search(){
        $result=null;

        if(isset($_POST["text"])&&!empty($_POST["text"])){
                $text=htmlentities($_POST['text']);

                $sql="select denumire_populara,denumire_stintifica,mini_descriere,punctaj_animal(?,denumire_populara) as scor
                from animale
                where punctaj_animal(?,denumire_populara)>0
                order by scor desc";

                $statement=Database::getConnection()->prepare($sql);

                $statement->bindParam(1,$text,PDO::PARAM_STR,200);
                $statement->bindParam(2,$text,PDO::PARAM_STR,200);

                $statement->execute();

                $result=$statement->fetchAll();
                if(is_null($result)){
                    $result="No animals found";
                }                
        }
        else{
            $result="The text given is either null or empty";
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