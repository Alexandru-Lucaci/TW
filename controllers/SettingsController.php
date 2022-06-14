<?php

class SettingsController extends Controller{

    public function show($content=null){

        $className=new $this->model;
        $method='get_user_information';
        if(method_exists($className,$method)){
            $content=$className->$method();
        }
        else{
            $content='Method called doesn\'t exist';
        }

        $presentation=$this->view->show($content);

        echo $presentation;
    }

    public function update(){
        
        //update by calling the respective function
        $content=null;

        $response=null;
        $className = new $this->model;
        if(isset($_POST['function'])&&!empty($_POST['function'])){
        
            $method = $_POST['function'];
            if(method_exists($className,$method)){
                $response=$className->$method();
            }
            else{
                $response='Method called doesn\'t exist';
            }
        }
        else{
            $response='Unknown method to call';
        }

        if(($method=='logout'||$method=='delete_account')&&$response=='OK'){
            header("Location: index.php?load=Home/show");
            exit();
        }
        else{
            $content=$className->get_user_information();
            if(!is_array($content)){
                $content=$response;
            }
            else{
                $content['function_response']=$response;
            }
        }

        //get presentation
        $presentation=$this->view->show($content);

        //show presentation
        echo $presentation;
    }
}

?>