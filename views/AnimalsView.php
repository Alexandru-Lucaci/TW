<?php

    class AnimalsView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Animals.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>