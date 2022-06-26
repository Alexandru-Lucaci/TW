<?php

    class ImportView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/Import.phtml');
        }
        function show($info){
            // echo 'here settings views';

            $this->data = $info;
            // var_dump($this->data) ;
            return $this->output();
        }
    }

 
?>