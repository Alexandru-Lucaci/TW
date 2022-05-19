<?php

class LoginView extends View{

    function __construct(){
        parent::__construct("views/templates/Login.phtml");
    }

    function show($usersInfo=null){

        $this->data = $usersInfo;

        return $this->output();
    }
}

?>