<?php
    class Database {
        private static $connection;
        public static $ok;
        // singleton class -needed for connection
        public static function getConn(){
            $options =[
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ];
            self::$connection = new PDO('oci:dbname=localhost/XE', 'TW_BD_ORACLE', 'TW_BD_ORACLE',$options);
            if(!is_null(self::$connection)){
                self::$ok = 'working';
                return self::$connection;
            }
            else{
                echo 'Something went wrong. BD-CONNECTION TO THE DATABASE';
                self::$ok='notworking';
            }
        }

    }

?>
