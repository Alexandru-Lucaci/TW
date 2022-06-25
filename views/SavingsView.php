<?php

    class SavingsView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Savings.phtml');
        }
        function show($continut =null ){
            $this->data = $continut;
            return $this->output();
        }
    }

 
?>