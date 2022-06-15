<?php

class AdminModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public static function is_administrator(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Trebuie sa te autentifici pe contul pe care doriti sa il verifica ca este admin";
        }

        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=$_SESSION['username'];

        $sql="select administrator from utilizatori where nume_utilizator=trim(:username)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            "username"=>$username
        ]);

        $result=$statement->fetchAll();

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

    public function get_user_information(){

        if(!(isset($_POST['username'])&&!empty($_POST['username']))){                        
            return 'Eroare!Numele de utilizator nu este setat sau este gol';
        }

        $username=htmlentities($_POST['username']);

        //get user info
        $sql="select * 
        from utilizatori
        where nume_utilizator=trim(?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);

        $statement->execute();

        $results=$statement->fetchAll();

        if(is_null($results)){
            return 'Informatia obtinuta este nula';
        }

        if(count($results)==0){
            return 'Nu exista informatii despre acest utilizator in baza de date(poate a fost sters contul intre timp)';
        }
        if(count($results)>1){
            return 'Acest nume de utilizator nu ar trebui sa poata sa aiba atatea informatii asociate :( ';
        }

        return array('user_info'=>$results[0]);
    }

    public function change_account_information(){

        if(!(isset($_POST["username"])&&!empty($_POST["username"]))){
            return "Numele utilizatorului nu este setat sau este gol";
        }

        if(!(isset($_POST["field_name"])&&!empty($_POST["field_name"]))){
            return "Numele campului nu este setat sau este gol";
        }

        if(!(isset($_POST["field_value"])&&!empty($_POST["field_value"]))){
            return "Valoarea noua pentru camp nu este setat sau este goala";
        }

        $result=null;

        $username=htmlentities($_POST['username']);
        $fieldName=htmlentities($_POST['field_name']);
        $fieldValue=htmlentities($_POST['field_value']);

        $sql="call schimbare_camp_utilizator(?,?,?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$fieldName,PDO::PARAM_STR,50);
        $statement->bindParam(3,$fieldValue,PDO::PARAM_STR,100);
        $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,200);

        $statement->execute();

        if(is_null($result)){
            return "Ceva sa intamplat cu executia sql";
        }
        else if($result!="OK"){
            return $result;
        }

        return "OK";
    }

    public function delete_account(){

        if(!(isset($_POST['username'])&&!empty($_POST['username']))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=htmlentities($_POST['username']);
        $result=null;

        $sql="call sterge_utilizator(?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();

        if(is_null($result)){
            return "Eroare neasteptata cu executia sql";
        }
        else if($result!='OK'){
            return $result;
        }

        return "OK";
    }

    public function get_saved_animals(){

        if(!(isset($_POST["username"])&&!empty($_POST["username"]))){
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

        return array('users_saved_animals'=>$results);
    }

    /*
        if(!(isset($_POST[''])&&!empty($_POST['']))){
            return '';
        }
    */

    public function get_animal_field_value(){
        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return 'Numele animalului nu este setat sau este gol';
        }

        if(!(isset($_POST['field_name'])&&!empty($_POST['field_name']))){
            return 'Numele campului nu este setat sau este gol';
        }

        $fieldNames=array("ID","DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","MINI_DESCRIERE","ETIMOLOGIE","ORIGINE","CLASA","INVAZIVA","STARE_DE_CONSERVARE","REGIM_ALIMENTAR","DIETA","MOD_DE_INMULTIRE","REPRODUCERE","DEZVOLTARE","VIATA","MORTALITATE","ISTORIE","DUSMANI_NATURALI","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI","CREAT_LA","ACTUALIZAT_LA");
        
        $fieldName=str_replace(' ','_',strtoupper(htmlentities($_POST['field_name'])));
        $animalName=htmlentities($_POST['animal_name']);
        if(!in_array($fieldName,$fieldNames)){
            return "Numele campului nu exista sau nu este permis pentru a accesa din tabela animale";
        }

        $sql="select $fieldName 
        from animale
        where lower(denumire_populara)=lower(:animalName)";
        
        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            "animalName"=>$animalName
        ]);

        $results=$statement->fetchAll();

        if(is_null($results)){
            return 'Eroare la executia sql';
        }

        if(empty($results)){
            return 'Nu exista un animal cu acest nume';
        }

        if(count($results)>1){
            return 'Eroare!Prea multe informatii pentru un animal au fost returnate';
        }

        $fieldValue=$results[0][$fieldName];
        if(is_null($fieldValue)||empty($fieldValue)){
            $fieldValue='-';
        }

        return array('field_value'=>$fieldValue);
    }

    public function change_animal_field_value(){
        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return 'Numele animalului nu este setat sau este gol';
        }

        if(!(isset($_POST['field_name'])&&!empty($_POST['field_name']))){
            return 'Numele campului nu este setat sau este gol';
        }

        if(!(isset($_POST['field_value'])&&!empty($_POST['field_value']))){
            return 'Valoare noua pentru acest camp nu este setata sau este goala';
        }

        $animalName=htmlentities($_POST['animal_name']);
        $fieldName=str_replace(' ','_',strtoupper(htmlentities($_POST['field_name'])));
        $fieldValue=htmlentities($_POST['field_value']);

        $fieldsLimit=array('DENUMIRE_POPULARA'=>50,
        'DENUMIRE_STINTIFICA'=>100,
        'MINI_DESCRIERE'=>500,
        'ETIMOLOGIE'=>4000,
        'ORIGINE'=>20,
        'CLASA'=>20,
        'INVAZIVA'=>20,
        'STARE_DE_CONSERVARE'=>40,
        'REGIM_ALIMENTAR'=>15,
        'DIETA'=>1300,
        'MOD_DE_INMULTIRE'=>30,
        'REPRODUCERE'=>4000,
        'DEZVOLTARE'=>4000,
        'VIATA'=>4000,
        'MORTALITATE'=>4000,
        'ISTORIE'=>4000,
        'DUSMANI_NATURALI'=>1000,
        'NR_ACCESARI'=>37,
        'NR_SALVARI'=>37,
        'NR_DESCARCARI'=>37
        );

        if(!isset($fieldsLimit[$fieldName])){
            return 'Campul nu este permis sau nu exista in baza de date pentru a fi modificat';
        }

        if(strlen($fieldValue)>$fieldsLimit[$fieldName]){
            return 'Valoarea noua a campului este prea mare(campul '.$fieldName.' permite cel mult '.$fieldsLimit[$fieldName].' caractere';
        }

        if(in_array($fieldName,array('NR_ACCESARI','NR_SALVARI','NR_DESCARCARI'))&&!is_numeric($fieldValue)){
            return 'Valuarea pentru campul '.$fieldName.' trebuie sa fie un numar';
        }

        $sql="update animale set $fieldName=:fieldValue where denumire_populara=:animalName";

        $statement=Database::getConnection()->prepare($sql);

        $nrRowsModified=$statement->execute([
            "fieldValue"=>$fieldValue,
            "animalName"=>$animalName
        ]);


        if(is_null($nrRowsModified)){
            return 'Eroare la executarea sql';
        }

        if($nrRowsModified==0){
            return 'Animalul cu numele '.$animalName.' nu exista';
        }

        if($nrRowsModified>1){
            return 'Eroare grava la baza de date';
        }

        return "OK";
    }
}

?>