<?php
    require '../config.php';
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Check if it worked</h1>
    <?php
        Database::getConn();
        echo Database::$ok;
    ?>
</body>
</html>