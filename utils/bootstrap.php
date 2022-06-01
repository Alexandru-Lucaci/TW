<?php
    //http://localhost/TW/index.php?load=Codes/list
    /*$controller = "Codes";
    $action = "list";
    $query = null;*/

    //http://localhost/TW/index.php?load=Codes/show/1
    $controller = "Home";
    $action = "show";
    $query = 1;


    if (isset($_GET['load'])){

        $params = array();
        $params = explode("/", $_GET['load']);

        $controller = ucwords($params[0]);

        if (isset($params[1]) && !empty($params[1])){
            $action = $params[1];
        }

        if (isset($params[2]) && !empty($params[2])){
            $query = $params[2];
        }
    }
    else if(isset($_POST['load'])){
        $params = array();
        $params = explode("/", $_POST['load']);

        $controller = ucwords($params[0]);

        if (isset($params[1]) && !empty($params[1])){
            $action = $params[1];
        }

        if (isset($params[2]) && !empty($params[2])){
            $query = $params[2];
        }
    }

    $controller .= 'Controller';
    $load = new $controller();

    if (method_exists($load, $action)){
        $load->$action($query);
    }
    else{
        die('Invalid method. Please check the URL.');
    }
?>