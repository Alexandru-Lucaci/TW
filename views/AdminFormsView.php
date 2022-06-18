<?php

class AdminFormsView extends View{

    function __construct(){
        parent::__construct("views/templates/AdminForms.phtml");
    }

    function show($content=null){

        $this->data=$content;

        return $this->output();
    }
}

?>