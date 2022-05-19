<?php

class ContactController extends Controller{

    public function show(){
        //$usersInfo=$this->model->getData();

        $presentation=$this->view->show();

        echo $presentation;
    }
}

?>