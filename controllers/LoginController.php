<?php

    class LoginController extends Controller{

        public function show(){
            // echo "should show";
            $presentation = $this->view->show();
            
            echo $presentation;
        }
    }

?>