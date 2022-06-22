<?php

    session_start();
    define('HOME', dirname(__FILE__));
    define('DS', DIRECTORY_SEPARATOR);


    // echo HOME;
    // echo DS;
    // echo $_SERVER['PHP_SELF']; -- dirname face sa fie slash-urile cum trebuie

  
    // echo 'here';
    require_once HOME . DS . 'config.php';
    include HOME .DS . 'utils' . DS . 'db.php';
    // echo 'here';
    require_once HOME . DS . 'utils'. DS  . 'autoload.php';
    // echo 'hereeeeeee';
    // echo '<br>'. HOME . DS . 'utils'. DS  . 'boot.php';
    require_once HOME . DS . 'utils'. DS  . 'boot.php';
    // echo 'here';
?>
