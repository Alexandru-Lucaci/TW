<?php

class AdminAnimalsModel extends AdminModel{

    function __construct(){
        parent::__construct();
    }

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

        return array('field_value'=>$fieldValue,'type'=>'animal_info');
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

    public function get_animal_saved_by_users(){
        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return 'Animal name is not set or is empty';
        }

        $animalName=htmlentities($_POST['animal_name']);

        //sql to get the animal id
        $sql="select id from animale where lower(denumire_populara)=lower(:animalName)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            "animalName"=>$animalName
        ]);

        $results=$statement->fetchAll();

        if(empty($results)){
            return 'Nu exista un animal cu numele '.$animalName;
        }

        if(count($results)>1){
            return 'Prea multe inregistrari pentru animalul '.$animalName;
        }

        $id=$results[0]['ID'];

        //construct sql for getting all users that save this animal

        $selectedUserFields=array("NUME_UTILIZATOR","PAROLA","EMAIL","TELEFON","ADMINISTRATOR");

        $sql='select ';
        $nrField=0;
        foreach($selectedUserFields as $field){
            if($nrField>0){
                $sql.=' , ';
            }
            $sql.=$field;
            $nrField++;
        }

        $sql.=' from utilizatori
        join salvari on id_animal=:idAnimal and id=id_utilizator';
        
        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            "idAnimal"=>$id
        ]);

        $results=$statement->fetchAll();

        if(is_null($results)){
            return 'Eroare la executarea sql';
        }

        $results['type']='get_animal_saved_by_users';

        return $results;
    }

    public function delete_animal(){
        if(!(isset($_POST['animal_name'])&&!empty($_POST['animal_name']))){
            return 'Numele animalului nu este setat sau este gol';
        }

        $animalName=htmlentities($_POST['animal_name']);

        $sql='delete from animale where lower(denumire_populara)=lower(:animalName)';

        $statement=Database::getConnection()->prepare($sql);

        $nrRowsModified=$statement->execute([
            "animalName"=>$animalName
        ]);

        if($nrRowsModified==0){
            return 'Nu exista un animal cu acest nume in baza de date';
        }

        if($nrRowsModified>1){
            return 'Eroare!Prea multe randuri au fost sterse cu aceasta operatiune';
        }

        return 'OK';
    }
}

?>