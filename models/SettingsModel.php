<?php

class SettingsModel extends Model{

    function __construct(){
        parent::__construct();
    }

    public function get_user_information(){
        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){                        
            return 'Error!The username is not set or is empty!';
        }

        $username=htmlentities($_SESSION['username']);

        //get user info
        $sql="select * 
        from utilizatori
        where nume_utilizator=trim(?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);

        $statement->execute();

        $results=$statement->fetchAll();

        if(is_null($results)){
            return 'The information obtained is null';
        }

        if(count($results)==0){
            return "No information about the user with this account exists";
        }
        if(count($results)>1){
            return "This username should not be able to get more than 1 recording :(";
        }

        return array('user_info'=>$results[0]);
    }

    public function change_account_information(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Not logged into an account";
        }

        if(!(isset($_POST["field_name"])&&!empty($_POST["field_name"]))){
            return "The field name is unset or empty";
        }

        if(!(isset($_POST["field_value"])&&!empty($_POST["field_value"]))){
            return "The field value is unset or empty";
        }

        $result=null;

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
            return "Something happened when executing the query";
        }
        else if($result=="OK"&&$fieldName=="nume_utilizator"){
            $_SESSION['username']=$fieldValue;
            //TODO
            //make information changed to be updated properly
        }

        return $result;
    }

    public function logout(){

        if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1){
            $_SESSION['loggedIn']=0;
            unset($_SESSION['username']);

            return "OK";
        }

        return "Not logged into an account";

    }

    public function delete_account(){

        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){
            return "Unexpected error occured";
        }

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "You need to log into the account you wish to delete";
        }

        $username=htmlentities($_SESSION['username']);
        $result=null;

        $sql="call sterge_utilizator(?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();

        if(is_null($result)){
            return "Unexpected error after sql statement";
        }
        else if($result!='OK'){
            return $result;
        }

        $_SESSION['loggedIn']=0;
        unset($_SESSION['username']);

        return "OK";
    }
}

?>