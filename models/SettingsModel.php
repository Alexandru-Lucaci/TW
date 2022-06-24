<?php 
    // session_start();
    class SettingsModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function getInformation(){
            if(isset($_SESSION['name'])&& !empty($_SESSION['name'])){
                //it's ok
                // echo 'ok';
                $user = htmlentities(($_SESSION['name']));
                // echo $_SESSION['name'];
                $comandaSql = 'Select * from utilizatori where nume_utilizator= (?)';

                $statement = Database::getConn()->prepare($comandaSql);
                $statement->bindParam(1,$user,PDO::PARAM_STR,100);
                $statement->execute();
                // associative array
                $output=$statement->fetchAll();
                
                // if(!empty($output)){
                return array('user_info' => $output[0]);
                // }
                // else
                // {
                //     // cam imposibil => n-am gasit
                //     return 'failed Something is wrong, i shouldn\'t be hereeeeee';
                // }

            }else{
                /// caz in care nu voi fi niciodata - sa fiu considerat logat dar sa nu stiu numele de utilizator
                return 'failed Something is wrong, i shouldn\'t be here';

            }
        }
        public function changeInfo(){
            if(isset($_SESSION['name'])&& !empty($_SESSION['name']))
                echo '<br> i got heeere <br>yess';
                $changeValue = null;
                if(isset($_POST['field_value']))
                {
                    echo '<br> valoarea este setata';
                }
                else{
                    echo '<br> valaorea nu este setata';
                }
        }
    
    }
?>