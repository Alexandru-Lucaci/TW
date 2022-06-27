<?php
    // include "../../utils/db.php";

    
    function exist_username($username){
        $comandaSQL = "select count(*) from utilizatori where nume_utilizator = ('?')";
        $statement = Database::getConn()->prepare($comandaSQL);
        $statement -> bindParam(1, $username, PDO::PARAM_STR, 100);
        $statement ->execute();
        $rezultat = $statement -> fetchAll();

        if ($rezultat[0]['COUNT(*)'] != 0 ){
            return 1;
        }
        
        return 0;
    }

?>
<response>
    <result><?php echo exist_username($_REQUEST['username']);?></result>
</response>