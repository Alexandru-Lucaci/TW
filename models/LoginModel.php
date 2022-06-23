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
                $copieName = $usname;
                $comandaSQL = "call inregistrare(?,?,?,?,?)";
                $statement=Database::getConn()->prepare($comandaSQL);
                $statement->bindParam(1,$usname,PDO::PARAM_STR,100);
                $statement->bindParam(2,$psswd,PDO::PARAM_STR,100);
                $statement->bindParam(3,$email,PDO::PARAM_STR,100);
                $statement->bindParam(4,$tel,PDO::PARAM_STR,100);
                $statement->bindParam(5,$rez,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                $statement->execute();
               
                if($rez == 'OK'){
                    // ORACLE returneaza prin rez = OK daca totul a decurs cum trebuie 
                    $_SESSION['login']=1;
                    $_SESSION['name'] = $copieName;
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

       public function login(){
        if(filter_has_var(INPUT_POST,'submit'))
        {
           $msg = '';
           $msgClass='';
           if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password'])&& !empty($_POST['password']))
           {
               $ussname =htmlentities( $_POST['username']);
               
               $usspswd = htmlentities($_POST['password']);
               $response = null;
               $comandaSQL = "call autentificare(?,?,?)";
               // usename, password and response

               $statement=Database::getConn()->prepare($comandaSQL);
               $statement->bindParam(1,$ussname,PDO::PARAM_STR,100);
               $statement->bindParam(2,$usspswd,PDO::PARAM_STR,100);
               $statement->bindParam(3,$response,PDO::PARAM_STR,100);
               $statement->execute();

               if(is_null($response)){
                    $msg ='Nu se potrivesc datele ';
                    $msgClass = 'failed';

                    echo $msgClass. ' '. $msg;

               }else{
                    $_SESSION['login'] = 1;
                    $_SESSION['name'] = $ussname;
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