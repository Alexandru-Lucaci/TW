<?php

    class HomeView extends View{
        public function __construct()
        {
            echo "should show";
            parent::__construct('views/templates/Home.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>