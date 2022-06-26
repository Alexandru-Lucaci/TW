<?php

    class AdminAnimalsView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/AdminAnimals.phtml');
        }
        function show($dates = null){
            $this->data = $dates;

            return $this->output();
        }
    }

 
?>