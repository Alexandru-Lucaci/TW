<?php

class AdminFormsController extends Controller{

    public function show(){

        $content=$this->model->get_forms();

        $presentation=$this->view->show($content);

        echo $presentation;
    }

    public function update(){

        //get data from model
        $content=null;
        if(isset($_POST['function'])&&!empty($_POST['function'])){
        
            $className = new $this->model;
            $method = $_POST['function'];
            if(method_exists($className,$method)){
                $content=$className->$method();
            }
            else{
                $content='Method called doesn\'t exist';
            }
        }
        else{
            $content="Unknown method to call";
        }

        //get presentation
        $presentation=$this->view->show($content);

        //show presentation
        echo $presentation;
    }
}

?>