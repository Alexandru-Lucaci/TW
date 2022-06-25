<?php

    class MoreInfoController extends Controller{

        public function show(){
            // echo "should show";
            $continut = null;
            
            
            if(isset($_POST['function']) && !empty($_POST['function'])){
                $className = new $this->model;
                $method = $_POST['function'];
                if(method_exists($className, $method)){
                    $continut = $className->$method();
                }
                else{
                    $continut = 'Metoda e problema';
                }

            }
            $presentation = $this->view->show();

            echo $presentation;
        }
    }

?>