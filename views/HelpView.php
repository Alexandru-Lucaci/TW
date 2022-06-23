<?php

    class HelpView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Help.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>