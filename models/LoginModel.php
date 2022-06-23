<?php 
    // session_start();
    class LoginModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
       /// voi avea nevoie de functii pentru login si register


       public function logout(){
            $_SESSION['login'] = 0;
            $_SESSION['name'] = null;
       }
       public function creareCont(){
         if(filter_has_var(INPUT_POST,'submit'))
         {
            $msg = '';
            $msgClass='';
            if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password'])&& !empty($_POST['password']))
            {
                $usname = htmlentities($_POST['username']) ;
                $psswd = htmlentities($_POST['password']);
                $email='';
                $tel ='';
                if(isset($_POST['email']) && !empty($_POST['email'])){
                    //set
                    $email =htmlentities( $_POST['email']);
                }
                $rez=null;
                if(isset($_POST['telephone']) && !empty($_POST['telephone']))
                {
                    $tel = htmlentities($_POST['telephone']);
                }
                $comandaSQL = "call inregistrare(?,?,?,?,?)";
                $statement=Database::getConn()->prepare($comandaSQL);
                $statement->bindParam(1,$usname,PDO::PARAM_STR,100);
                $statement->bindParam(2,$psswd,PDO::PARAM_STR,100);
                $statement->bindParam(3,$email,PDO::PARAM_STR,100);
                $statement->bindParam(4,$tel,PDO::PARAM_STR,100);
                $statement->bindParam(5,$usname,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                $statement->execute();
                if($rez == 'OK'){
                    // ORACLE returneaza prin rez = OK daca totul a decurs cum trebuie 
                    $_SESSION['login']=1;
                    $_SESSION['name'] = $usname;
                    echo $rez;
                    return $rez;
                }
                else
                {
                    // something is not ok
                    echo $rez;
                    return $rez;
                }
                
            }
            else
            {
                // failed, numele sau parola nu au fost puse
                $msg ='Câmpurile username si password sunt obligatorii';
                $msgClass = 'failed';
            }
         }
       }
    }

?>