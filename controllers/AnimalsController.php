<?php

class AnimalsController extends Controller{

    public function update(){
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

    public function show($content=null){

        $presentation=$this->view->show($content);

        echo $presentation;
    }

    public function download(){

        //get array of animals
        $response=null;
        $content=null;
        $fileFormat=null;

        $this->model->download($response,$content,$fileFormat);
        if($response=="OK"){

            if($fileFormat=="xml"){
                header('Content-type: text/xml');
                header('Content-Disposition: attachment; filename="Animals.xml"');

                echo $content;
            }
            else if($fileFormat=="json"){
                header('Content-type: text/json');
                header('Content-Disposition: attachment; filename="Animals.json"');

                echo $content;
            }
        }
        else{
            $this->show($response);
        }
    }
}

?>