<?php

class ContactView extends View{

    function __construct(){
        parent::__construct("views/templates/Contact.phtml");
    }

    function show($usersInfo=null){

        $this->data = $usersInfo;

        return $this->output();
    }
}

?>