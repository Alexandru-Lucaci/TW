<?php
    class Database{
        //CONN TW_BD_ORACLE/TW_BD_ORACLE

        private static $connection=NULL;
        
        public static function getConnection(){
            if(is_null(self::$connection)){

                include ("config.php");

                $options = [
                    // erorile sunt raportate ca exceptii de tip PDOException
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    // rezultatele vor fi disponibile in tablouri asociative
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // conexiunea e persistenta
                    PDO::ATTR_PERSISTENT 		 => TRUE
                ];

                try{
                    self::$connection=new PDO($database,$username,$password,$options);
                }
                catch(PDOException $e){
                    echo $e->getMessage();
                }                
            }
            return self::$connection;
        }
    }
?>
