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
        
        }


    }
?>