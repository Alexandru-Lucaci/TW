<?php

class AnimalsView extends View{

    function __construct(){
        parent::__construct("views/templates/Animals.phtml");
    }

    function show($usersInfo=null){

        $this->data = $usersInfo;

        return $this->output();
    }
}

?>