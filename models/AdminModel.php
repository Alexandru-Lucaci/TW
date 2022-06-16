<?php

class AdminModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public function is_administrator(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Trebuie sa te autentifici pe contul pe care doriti sa il verifica ca este admin";
        }

        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=$_SESSION['username'];

        $sql="select administrator from utilizatori where nume_utilizator=trim(:username)";

        $this->setQuery($sql);

        $result=$this->getAll(array("username"=>$username));

        if(is_null($result)){
            return "Eroare la executia sql";
        }

        if(empty($result)){
            return "Utilizatorul cu numele '".$username."' nu exista in baza de date";
        }

        else if(count($result)>1){
            return 'Acest nume de utilizator nu ar trebui sa poata sa aiba atatea informatii asociate :( ';
        }

        return ($result[0]['ADMINISTRATOR']==0)?false:true;
    }
    
    public function get_information(){

        //TODO
        //1.Adapt to work well for natural enemies column
        //2.Use a plsql procedure to get the allowed columns for the table associated

        if(!(isset($_POST["field_name"])&&!empty($_POST["field_name"]))){
            return "The field name is unset or empty";
        }

        if(!(isset($_POST["field_value"])&&!empty($_POST["field_value"]))){
            return "The field value is unset or empty";
        }

        if(!(isset($_POST["table"])&&!empty($_POST["table"]))){
            return "The table name is unset or empty";
        }

        //allowed fields names for table names
        $tablesAllowedFields=array(
            "animale"=>array("DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","MINI_DESCRIERE","ETIMOLOGIE","ORIGINE","CLASA","INVAZIVA","STARE_DE_CONSERVARE","REGIM_ALIMENTAR","DIETA","MOD_DE_INMULTIRE","REPRODUCERE","DEZVOLTARE","VIATA","MORTALITATE","ISTORIE"),
            "utilizatori"=>array("NUME_UTILIZATOR","PAROLA","EMAIL","TELEFON","ADMINISTRATOR")
        );
        $tablesExtractedFields=array(
            "animale"=>array("DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI"),
            "utilizatori"=>array("NUME_UTILIZATOR","PAROLA","EMAIL","TELEFON","ADMINISTRATOR")
        );

        $fieldName=str_replace(' ','_',strtoupper(htmlentities($_POST["field_name"])));
        
        $tableName=null;
        foreach($tablesAllowedFields as $name=>$fields){
            if(in_array($fieldName,$fields)){
                $tableName=$name;
                break;
            }
        }

        if(is_null($tableName)){
            return "The column with the name '".$fieldName."' does not exist";
        }

        $fieldValue=htmlentities($_POST["field_value"]);
        //we have the table name,field name and field value,let's begin the sql


        $sql="select ";
        
        //set selected fields
        $count=0;
        foreach($tablesExtractedFields[$tableName] as $selectedField){
            if($count>0){
                $sql.=" , ";
            }
            $sql.=$selectedField;
            $count++;
        }

        //set table to extract from
        $sql.=" from $tableName";

        //set the order of the results
        $sql.=" order by punctaj_cuvant_camp('$fieldValue',$fieldName,100) desc";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute();

        $results=$statement->fetchAll();

        if($tableName=='utilizatori'){
            return array('list_users'=>$results);
        }
        
        if($tableName=='animale'){
            return array('list_animals'=>$results);
        }

        return 'Am ajuns aici,cumva';
    }
}

?>