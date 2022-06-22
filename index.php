<?php

    session_start();
    define('HOME', dirname(__FILE__));
    define('DS', DIRECTORY_SEPARATOR);


    // echo HOME;
    // echo DS;
    // echo $_SERVER['PHP_SELF']; -- dirname face sa fie slash-urile cum trebuie


    require_once HOME . DS . 'config.php';
    
?>
