<?php 
    class MoreInfoModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function getAnimal(){
            if(isset($_POST['animal_name']) && !empty($_POST['animal_name'])){
                $animal = $_POST['animal_name'];

                $comandaSQL = "select * from animale where lower(denumire_populara) = lower(?)";
                $statement = Database::getConn()->prepare($comandaSQL);

                $statement->bindParam(1,$animal,PDO::PARAM_STR,100);
                $statement->execute();

                $output = $statement->fetchAll();
                // sigur am un animal
                // var_dump($output);

                return $output;
            }else{
                return 'Nu am informațiile necesare despre animal';

            }
        }
    
    }
?>