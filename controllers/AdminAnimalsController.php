<?php

    class AdminAnimalsController extends Controller{

        public function show(){
            // echo "should show";
            $presentation = $this->view->show();
            
            echo $presentation;
        }
        public function update(){
            $rezult = null;

            if(isset($_POST['function']) && !empty($_POST['function'])){
                $className = new $this->model;
                $functie = $_POST['function'];

                if(method_exists($className,$functie)){
                    $rezult = $className->$functie();
                    if($rezult == 'Campuri  necompletate')
                        echo $rezult;
                }
                else
                {
                    echo ' metoda nu prea exista (inca)';
                    return 'metoda nu exista';
                }

            }
            else
            {
                echo 'apel gresit';
                return 'apel gresit';
            }

            $presentation = $this->view->show($rezult);
            echo $presentation;
        }
    }
    

?>