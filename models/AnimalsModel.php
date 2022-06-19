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

/**
 * Clasa pentru a asocia un camp de intrare cu propietatile asociate(cum ar fi numele campului,lungime maxima,este necesar)
 */
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
     * Salveaza rezultatele cautarii in sesiune
     */
    private static function set_search_results($results){
        $_SESSION['search_results']=$results;
        $_SESSION['page_number']=1;
    }

    /**
     * Salveaza criteriile alese de catre utilizator in sesiune
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
     * Genereaza clauzele logile pentru where and order folosite in sql
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
                    $whereClause.= 'lower('.$column.')=\''.strtolower($value).'\'';

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
     * Cautare multicriteriala ,care pe baza unui sir , obtine din baza de date animalele care au campurile cu valorile asociate acestora
     * Format :criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
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

    /**
     * Obtine informatii despre un animal
     * Primeste prin POST numele animalului
     * Returneaza informatiile despre animale intr-un tablou asociativ,sau un mesaj de eroare
     */
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
    
    /**
     * Cautare bazata pe asocierea unui animal o valoarea in functie de 'asemanarea' cu textul scris.
     * Textul va fi despartit in cuvinte care vor fi comparate cu valorile asociate unui animal,in care un camp are un punctaj maxim asociat(pe baza cator litere din acel cuvant e egal cu valoarea campului)
     * Suma acestora va fi punctajul unui animal,punctaj cu valoare mai mare fiind mai bun.
     * Returneaza animalele cu punctajele asociate in ordinea descrescatoare a punctajului,sau un mesaj de eroare
     */
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

    /**
     * Verificare ca exista un utilizator conectat si salveaza animalele care exista in baza de date cu numele oferite
     * Returneaza 'OK' daca totul merge bine sau un mesaj de eroare altfel
     */
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
     * Schimba in sesiune numarul curent al paginii(daca este posibil).
     * Primeste prin POST pagina cu care se schimba valoarea si numarul maxim de rezultate de pagini
     * Returneaza 'OK' daca totul merge bine sau un mesaj de eroare altfel
     */
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

    /**
     * Verifica daca un utilizator are la salvari acest animal
     * @param username descrie numele utilizatorului
     * @param animalName descrie numele animalului
     */
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

    /**
     * Sterge din salvari un animal pentru utilizatorul conectat in acest moment
     * Primeste prin POST numele animalului
     * Returneaza 'OK' daca totul merge bine sau un mesaj de eroare altfel
     */
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