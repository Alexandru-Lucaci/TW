<?php 
    class ContactModel extends Model{
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function sentEmail(){
            /// get the datas
            $msg =''; // mesaj de cofirmare
            $msgClass = '';// clasa in care va fi pus mesajul
            if(filter_has_var(INPUT_POST,'submit')){
                $email = $_POST['email'];
                $name= $_POST['name'];
                $number = $_POST['telephone'];
                $message = $_POST['message'];
                $ipAddr = $_SERVER['REMOTE_ADDR'];
                if(!empty($email) && !empty($name) && !empty($number) && !empty($message)){
                    if(filter_var($email,FILTER_VALIDATE_EMAIL)== false)
                }

            }
        }
    }
?>