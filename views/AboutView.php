<?php

class AboutView extends View{

    function __construct(){
        parent::__construct("views/templates/About.phtml");
    }

    function show($usersInfo=null){

        $this->data = $usersInfo;

        return $this->output();
    }
}

?>