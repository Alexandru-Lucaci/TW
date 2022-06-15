<?php

class AdminModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public static function is_administrator(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Trebuie sa te autentifici pe contul pe care doriti sa il verifica ca este admin";
        }

        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=$_SESSION['username'];

        $sql="select administrator from utilizatori where nume_utilizator=trim(:username)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->execute([
            "username"=>$username
        ]);

        $result=$statement->fetchAll();

        if(is_null($result)){
            return "Eroare la executia sql";
        }

        if(empty($result)){
            return "Utilizatorul cu numele '".$username."' nu exista in baza de date";
        }

        else if(count($result)>1){
            return 'Acest nume de utilizator nu ar trebui sa poata sa aiba atatea informatii asociate :( ';
        }

        return ($result[0]['ADMINISTRATOR']==0)?false:true;
    }    
}

?>