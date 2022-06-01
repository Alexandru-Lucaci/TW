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

    public function logout(){

        $result=null;

        if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1){
            $_SESSION['loggedIn']=0;
            unset($_SESSION['username']);

            $result="Logout successful";
        }
        else{
            $result="Not logged into an account";
        }

        return $result;
    }

    public function delete_account(){

        $result=null;

        if(isset($_SESSION['username'])&&!empty($_SESSION['username'])){

            if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1){
                $username=htmlentities($_SESSION['username']);
                $result=null;

                $sql="call sterge_utilizator(?,?)";

                $statement=Database::getConnection()->prepare($sql);

                $statement->bindParam(1,$username,PDO::PARAM_STR,100);
                $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

                $statement->execute();

                if(is_null($result)){
                    $result="Unexpected error after sql statement";
                }
                else if($result=='OK'){
                    $_SESSION['loggedIn']=0;
                }

            }
            else{
                $result="You need to log into the account you wish to delete";
            }
        }
        else{
            $result="Unexpected error occured";
        }

        return $result;
    }

    public function change_account_information(){
        $result=null;

        if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1&&isset($_SESSION['username'])&&$_SESSION['username']){
            if(isset($_POST["field_name"])&&!empty($_POST["field_name"])&&isset($_POST["field_value"])&&!empty($_POST["field_value"])){
                $username=htmlentities($_SESSION['username']);
                $fieldName=htmlentities($_POST['field_name']);
                $fieldValue=htmlentities($_POST['field_value']);

                $sql="call schimbare_camp_utilizator(?,?,?,?)";

                $statement=Database::getConnection()->prepare($sql);

                $statement->bindParam(1,$username,PDO::PARAM_STR,100);
                $statement->bindParam(2,$fieldName,PDO::PARAM_STR,50);
                $statement->bindParam(3,$fieldValue,PDO::PARAM_STR,50);
                $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,200);

                $statement->execute();

                if(is_null($result)){
                    $result="Something happened when executing the query";
                }
                else if($result=="OK"&&$fieldName=="nume_utilizator"){
                    $_SESSION['username']=$fieldValue;
                    //TODO
                    //make information changed to be updated properly
                }
            }
            else{
                $result="Value for fields namas or values is not set or empty";
            }
        }
        else{
            $result="You must be logged in to change account settings and have the username set and not empty";
        }

        return $result;
    }
}

?>