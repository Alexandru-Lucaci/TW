<?php

    class LoginController extends Controller{

        public function show(){
            // echo "should show";
            $presentation = $this->view->show();
            
            echo $presentation;
        }
        public function doRegOrLog(){
            if(filter_has_var(INPUT_POST,'submit')){
                if(!empty($_POST['function'])){
                    $functie = $_POST['function'];
                    $class = new $this->model;
                    $continut = $class->$functie();

                }
            }else{
                echo 'something is not good';
            }

            $presentation= $this->view->show();
            echo $presentation;
            
        }
    }

?>