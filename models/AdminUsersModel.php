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
        public function change_account_information(){
            // toate campurile ar fi obligatorii
            if(!(isset($_POST['username'])&& !empty($_POST['username']))){
                return 'Numele utilizatorului nu este setat';
            }
            if(!(isset($_POST['field_name'])&& !empty($_POST['field_name']))){
                return 'Campul nu este setat';
            }
            if(!(isset($_POST['field_value'])&& !empty($_POST['field_value']))){
                return 'Valoarea noua nu este setat';
            }
            $rezultat = null;


            $username = $_POST['username'];
            $fieldName = $_POST['field_name'];
            $fieldValue =$_POST['field_value'];
            // echo $username . ' ' . $fieldName . ' ' . $fieldValue;
            $comandaSQL = " call schimbare_camp_utilizator(?,?,?,?)";
            $statement = Database::getConn()->prepare($comandaSQL);

            $statement -> bindParam(1, $username , PDO::PARAM_STR, 100);
            $statement -> bindParam(2, $fieldName , PDO::PARAM_STR, 100);
            $statement -> bindParam(3, $fieldValue , PDO::PARAM_STR, 100);
            $statement -> bindParam(4, $rezultat , PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 100);
            $statement -> execute();
            // var_dump($rezultat);
            if(is_null($rezultat)){
                return 'Nu s-a executat modificarea ';
            }
            if($rezultat == 'OK'){
                return $rezultat;
            }
            return "Nu a functionat bine comanda sql " . "$rezultat ";
        }

        public function delete_account(){
            // toate campurile ar fi obligatorii
            if(!(isset($_POST['username'])&& !empty($_POST['username']))){
                return 'Numele utilizatorului nu este setat';
            }

            
        }
    
    }
?>