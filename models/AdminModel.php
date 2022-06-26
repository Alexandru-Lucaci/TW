<?php

    class AdminModel extends Model{
        public function __construct()
        {
            parent::__construct();
        }
        public function is_administrator(){
            $name = $_SESSION['name'];
            $comandaSQL = "select administrator from utilizatori where nume_utilizator = trim(?)";
            
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement->bindParam(1, $name,PDO::PARAM_STR,100);
            $statement->execute();
            $rezultat = $statement->fetchAll();
            $rezultat = $rezultat[0];
            // var_dump($rezultat);
            if($rezultat['ADMINISTRATOR']!='0'){
                return true;
            }
            return false;
        }
        public function get_information(){
            if(!(isset($_POST['field_name']) && !empty($_POST['field_name']) && isset($_POST['field_value']) && !empty($_POST['field_value'])
            && isset($_POST['table']) && !empty($_POST['table']))
            ) 
            {
                echo 'nu am tot ce am nevoie';
                return 'nu am tot ce am nevoie ';
            }
            $tablesAllowedFields=array(
                "animale"=>array("DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","MINI_DESCRIERE","ETIMOLOGIE","ORIGINE","CLASA","INVAZIVA","STARE_DE_CONSERVARE","REGIM_ALIMENTAR","DIETA","MOD_DE_INMULTIRE","REPRODUCERE","DEZVOLTARE","VIATA","MORTALITATE","ISTORIE"),
                "utilizatori"=>array("NUME_UTILIZATOR","PAROLA","EMAIL","TELEFON","ADMINISTRATOR")
            );
            $tablesExtractedFields=array(
                "animale"=>array("DENUMIRE_POPULARA","DENUMIRE_STINTIFICA","NR_ACCESARI","NR_SALVARI","NR_DESCARCARI"),
                "utilizatori"=>array("NUME_UTILIZATOR","PAROLA","EMAIL","TELEFON","ADMINISTRATOR")
            );

            $fieldName = str_replace('','_',strtoupper($_POST['field_name']));
            $table = null;

            foreach ($tablesAllowedFields as $key => $value) {
                if(in_array($fieldName,$value)){
                    $table = $key;
                }
            }
            if(is_null($table)) {
                echo 'Something is wrong. field name does not exist';
                return 'Something is wrong. field name does not exist';
            }
            $valoare = $_POST['field_value'];

            $buildeSQL = "";
            $buildeSQL.="select ";

            $i=0;
            foreach ($tablesExtractedFields[$table] as $value) {
                if($i==0){
                    // first 
                    $buildeSQL .= $value;
                    $i++;
                }
                else
                {
                    $buildeSQL .=' , ';
                    $buildeSQL .= $value;
                    $i++;
                }
            }

            $buildeSQL .= " from $table";

            $buildeSQL .= " order by punctaj_cuvant_camp('$valoare','$fieldName,100') desc";
            $statement = Database::getConn()->prepare($buildeSQL); 
            $statement->execute();
            $rezultat = $statement->fetchAll();
            if($table =='utilizatori'){
                return array('list_users'=>$rezultat);
            }

            if($table =='animale'){
                return array('list_animale'=>$rezultat);
            }
            return 'OK';
        }
    }



?>