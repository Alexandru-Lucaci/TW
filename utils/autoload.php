<?php

    function autoload($clasa){
        if(file_exists(HOME . DS . 'utils' . DS . strtolower($clasa) . '.php'))
        {
            require_once HOME . DS . 'utils' . DS . strtolower($clasa) . '.php';
        }
        if(file_exists(HOME . DS . 'models' . DS . strtolower($clasa) . '.php'))
        {
            require_once HOME . DS . 'models' . DS . strtolower($clasa) . '.php';
        }
        if(file_exists(HOME . DS . 'controllers' . DS . strtolower($clasa) . '.php'))
        {
            require_once HOME . DS . 'controllers' . DS . strtolower($clasa) . '.php';
        }
        if(file_exists(HOME . DS . 'views' . DS . strtolower($clasa) . '.php'))
        {
            require_once HOME . DS . 'views' . DS . strtolower($clasa) . '.php';
        }
    }
    spl_autoload_register('autoload');

?>