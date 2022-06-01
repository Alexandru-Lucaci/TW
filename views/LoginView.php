<?php

class LoginView extends View{

    function __construct(){
        parent::__construct("views/templates/Login.phtml");
    }

    function show($content){

        $this->data = $content;

        return $this->output();
    }
}

?>