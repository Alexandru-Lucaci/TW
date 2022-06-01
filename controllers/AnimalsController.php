<?php

class AnimalsController extends Controller{

    public function show(){
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

        $presentation=$this->view->show($content);

        echo $presentation;
    }
}

?>