<?php

    // this will be a parent class 

    abstract class Controller{
        // controllerul ar trebui să cunoască atât view-ul cât și modelul
        public $model;
        public $view;
        public $thisClassName = get_class($this);

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
    <?php str_replace('Alex', 'Alexandru', 'Alex lucaci');?>
</body>
</html>