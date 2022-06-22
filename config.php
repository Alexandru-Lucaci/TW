<?php

    // dates needed for connection to the database
    // 'oci:dbname=localhost/XE', 'TW_BD_ORACLE', 'TW_BD_ORACLE'
    define('HOST','oci:dbname=localhost/XE');
    define('USNAME','TW_BD_ORACLE');
    define('USPASS','TW_BD_ORACLE');
    define('OPTIONS' , array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true));

?>