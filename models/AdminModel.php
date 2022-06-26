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
    }



?>