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

class InputField{

    private $fieldName;
    private $maxLength;
    private $isRequired;

    public function __construct($fieldName,$maxLength,$isRequired){
        $this->fieldName=$fieldName;
        $this->maxLength=$maxLength;
        $this->isRequired=$isRequired;
    }

    public function getFieldName(){
        return $this->fieldName;
    }

    public function getMaxLength(){
        return $this->maxLength;
    }

    public function isRequired(){
        return $this->isRequired;
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

    private static function set_search_results($results){
        $_SESSION['search_results']=$results;
        $_SESSION['page_number']=1;
    }

    /**
     * Function that ,given a string with the values for the criterias and,using a helper function,returns/gives the values as an associative array
     * Input string format:criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
     */
    public function multicriterial_search(){
        
        //TODO
        //2.since such a query might return more than 4000 characters(the limit for varchar2) we should use a large object 
        //to return the characters(in plsql)

        if(!(isset($_POST['query'])&&!empty($_POST['query']))){
            return "Value for query is not set or empty";
        }
        //execute procedure to start search
        $result='';

        $searchCriterias=htmlentities($_POST['query']);
        if(strlen($searchCriterias)>4000){
            return "A search criteria string cannot have more than 4000 characters";
        }

        $response=null;

        $sql="call cautare_multicriteriala(?,?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$searchCriterias,PDO::PARAM_STR,1000);
        $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,4000);
        $statement->bindParam(3,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();

        if(is_null($response)){
            return "Unexpected  error after sql statement";
        }

        if($response!="OK"){
            return $response;
        }

        //save the results in the $_SESSION variable
        $this->set_search_results($this::get_array($result,'#',',','='));

        return "OK";
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

        if(!(isset($_POST["text"])&&!empty($_POST["text"]))){
            return "The text given is either null or empty";
        }

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
            return "No animals found";
        }   

        $this::set_search_results($result);

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

    public function save_animals(){

        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"]))){
            return "Not logged into an account";
        }

        if(!(isset($_SESSION["username"])&&!empty($_SESSION["username"]))){
            return "Username is unset or empty";
        }

        if(!(isset($_POST["animal_names"])||!empty($_POST["animal_names"]))){
            return "String for animal names is unset or empty";
        }

        $username=htmlentities($_SESSION["username"]);
        $animalNames=htmlentities($_POST["animal_names"]);
        $separator=',';
        $response=null;


        $sql="call salvare_animale(?,?,?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$animalNames,PDO::PARAM_STR,2000);
        $statement->bindParam(3,$separator,PDO::PARAM_STR,1);
        $statement->bindParam(4,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();      

        if(is_null($response)){
            return "Unexpected error occurred after sql statement has run";
        }

        return $response;
    }

    public function get_saved_animals(){
        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"]))&&$_SESSION["loggedIn"]==1){
            return "Not logged into an account";
        }

        if(!(isset($_SESSION["username"])&&!empty($_SESSION["username"]))){
            return "Username is unset or empty";
        }

        $username=htmlentities($_SESSION["username"]);

        $sql="select denumire_populara,denumire_stintifica,mini_descriere
        from animale 
        join salvari on id_utilizator=obtine_id_utilizator(?) and id=id_animal";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);

        $statement->execute();

        return $statement->fetchAll();
    }

        /**
     * Based on the $_POST["input_option"] value it will compute the data about the animals to be inserted from $_FILES['file']
     * The parameter checks if the fields 
     * Only takes as input files of type xml or json
     * Files cannot exceed 1GB
     * Returns an array of associative arrays,each associative array containing the details about an animal 
     * In case of failure,returns a
     * The parameter $fields is used to check if the content of the 
     */
    private static function get_field_values($fields){
        if(!isset($_POST["input_option"])){
            return "The input option(for how the info data is given) is unset";
        }

        if(empty($_POST["input_option"])){
            return "The input option(for how the info data is given) is empty";
        }

        $inputOption=htmlentities($_POST["input_option"]);
        $animalsData=array();

        if($inputOption=="direct"){

            $animalData=array();
            foreach($fields as $field){
                $fieldName=$field->getFieldName();
                if(!isset($_POST[$fieldName])){
                    return "Field with name ".$fieldName." is unset";
                }

                if($field->isRequired()&&empty($_POST[$fieldName])){
                    return "Field with name ".$fieldName." is empty,but it has to have at least one character";
                }

                $animalData[$fieldName]=htmlentities($_POST[$fieldName]);
            }

            array_push($animalsData,$animalData);
        }
        else{

            if(!isset($_FILES['file'])){
                return "File uploaded is not set";
            }

            $allowedTypes=array("application/xhtml+xml","text/xml","application/json");
            
            //accept only json/xml
            $fileType=strtolower($_FILES['file']['type']);
            if(!in_array($fileType,$allowedTypes)){
                return "Extensions for this file not allowed.Choose a file that has the proper extension(xml or json)";
            }

            //cannot be bigger than 1GB
            $fileSize=$_FILES['file']['size'];
            if($fileSize>1024000){
                return "File size is bigger than the allowed sized of 1GB";
            }

            //TODO
            //check validity of the xml/json file

            $file=null;
            $fileTmp=$_FILES['file']['tmp_name'];
            if($fileType=="application/xhtml+xml"||$fileType=="text/xml"){
                $file=simplexml_load_file($fileTmp) or die("XML file could not be loaded");
            }
            else if($fileType="application/json"){
                $file=json_decode(file_get_contents($fileTmp));
            }
            
            if(is_null($file)){
                return "Error from loading the file from tmp";
            }

            foreach($file as $animal){
                $animalData=array();
                
                foreach($fields as $field){
                    $fieldName=$field->getFieldName();
                    if($field->isRequired()){

                        if(!isset($animal->$fieldName)||empty((string)$animal->$fieldName)){
                            return "An animal doesn't have the field ".$fieldName." declared,which is a mandatory field";
                        }

                        if(strlen((string)$animal->$fieldName)>$field->getMaxLength()){
                            return "An animal has the field value for ".$fieldName." exceeding the character limit";
                        }
                    }

                    if(isset($animal->$fieldName)){
                        $animalData[$fieldName]=(string)$animal->$fieldName;
                    }
                    else{
                        $animalData[$fieldName]=null;
                    }
                }

                array_push($animalsData,$animalData);
            }
        }
        
        return $animalsData;
    }

