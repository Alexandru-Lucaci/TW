<?php

    // this will be a parent class 

    abstract class Controller{
        // controllerul ar trebui să cunoască atât view-ul cât și modelul
        public $model;
        public $view;
        public $thisClassName ;
        public function __construct()
        {
            $this->thisClassName = get_class($this); // this return the actual class name of the class ( so if a child called it it will get the child class name)
            
            $newClass = str_replace('Controller','Model', $this->thisClassName);
            
            if(class_exists($newClass))
            {
                $this->model = $newClass;
            }
            else
            {
                throw new Exception("model $newClass does not exists.") ;
            }

            // same thing as before
            $newClass = str_replace('Controller','View', $this->thisClassName);
            
            if(class_exists($newClass))
            {
                $this->view = $newClass;
            }
            else
            {
                throw new Exception("View $newClass does not exists.") ;
            }
        }


    }

?>
<?php
    class verificare extends Controller{
        public function getClass(){
            echo $this->thisClassName;
            return $this->thisClassName;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
</head>
<body>
    <?php 
    
    echo str_replace('Alex', 'Alexandru', 'Alex lucaci');
    echo 'hellooo';
    $ver = new verificare();
    echo ' here ';
    echo $ver->thisClassName;
    echo ' here ';
    ?>
</body>
</html>