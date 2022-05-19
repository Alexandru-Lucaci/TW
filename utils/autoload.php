<?php
function autoload($class)
    {
        if (file_exists(HOME . DS . 'utils' . DS . strtolower($class) . '.php'))
        {
            require_once HOME . DS . 'utils' . DS . strtolower($class) . '.php';
        }
        else if (file_exists(HOME . DS . 'models' . DS . strtolower($class) . '.php'))
        {
            require_once HOME . DS . 'models' . DS . strtolower($class) . '.php';
        }
        else if (file_exists(HOME . DS . 'controllers' . DS . strtolower($class) . '.php'))
        {
            require_once HOME . DS . 'controllers'  . DS . strtolower($class) . '.php';
        }
        else if (file_exists(HOME . DS . 'views' . DS . strtolower($class) . '.php'))
        {
            require_once HOME . DS . 'views'  . DS . strtolower($class) . '.php';
        }        
    }
spl_autoload_register('autoload');