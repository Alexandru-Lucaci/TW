<?php

class SavingsView extends View{

    function __construct(){
        parent::__construct("views/templates/Savings.phtml");
    }

    function show($content=null){

        $this->data=$content;

        return $this->output();
    }
}

?>