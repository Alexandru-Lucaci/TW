<?php

class SavingsController extends Controller{

    public function  show(){

        $presentation=$this->view->show();

        echo $presentation;
    }
}

?>