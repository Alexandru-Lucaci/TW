<?php

    class View{

        protected $template;
        protected $data = array();

        public function __construct($file){
            $this->template = $file;
        }

        public function __set($key, $value){
            $this->data[$key] = $value;
        }

        public function __get($key){
            return $this->data[$key];
        }

        public function output(){

            if (!file_exists($this->template)){
                throw new Exception("Template " . $this->template . " doesn't exist.");
            }

            $data=$this->data;

            ob_start();
            include($this->template);
            $output = ob_get_contents();
            ob_end_clean();
            
            return $output;
        }
    }
?>