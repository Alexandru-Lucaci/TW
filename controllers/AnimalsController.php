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
            // echo $rezultat;
            return $rezultat;
        }
        // var_dump($continut);
        $presentation = $this->view->show($continut);
        echo $presentation;

        }
        public function download(){
           $result = $this->model->download();
        //    var_dump($result);
            foreach ($result[0] as $key => $value) {
                echo "cheie -$key-  = value -$value- <br>";
            }
        }
    }

?>