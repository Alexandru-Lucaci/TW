<?php

class HistoryController extends Controller{

    public function  show(){

        $presentation=$this->view->show();

        echo $presentation;
    }
}

?>