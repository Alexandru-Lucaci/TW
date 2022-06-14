<?php

class LoginModel extends Model{

    function __construct(){
        parent::__construct();
    }
    
    public function register(){
            
        $result=null;

        if(isset($_POST['username'])&&!empty($_POST['username'])&&isset($_POST['password'])&&!empty($_POST['password'])){

            if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==0){

                echo 'Not logged in';

                $username=htmlentities($_POST['username']);
                $password=htmlentities($_POST['password']);

                $email=null;
                if(isset($_POST['email'])&&!empty($_POST['email'])){
                    $email=htmlentities($_POST['email']);
                }

                $telephone=null;
                if(isset($_POST['telephone'])&&!empty($_POST['telephone'])){
                    $telephone=htmlentities($_POST['telephone']);
                }
            
                //inregistare -> username,password,email,telephone,response
                $sql="call inregistrare(?,?,?,?,?)";

                $statement=Database::getConnection()->prepare($sql);

                $statement->bindParam(1,$username,PDO::PARAM_STR,100);
                $statement->bindParam(2,$password,PDO::PARAM_STR,100);
                $statement->bindParam(3,$email,PDO::PARAM_STR,100);
                $statement->bindParam(4,$telephone,PDO::PARAM_STR,100);
                $statement->bindParam(5,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

                $statement->execute();

                if(is_null($result)){
                    $result="Unexpected error after sql statement";
                }
                else if($result=='OK'){
                    $_SESSION['loggedIn']=1;
                        
                    $_SESSION['username']=$username;
                }
            }
            else{
                echo 'logged in';
                $result="You need to logout in order to register an account";
            }
        }
        else{
            $result="Unexpected error occured";
        }

        return $result;
    }

    public function login(){

        $result=null;

        if(isset($_POST['username'])&&!empty($_POST['username'])&&isset($_POST['password'])&&!empty($_POST['password'])){

            if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==0){
                $username=htmlentities($_POST['username']);
                $password=htmlentities($_POST['password']);

                $sql="call autentificare(?,?,?)";

                $statement=Database::getConnection()->prepare($sql);

                $statement->bindParam(1,$username,PDO::PARAM_STR,100);
                $statement->bindParam(2,$password,PDO::PARAM_STR,100);
                $statement->bindParam(3,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
                
                $statement->execute();

                if(is_null($result)){
                    $result="Unexpected error after sql statement";
                }
                else if($result=='OK'){
                    $_SESSION['loggedIn']=1;

                    $_SESSION['username']=$username;
                }
            }
            else{
                $result="You need to logout in order to login to another account";
            }
        }
        else{
            $result="Unexpected error occured";
        }

        return $result;
    }
}

?>