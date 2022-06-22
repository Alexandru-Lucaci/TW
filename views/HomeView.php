<?php

    class HomeView extends View{
        public function __construct()
        {
            parent::__construct('views/templates/Home.phtml');
        }
    }

    function show(){
        return $this->output();
    }
?>