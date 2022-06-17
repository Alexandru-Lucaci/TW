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

function save_animals($username,$animalNames){

    $separator=',';
    $response=null;


    $sql="call salvare_animale(?,?,?,?)";

    $statement=Database::getConnection()->prepare($sql);

    $statement->bindParam(1,$username,PDO::PARAM_STR,100);
    $statement->bindParam(2,$animalNames,PDO::PARAM_STR,2000);
    $statement->bindParam(3,$separator,PDO::PARAM_STR,1);
    $statement->bindParam(4,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

    $statement->execute();      

    return $response;
}
?>

<response>
    <result><?php echo save_animals ($_REQUEST['username'],$_REQUEST['animal_names']); ?></result>
</response>