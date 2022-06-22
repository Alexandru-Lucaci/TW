<?php
    // require '../config.php';
    class Database {
        private static $connection;
        public static $ok;
        // singleton class -needed for connection
        public static function getConn(){

            self::$connection = new PDO(HOST, USNAME, USPASS ,OPTIONS);
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
