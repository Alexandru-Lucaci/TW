<?php

    // this will be a parent class 

    abstract class Controller{
        // controllerul ar trebui să cunoască atât view-ul cât și modelul
        public $model;
        public $view;

        public function __construct()
        {
            
        }
    }

?>