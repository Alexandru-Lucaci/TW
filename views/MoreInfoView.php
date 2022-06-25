<?php

    class MoreInfoView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/MoreInfo.phtml');
        }
        function show($content = null){
            $this->data = $content;
            return $this->output();
        }
    }

 
?>