<?php

class ImportView extends View{

    function __construct(){
        parent::__construct("views/templates/Import.phtml");
    }

    function show($content=null){

        $this->data=$content;

        return $this->output();
    }
}

?>