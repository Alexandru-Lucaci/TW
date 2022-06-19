<?php

class ContactModel extends Model{

    function __construct(){
        parent::__construct();
    }

    /**
     * Salveaza un formular de contact in baza de date
     * Primeste prin POST numele,email,numar de telefon si mesajul
     */
    public function save_contact_form(){
        if(!(isset($_POST['name']))&&!empty($_POST['name'])){
            return 'Trebuie sa oferiti un nume pentru cine a trimis acest formular de contact';
        }

        if(!(isset($_POST['email']))&&!empty($_POST['email'])){
            return 'Trebuie sa oferiti un email';
        }

        if(!(isset($_POST['message']))&&!empty($_POST['message'])){
            return 'Trebuie sa scrieti un mesaj pentru administratori paginii';
        }

        $name=htmlentities($_POST['name']);
        if(strlen($name)>100){
            return 'Numele este prea lung(limita de 100 de caractere)';
        }

        $email=htmlentities($_POST['email']);
        if(strlen($email)>100){
            return 'Email este prea lung(limita de 100 de caractere)';
        }

        $telephone=htmlentities($_POST['telephone']);
        if(strlen($telephone)>100){
            return 'Numarul de telefon este prea lung(limita de 100 de caractere)';
        }

        $message=htmlentities($_POST['message']);
        if(strlen($message)>3500){
            return 'Mesajul este prea lung(limita de 3500 de caractere)';
        }

        $response=null;

        $sql='call adaugare_formular_contact(?,?,?,?,?)';

        $statement=$this->connection->prepare($sql);

        $statement->bindParam(1,$name,PDO::PARAM_STR,100);
        $statement->bindParam(2,$email,PDO::PARAM_STR,100);
        $statement->bindParam(3,$telephone,PDO::PARAM_STR,100);
        $statement->bindParam(4,$message,PDO::PARAM_STR,100);
        $statement->bindParam(5,$response,PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT,100);

        $statement->execute();

        return $response;
    }
    
}

?>