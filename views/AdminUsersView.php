<?php

    class AdminUsersView extends View{

        function __construct(){
            parent::__construct("views/templates/AdminUsers.phtml");
        }

        function show($content=null){

            $this->data = $content;

            return $this->output();
        }
    }

?>