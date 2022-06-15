<?php

class SavingsView extends View{

    function __construct(){
        parent::__construct("views/templates/Savings.phtml");
    }

    function show(){

        return $this->output();
    }
}

?>