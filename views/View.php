<?php
    abstract class View{
        public $template;
        protected $data = array();

        
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
        public function output(){
            // verifici daca exita tempalteul
            if(!file_exists($this->template))
            {
                //nu exista
                return new Exception('Something went wrong, the template '. $this->template . 'does not exists');

            }
            else
            {
                $data = $this->data;
                // exista 
                ob_start();
                include ($this->template);
                $result = ob_get_contents();
                ob_end_clean();
                return $result;
            }
        }

    }
?>