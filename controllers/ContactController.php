<?php

    class ContactController extends Controller{

        public function show(){
            // echo "should show";
            $presentation = $this->view->show();
            
            echo $presentation;
        }
        public function send_form(){
            echo 'heeere controller -> before <br>';
            $response = $this->model->sentEmail();
            echo 'heeere controller';
            // $presentation = $this->show($response);
            'heeeere x2  controller';
        }
    }

?>