<?php

    class AnimalsController extends Controller{

        public function show($continut){
            // echo "should show";
            $presentation = $this->view->show($continut);
            
            echo $presentation;
        }

        public function update(){
            $rezultat = null;
            if(!empty($_POST['function'])){
                $functie = $_POST['function'];
                $class = new $this->model;
                $continut = $class->$functie();

            
        }else{
            $rezultat ='something is not good';
            echo $rezultat;
            return $rezultat;
        }
        $presentation = $this->view->show($continut);
        echo $presentation;

        }
    }

?>