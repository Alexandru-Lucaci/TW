<?php 
    class AdminUsersModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        

        }

        public function get_user_information(){

            $name = $_POST['username'];
            $comandaSQL = "select * from utilizatori where nume_utilizator=trim(?)";
            $statement = Database::getConn()->prepare($comandaSQL);
            $statement -> bindParam(1,$name, PDO::PARAM_STR,100);
            $statement -> execute();

            $result = $statement->fetchAll();

            if(is_null($result)){
                echo 'fara info';
                return 'fara info';
            }

            $result['type'] = 'user_info';
            return $result;

        }
    
    }
?>