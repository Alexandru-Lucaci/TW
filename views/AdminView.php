<?php

class AdminView extends View{

    function __construct(){
        parent::__construct("views/templates/Admin.phtml");
    }

    function show(){
        return $this->output();
    }
}

?>