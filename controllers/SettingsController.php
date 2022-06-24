<?php

    class SettingsController extends Controller{

        public function show($rezultat=NULL){
            // echo "should show";

            // i have to get all user information ->function in SettingsModel
            $class = new $this->model; //SettingsModel
            /// i
            // echo 'heere';
            $function = 'getInformation';
            $rezultat = $class->$function();
            // echo "<br>";
            // var_dump($rezultat);
            $presentation = $this->view->show($rezultat);
            $_SESSION['login']=1;
            echo $presentation;
        }

        public function update(){
            $class = new $this->model;
            echo 'heere';
            // $function = 'getInformation';
            // $informationThatINeed = $class->$function();



            // $content=$informationThatINeed;
            // // $content['function_response'] = $informationThatINeed;
            // $presentation = $this->view->show($content);
            // echo $presentation;

            if(isset($_POST['function'])&& !(empty($_POST['function'])))
            {
                $function = $_POST['function'];
                if(method_exists($class,$function)){
                    //exista 
                    echo 'exista';
                    $result = $class->$function();
                }
                else
                {
                    //problema metoda nu exista
                    $result ="Metoda nu exista ";
                }
            }
            else{
                //not initialized
                $result=('n-am ce metoda sa aplic');
            }

            // echo 'heeeere';
            if($_SESSION['login']!=0)
            {              
                $content = $class->getInformation();
                // echo '<br> '. $content;
                // echo '<br> '. $result;
                
                if(!is_array($content)){
                    $content = $result;
                }
                else
                {
                    $content['function_response'] = $result;
                }
                $presentation = $this->view->show($content);
                echo $presentation;
            }
            else
            {
                $home = new HomeView();
                $presentation = $home->show();
                echo $presentation;
            }

        }
        
    }

?>