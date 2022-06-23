<?php

    class AboutView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/About.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>