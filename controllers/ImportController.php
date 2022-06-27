<?php

    class ImportController extends Controller{

        public function show(){
            $continut = null;
            // echo "should show";
            $class = new $this->model;
            $functie = 'get_saved_animals';

            if(method_exists($class, $functie)){
                $continut = $class->$functie();
            }
            else
            {
                echo ' ceva nu e ce trebuie';
            }



            $presentation = $this->view->show($continut);
            
            echo $presentation;
        }

        public function update(){
            $rezultat = null;
        if(!empty($_POST['function'])){
                $functie = $_POST['function'];
                $class = new $this->model;
                $continut = $class->$functie();

                if($continut == 'ok'){
                    echo 'Am introdus animalul in baza de date';
                }
        }else{
            $rezultat ='something is not good';
            // echo $rezultat;
            return $rezultat;
        }
        // var_dump($continut);
        $presentation = $this->view->show($continut);
        echo $presentation;

        }
    }

?>