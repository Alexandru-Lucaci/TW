<?php

    class AdminView extends View{

        function __construct(){
            parent::__construct("views/templates/Admin.phtml");
        }

        function show($content=null){

            $this->data = $content;

            return $this->output();
        }
    }

?>