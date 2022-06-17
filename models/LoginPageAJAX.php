<?php

header('Content-type: text/xml');

//TODO
//find out how to include th Database connection so that it wokrs properly....
//obviosuly,autoload.php and include() don't work :(
class Database{
    //CONN TW_BD_ORACLE/TW_BD_ORACLE

    private static $connection=NULL;
    
    public static function getConnection(){
        if(is_null(self::$connection)){

            $database='oci:dbname=localhost/XE';
            $username='TW_BD_ORACLE';
            $password='TW_BD_ORACLE';

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

function exists_username($username){

    $sql='select count(*) from utilizatori where nume_utilizator=:username';

    $statement=Database::getConnection()->prepare($sql);

    $statement->execute([
        "username"=>$username
    ]);

    $result=$statement->fetchAll();

    return ($result[0]['COUNT(*)']==0)?0:1;
}

?>

<response>
    <result><?php echo exists_username ($_REQUEST['username']); ?></result>
</response>