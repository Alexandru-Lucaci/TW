<?php

    class AdminUsersView extends View{
        public function __construct()
        {
            // echo "should show";
            parent::__construct('views/templates/AdminUsers.phtml');
        }
        function show($dates = null){
            $this->data = $dates;

            return $this->output();
        }
    }

 
?>