    public function insert_animal_info(){
        
        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"]))||$_SESSION["loggedIn"]==0){
            return "Not logged into an account";
        }

        //Some contants needed before the operation
        //Order should be denumire_populara,denumire_stintifica,mini_descriere,etimologie,origine,clasa,invaziva,stare_de_conservare,regim_alimentar,dieta,mod_de_inmultire,reproducere,dezvoltare,viata,mortalitate,istorie,dusmani_naturali
        //Order matters,since this will be the order of insertion in the database
        $fields=array(
            new InputField('DENUMIRE_POPULARA',50,true),
            new InputField('DENUMIRE_STINTIFICA',100,true),
            new InputField('MINI_DESCRIERE',500,false),
            new InputField('ETIMOLOGIE',4000,false),
            new InputField('ORIGINE',20,false),
            new InputField('CLASA',20,false),
            new InputField('INVAZIVA',20,false),
            new InputField('STARE_DE_CONSERVARE',40,false),
            new InputField('REGIM_ALIMENTAR',15,false),
            new InputField('DIETA',1300,false),
            new InputField('MOD_DE_INMULTIRE',30,false),
            new InputField('REPRODUCERE',4000,false),
            new InputField('DEZVOLTARE',4000,false),
            new InputField('VIATA',4000,false),
            new InputField('MORTALITATE',4000,false),
            new InputField('ISTORIE',4000,false),
            new InputField('DUSMANI_NATURALI',1000,false)
        );


        $animalsData=$this::get_field_values($fields);
        if(!is_array($animalsData)){
            return $animalsData;
        }

        //TODO: maybe a better way to do this than assuming?
        //assume that the importUtils file has the corresponding number of parameters to be inserted
        //create string for sql

        $responseArray=array();

        foreach($animalsData as $animalData){

            $response=null;

            $sql="call inserare_informatii_animal(?";
            $nrParameters=count($fields);
            for($i=1;$i<$nrParameters;$i++){
                $sql.=",?";
            }
            $sql.=",?"; //for the response
            $sql.=")";
    
            //create statement
            $statement=Database::getConnection()->prepare($sql);
        
            //set values for statement
            
            $position=1;
            foreach($fields as $field){
    
                $fieldName=$field->getFieldName();
                $maxLength=$field->getMaxLength();
    
                $statement->bindParam($position,$animalData[$fieldName],PDO::PARAM_STR,$maxLength);
    
                $position++;
            }
            $statement->bindParam($position,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
    
            //execute
            $statement->execute();
    
            if(is_null($response)){
                return "Unexpected error when executing the sql query";
            }
            
            array_push($responseArray,$response);
        }

        return $responseArray;
    }

    public function change_results_page(){
        if(!(isset($_SESSION["search_results"])&&!empty($_SESSION["search_results"]))){
            return "No search result is set or is empty";
        }

        if(!(isset($_SESSION["page_number"])&&!empty($_SESSION["page_number"]))){
            return "No search result page number is set or is empty";
        }

        if(!(isset($_POST["change_value"])&&!empty($_POST["change_value"]))){
            return "The value change for the page number is not set or is empty";
        }

        $nrAnimals=count($_SESSION["search_results"]);
        $currentPageNr=$_SESSION["page_number"];
        $changeValue=$_POST["change_value"];

        if($changeValue==-1){
            if(!($currentPageNr>1)){
                return "This is the first page";
            }
            $currentPageNr--;
        }
        else if($changeValue==1){
            $nrAnimalsPerPage=$_POST["results_per_page"];
            if(!($currentPageNr*$nrAnimalsPerPage<$nrAnimals)){
                return "This is the last page";
            }
            $currentPageNr++;
        }
        $_SESSION['page_number']=$currentPageNr;

        return "OK";
    }
}

?>