<?php

class HelpView extends View{

    function __construct(){
        parent::__construct("views/templates/Help.phtml");
    }

    function show($usersInfo=null){

        $this->data = $usersInfo;

        return $this->output();
    }
}

?>