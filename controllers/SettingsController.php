<?php

    class SettingsController extends Controller{

        public function show(){
            // echo "should show";

            // i have to get all user information ->function in SettingsModel
            $class = new $this->model; //SettingsModel
            /// i
            $function = 'getInformation';
            $rezultat = $class->$function();
            echo $rezultat;
            $presentation = $this->view->show();
            
            // echo $presentation;
        }
    }

?>