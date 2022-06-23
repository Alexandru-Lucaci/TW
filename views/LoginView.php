<?php

    class LoginView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Login.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>