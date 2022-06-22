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
        public function output(){
            // verifici daca exita tempalteul
            if(!file_exists($this->template))
            {
                //nu exista
                return new Exception('Something went wrong, the template '. $this->template . 'does not exists');

            }
            else
            {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $viewss = new View('templates/Home.phtml');
        $views->output();

?>
</body>
</html>