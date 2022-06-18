<?php

class ContactController extends Controller{

    public function show(){
        //$usersInfo=$this->model->getData();

        $presentation=$this->view->show();

        echo $presentation;
    }

    public function send_form(){
        
        $response=$this->model->save_contact_form();

        $presentation=$this->view->show($response);

        echo $presentation;
    }
}

?>