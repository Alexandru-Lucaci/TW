<?php

    class ContactView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Contact.phtml');
        }
        function show(){
            return $this->output();
        }

        
    }

 
?>