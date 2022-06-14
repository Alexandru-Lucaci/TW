<?php

    class SettingsView extends View{

        function __construct(){
            parent::__construct("views/templates/Settings.phtml");
        }

        function show($usersInfo){

            $this->data = $usersInfo;

            return $this->output();
        }
    }

?>