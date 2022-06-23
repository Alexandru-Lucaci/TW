<?php

    class SettingsView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Settings.phtml');
        }
        function show(){
            return $this->output();
        }
    }

 
?>