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
            self::$connection = new PDO('oci.dbname=localhost/XE', 'TW_BD_ORACLE', 'TW_BD_ORACLE');
            if(!empty($connection)){
                $ok = ' working';
                return self::$connection;
            }


            else{
                echo 'Something went wrong. BD-CONNECTION TO THE DATABASE';
                $ok='notworking';
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