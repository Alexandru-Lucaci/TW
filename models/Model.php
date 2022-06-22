<?php

    abstract class Model{

        public $connection;
        private $querry;


        public function __constract(){
            $this->connection = Database::getConn();


        }
        public function setQuerry($querry){
            $this->querry = $querry;
        }





    }


?>