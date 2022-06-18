<?php

class ContactView extends View{

    function __construct(){
        parent::__construct("views/templates/Contact.phtml");
    }

    function show($content=null){

        $this->data = $content;

        return $this->output();
    }
}

?>