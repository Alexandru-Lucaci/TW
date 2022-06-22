<?php

    class HomeController extends Controller{

        public function show(){
            $presentation = $this->view->show();
            echo $presentation;
        }
    }

?>