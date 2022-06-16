<?php

    class AdminAnimalsView extends View{

        function __construct(){
            parent::__construct("views/templates/AdminAnimals.phtml");
        }

        function show($content=null){

            $this->data = $content;

            return $this->output();
        }
    }

?>