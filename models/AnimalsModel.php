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
     * Save in the session the search results
     */
    private static function set_search_results($results){
        $_SESSION['search_results']=$results;
        $_SESSION['page_number']=1;
    }

    /**
     * Save in the session the options chosen for the search
     * Helps by 'remebering' the users choice
     */
    private static function set_form_criterias($criterias){
        foreach($criterias as $criteria){
            //unset and save the new criteria values,if they exist in the request
            unset($_SESSION[$criteria]);
            if(isset($_POST[$criteria])&&!empty($_POST[$criteria])){
                $_SESSION[$criteria]=$_POST[$criteria];
            }
        }
    }

    /**
     * Computes the clauses in sql for the where and oder by
     */
    private static function compute_clauses(&$whereClause,$whereColumns,&$orderByClause,$orderByColumn){
        
        //compute the where clause,if conditions exist
        $nrTerms=0;
        $whereClause='';
        foreach($whereColumns as $column){
            if(isset($_POST[$column])&&!empty($_POST[$column])){

                if($nrTerms>0){
                    $whereClause.=' and ';
                }

                $whereClause.=' ( ';

                $position=0;
                $values=$_POST[$column];
                foreach($values as $value){
                    if($position!=0){
                        $whereClause.=' or ';
                    }
                    $whereClause.= $column.'=\''.strtolower($value).'\'';

                    $position++;
                }

                $whereClause.=" ) ";

                $nrTerms++;
            }
        }

        //compute the order by clause,if conditions exist

        $orderByClause='';
        if(isset($_POST[$orderByColumn])&&!empty($_POST[$orderByColumn])){

            $position=0;
            $values=$_POST[$orderByColumn];
            foreach($values as $value){
                if($position!=0){
                    $orderByClause.=' , ';
                }
                $orderByClause.=strtolower($value).' asc ';

                $position++;
            }
        }
    }

    /**
     * Function that ,given a string with the values for the criterias and,using a helper function,returns/gives the values as an associative array
     * Input string format:criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
     */
    public function multicriterial_search(){

        //some constants
        $orderBy="ordonare";
        $columns=array("origine","clasa","habitat","invaziva","stare_de_conservare","regim_alimentar","mod_de_inmultire");

        //get the where and order by clause conditions
        $whereClause='';
        $orderByClause='';
        $this::compute_clauses($whereClause,$columns,$orderByClause,$orderBy);

        //set the new values for the session
        array_push($columns,$orderBy);
        $this::set_form_criterias($columns);

        //construct the sql code

        $sql="select denumire_populara,denumire_stintifica,mini_descriere
        from animale";

        if(strlen($whereClause)>0){
            $whereClause=' where '.$whereClause;
            $sql.=$whereClause;
        }

        if(strlen($orderByClause)>0){
            $orderByClause=' order by '.$orderByClause;
            $sql.=$orderByClause;
        }

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute();

        $results=$statement->fetchAll();

        //save the results in the $_SESSION variable
        $this->set_search_results($results);
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
            $result="Numele animalulu nu este setat sau este gol";
        }

        return $result;
    }
    
    public function score_search(){

        if(!(isset($_POST["text"])&&!empty($_POST["text"]))){
            return "Textul scris nu este setat sau este gol";
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
            return "Niciun animal gasit";
        }   

        $this::set_search_results($result);

        return $result;
    }

    public function download(&$response,&$content,&$fileFormat){

        $allowedFileFormats=array("xml","json");
        $response=null;
        $content=null;

        if(!(isset($_POST["file_format"])&&!empty(($_POST["file_format"])))){
            $response="Formatul fisierului nu este setat sau este gol";
            return;
        }

        if(isset($_POST["sent_by_session"])){
            if(!(isset($_SESSION['search_results'])&&!empty($_SESSION['search_results']))){
                $response='Rezultatele nu sunt salvate in sesiune';
                return;
            }

            $animalsNames=array();
            foreach($_SESSION['search_results'] as $animal){
                array_push($animalsNames,$animal['DENUMIRE_POPULARA']);
            }
        }
        else{
            if(!(isset($_POST["animals_names"])&&!empty(($_POST["animals_names"])))){
                $response="Numele pentru animalele descarcate nu este setat sau este gol";
                return;
            }
            $animalsNames=explode(',',htmlentities($_POST["animals_names"]));
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

    public function save_animals(){

        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"])&&$_SESSION['loggedIn']==1)){
            return "Nu sunteti autentificat intr-un cont pentru a putea salva animalul";
        }

        if(!(isset($_SESSION["username"])&&!empty($_SESSION["username"]))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        if(!(isset($_POST["animal_names"])&&!empty($_POST["animal_names"]))){
            return "Numele pentru animale nu este setat sau este gol";
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
            return "Eroare neasteptata la rularea sql";
        }

        return $response;
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
            return "Niciun rezultat de cautare este setat sau este gol";
        }

        if(!(isset($_SESSION["page_number"])&&!empty($_SESSION["page_number"]))){
            return "Numarul de pagina nu este setat sau este gol";
        }

        if(!(isset($_POST["change_value"])&&!empty($_POST["change_value"]))){
            return "Cu cat schimbam numarul paginii nu este setat sau este gol";
        }

        $nrAnimals=count($_SESSION["search_results"]);
        $currentPageNr=$_SESSION["page_number"];
        $changeValue=$_POST["change_value"];

        if($changeValue==-1){
            if(!($currentPageNr>1)){
                return "Aceasta este prima pagina";
            }
            $currentPageNr--;
        }
        else if($changeValue==1){
            if(!(isset($_POST["results_per_page"])&&!empty($_POST["results_per_page"]))){
                return "Nu este setat numarul de rezultate pe pagina";    
            }

            $nrAnimalsPerPage=$_POST["results_per_page"];
            if(!($currentPageNr*$nrAnimalsPerPage<$nrAnimals)){
                return "Aceasta este ultima pagina";
            }
            $currentPageNr++;
        }
        $_SESSION['page_number']=$currentPageNr;

        return "OK";
    }

    public static function animal_saved($username,$animalName){

        if(empty($username)){
            return 'Numele utilizatorului nu poate fi gol';
        }

        if(empty($animalName)){
            return 'Numele animalului nu poate fi gol';
        }

        $connection=Database::getConnection();

        //get username id
        $sql="select id from utilizatori where nume_utilizator=trim(:username)";

        $statement=$connection->prepare($sql);

        $statement->execute([
            "username"=>$username
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return 'Nu exista cont pentru utilizatorul cu numele '.$username;
        }
        if(count($result)>1){
            return 'Eroare!Prea multe informatii asociate numelui '.$username;
        }

        $userId=$result[0]['ID'];

        //get animal name id

        $sql='select id from animale where lower(denumire_populara)=lower(:animalName)';

        $statement=$connection->prepare($sql);

        $statement->execute([
            'animalName'=>$animalName
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return 'Nu exista un animal cu numele '.$animalName.' in baza de date';
        }
        if(count($result)>1){
            return 'Eroare!Prea multe informatii asociate animalului cu numele '.$animalName;
        }

        $animalId=$result[0]['ID'];

        //check to see if the user has saved this animal
        $sql='select count(*) from salvari where id_animal=:animalId and id_utilizator=:userId';

        $statement=$connection->prepare($sql);

        $statement->execute([
            'animalId'=>$animalId,
            'userId'=>$userId
        ]);

        $result=$statement->fetchAll();

        return ($result[0]['COUNT(*)']==0)?false:true;
    }

    public function delete_animal_from_savings(){

        if(!(isset($_SESSION["loggedIn"])&&!empty($_SESSION["loggedIn"])&&$_SESSION['loggedIn']==1)){
            return "Nu sunteti autentificat intr-un cont pentru a putea sterge animalul";
        }

        if(!(isset($_SESSION["username"])&&!empty($_SESSION["username"]))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        if(!(isset($_POST["animal_name"])&&!empty($_POST["animal_name"]))){
            return "Numele pentru animal nu este setat sau este gol";
        }

        $username=$_SESSION['username'];
        $animalName=$_POST['animal_name'];

        $connection=Database::getConnection();

        //get username id
        $sql="select id from utilizatori where nume_utilizator=trim(:username)";

        $statement=$connection->prepare($sql);

        $statement->execute([
            "username"=>$username
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return 'Nu exista cont pentru utilizatorul cu numele '.$username;
        }
        if(count($result)>1){
            return 'Eroare!Prea multe informatii asociate numelui '.$username;
        }

        $userId=$result[0]['ID'];

        //get animal name id

        $sql='select id from animale where lower(denumire_populara)=lower(:animalName)';

        $statement=$connection->prepare($sql);

        $statement->execute([
            'animalName'=>$animalName
        ]);

        $result=$statement->fetchAll();

        if(empty($result)){
            return 'Nu exista un animal cu numele '.$animalName.' in baza de date';
        }
        if(count($result)>1){
            return 'Eroare!Prea multe informatii asociate animalului cu numele '.$animalName;
        }

        $animalId=$result[0]['ID'];

        //delete from the save tabel
        $sql='delete from salvari where id_animal=:animalId and id_utilizator=:userId';

        $statement=$connection->prepare($sql);

        $nrRowsModified=$statement->execute([
            'animalId'=>$animalId,
            'userId'=>$userId
        ]);

        if($nrRowsModified==0){
            return 'Nu a fost gasita aceasta salvare in baza de date';
        }

        if($nrRowsModified>1){
            return 'Eroare!Prea multe stergeri de la salvari';
        }

        return "OK";
    }
}

?>