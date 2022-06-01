<?php

class AnimalsView extends View{

    function __construct(){
        parent::__construct("views/templates/Animals.phtml");
    }

    function show($content){

        $this->data = $content;

        return $this->output();
    }
}

?>