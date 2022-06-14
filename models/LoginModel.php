<?php

class LoginModel extends Model{

    function __construct(){
        parent::__construct();
    }
    
    public function register(){

        if(!(isset($_POST['username'])&&!empty($_POST['username']))){
            return 'Numele de utilizator nu este setat sau este gol';
        }

        if(!(isset($_POST['password'])&&!empty($_POST['password']))){
            return 'Parola nu este setata sau este goala';
        }

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==0)){
            return 'Trebuie sa te deloghezi de la contul curent pentru a putea crea alt cont';
        }

        $result=null;

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
        $statement->bindParam(5,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,200);

        $statement->execute();

        if(is_null($result)){
            return 'Eroare neasteptata dupa rularea comenzii sql';
        }
        else if($result!='OK'){
            return $result;
        }

        $_SESSION['loggedIn']=1;

        $_SESSION['username']=$username;

        return 'Succes!';
    }

    public function login(){

        if(!(isset($_POST['username'])&&!empty($_POST['username']))){
            return "Numele de utilizator pentru autentificare nu este setat sau este gol";
        }

        if(!(isset($_POST['password'])&&!empty($_POST['password']))){
            return "Parola nu este setata sau este goala";
        }

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==0)){
            return 'Trebuie sa te deloghezi de la contul curent pentru a putea crea alt cont';
        }

        $username=htmlentities($_POST['username']);
        $password=htmlentities($_POST['password']);

        $sql="call autentificare(?,?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$password,PDO::PARAM_STR,100);
        $statement->bindParam(3,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);
        
        $statement->execute();

        if(is_null($result)){
            return 'Eroare neasteptata dupa rularea comenzii sql';
        }
        else if($result!='OK'){
            return $result;
        }

        $_SESSION['loggedIn']=1;

        $_SESSION['username']=$username;

        return "Succes!";
    }
}

?>