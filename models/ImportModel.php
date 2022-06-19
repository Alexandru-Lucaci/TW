<?php

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

class ImportModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Verificara integritatea datelor incarcate
     * Bazandu-se pe $_POST["input_option"],va lua datele despre fisierul incarcat din $_FILES['file']
     * Daca se incarca un fisier,verificam ca:
     * Tipul acestuia este xml sau json
     * Nu e mai mare de 1GB
     * Returneaza un tablou de tablouri asociative,fiecare tablou asociativ  continand informatii despre animale
     * sau un mesaj de eroare corespunzator     
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

            $translation=array('ă'=>'a','î'=>'i','â'=>'a','ș'=>'s','ț'=>'t','Ă'=>'A','Î'=>'I','Â'=>'A','Ș'=>'S','Ț'=>'T');

            $animalData=array();
            foreach($fields as $field){
                $fieldName=$field->getFieldName();
                if(!isset($_POST[$fieldName])){
                    return "Field with name ".$fieldName." is unset";
                }

                if($field->isRequired()&&empty($_POST[$fieldName])){
                    return "Field with name ".$fieldName." is empty,but it has to have at least one character";
                }

                $fieldValue=htmlentities(strtr($_POST[$fieldName],$translation));
                if(strlen($fieldValue)>$field->getMaxLength()){
                    return 'Campul cu numele '.$fieldName.' depaseste limata de caractere permisa(datorata folositii functiei htmlentities())';
                }

                $animalData[$fieldName]=$fieldValue;
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

    /**
     * Insereaza datele despre animale in baza de date,folosindu-se de functia ajutatoare get_field_values()
     * Returneaza un tablou cu raspunsuri
     */
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
            new InputField('MINI_DESCRIERE',1500,false),
            new InputField('ETIMOLOGIE',4000,false),
            new InputField('ORIGINE',20,false),
            new InputField('CLASA',20,false),
            new InputField('HABITAT',40,false),
            new InputField('INVAZIVA',20,false),
            new InputField('STARE_DE_CONSERVARE',40,false),
            new InputField('REGIM_ALIMENTAR',15,false),
            new InputField('DIETA',4000,false),
            new InputField('MOD_DE_INMULTIRE',30,false),
            new InputField('REPRODUCERE',4000,false),
            new InputField('DEZVOLTARE',4000,false),
            new InputField('VIATA',4000,false),
            new InputField('ISTORIE',4000,false),
            new InputField('DUSMANI_NATURALI',4000,false)
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
}

?>