<?php

    class Model{
        
        protected $connection;
        protected $query;

        public function __construct(){

            $this->connection = Database::getConnection();
        }

        protected function setQuery($query){
            $this->query = $query;
        }

        public function getAll($data = null){

            if (!$this->query){
                throw new Exception("No SQL query!");
            }

            $statement = $this->connection->prepare($this->query);

            $statement->execute($data);

            return $statement->fetchAll();
        }
    }
?>