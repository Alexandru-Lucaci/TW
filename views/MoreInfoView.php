<?php

    class MoreInfoView extends View{

        function __construct(){
            parent::__construct("views/templates/MoreInfo.phtml");
        }

        function show($content=null){

            $this->data = $content;

            return $this->output();
        }
    }

?>