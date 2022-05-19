<?php

class HomeController extends Controller{

    public function  show(){
        $usersInfo=$this->model->getAllUsersInfo();

        $presentation=$this->view->show($usersInfo);

        echo $presentation;
    }
}

?>