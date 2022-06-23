<?php

    class LoginController extends Controller{

        public function show(){
            // echo "should show";
            $presentation = $this->view->show();
            
            echo $presentation;
        }
        public function doRegOrLog(){
          
            if(!empty($_POST['function'])){
                    $functie = $_POST['function'];
                    $class = new $this->model;
                    $continut = $class->$functie();

                
            }else{
                echo 'something is not good';
            }
            $presentation = new HomeView();
            $prez= $presentation->show();
            echo $prez;
            
        }
    }

?>