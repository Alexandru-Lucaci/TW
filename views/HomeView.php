<?php

class HomeView extends View{

    function __construct(){
        parent::__construct("views/templates/Home.phtml");
    }

    function show(){

        return $this->output();
    }
}

?>