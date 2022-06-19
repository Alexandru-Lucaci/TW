<?php

class SettingsModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Obtine informatii despre utilizatorul curent
     * Returneaza un tablou cu informatiile respective daca totul merge bine sau un mesaj de eroare altfel
     */
    public function get_user_information(){
        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){                        
            return 'Eroare!Numele de utilizator nu este setat sau este gol';
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
            return 'Informatia obtinuta este nula';
        }

        if(count($results)==0){
            return 'Nu exista informatii despre acest utilizator in baza de date(poate a fost sters contul intre timp)';
        }
        if(count($results)>1){
            return 'Acest nume de utilizator nu ar trebui sa poata sa aiba atatea informatii asociate :( ';
        }

        return array('user_info'=>$results[0]);
    }

    /**
     * Schimba informatiile despre un camp al acestui utilizator
     * Primeste prin POST numele campului si valoare noua a campului
     * Returneaza 'OK' daca totul merge bine sau un mesaj de eroare altfel
     */
    public function change_account_information(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Nu este autentificat intr-un cont";
        }

        if(!(isset($_POST["field_name"])&&!empty($_POST["field_name"]))){
            return "Numele campului nu este setat sau este gol";
        }

        if(!(isset($_POST["field_value"])&&!empty($_POST["field_value"]))){
            return "Valoarea noua pentru camp nu este setat sau este goala";
        }

        $result=null;

        $username=htmlentities($_SESSION['username']);
        $fieldName=htmlentities($_POST['field_name']);
        $fieldValue=htmlentities($_POST['field_value']);

        $sql="call schimbare_camp_utilizator(?,?,?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$fieldName,PDO::PARAM_STR,50);
        $statement->bindParam(3,$fieldValue,PDO::PARAM_STR,100);
        $statement->bindParam(4,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,200);

        $statement->execute();

        if(is_null($result)){
            return "Ceva sa intamplat cu executia sql";
        }
        else if($result!="OK"){
            return $result;
        }
        
        if($fieldName=="nume_utilizator"){
            $_SESSION['username']=$fieldValue;
        }

        return "OK";
    }

    /**
     * Sterge datele salvate in sesiune la apasarea butonului de deconectare
     */
    private static function unset_session(){
        unset($_SESSION['admin']);
        unset($_SESSION['username']);
    }

    /**
     * Deconecteaza utilizatorul curent
     */
    public function logout(){

        if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1){
            $_SESSION['loggedIn']=0;
            
            $this->unset_session();

            return "OK";
        }

        return "Nu este autentificat pe un cont";

    }

    /**
     * Sterge contul care este autentificat in momentul acesta
     * Datele despre cont se extrag din sesiune
     */
    public function delete_account(){

        if(!(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1)){
            return "Trebuie sa te autentifici pe contul pe care doriti sa il stergeti";
        }


        if(!(isset($_SESSION['username'])&&!empty($_SESSION['username']))){
            return "Numele de utilizator nu este setat sau este gol";
        }

        $username=htmlentities($_SESSION['username']);
        $result=null;

        $sql="call sterge_utilizator(?,?)";

        $statement=Database::getConnection()->prepare($sql);

        $statement->bindParam(1,$username,PDO::PARAM_STR,100);
        $statement->bindParam(2,$result,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();

        if(is_null($result)){
            return "Eroare neasteptata cu executia sql";
        }
        else if($result!='OK'){
            return $result;
        }

        $_SESSION['loggedIn']=0;

        $this->unset_session();

        return "OK";
    }
}

?>