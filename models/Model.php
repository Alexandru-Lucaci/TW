<?php

    // include '../utils/db.php';
    abstract class Model{

        public $connection;
        public $querry;


        public function __construct(){
            $this->connection = Database::getConn();
        }
        public function setQuerry($querry){
            $this->querry = $querry;
        }

        public function getQuerry(){
            return $this->querry;
        }



    }


?>