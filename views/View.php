<?php

    abstract class View{
        private $template;
        private $data = array();

        
        public function getTemplate()
        {
            return $this->template;
        }
        public function getData()
        {
            return $this->data;
        }
        public function __construct($fisier){
            $this->template = $fisier;
        }

        public function __get($key){
            return $this->data[$key];
        }
        public function __set($key,$value){
            $this->data[$key] = $value; 
        }
        

    }
?>