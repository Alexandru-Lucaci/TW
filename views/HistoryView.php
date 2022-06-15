<?php

class HistoryView extends View{

    function __construct(){
        parent::__construct("views/templates/History.phtml");
    }

    function show(){

        return $this->output();
    }
}

?>