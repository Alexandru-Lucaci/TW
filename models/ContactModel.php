<?php 
    // require '../utils/mail.php';
    require HOME . DS . 'utils' . DS . 'mail.php';
    class ContactModel extends Model{
        
        public function __construct()
        {
            // echo "should show";
            parent::__construct();
        
        }
        public function sentEmail(){
            /// get the datas
        //    echo 'heeer i am';
            if(isset($_POST['submit'])){
                echo 'heeer i am';
                $msg =''; // mesaj de cofirmare
                $msgClass = '';// clasa in care va fi pus mesajul
                $email = $_POST['email'];
                $name= $_POST['name'];
                $number = $_POST['telephone'];
                $message = $_POST['message'];
                $ipAddr = $_SERVER['REMOTE_ADDR'];
                if(!empty($email) && !empty($name) && !empty($number) && !empty($message)){
                    if(filter_var($email,FILTER_VALIDATE_EMAIL)== false){
                        //failed - email not good
                        
                        $msg = 'Not a good email';
                        $msgClass = 'failed';
                        echo $msgClass . ' '. $msg;

                    }else{
                        //email is good
                        // mail that i want to sent
                        date_default_timezone_set('Europe/Bucharest');
                        $data = date('d/m/Y h:i:s a');
                        $subject = 'ContactRequestForm '. $name . ' ' . $data;
                        $body= '<h2> Contact Request </h2>
                        <h4>Name</h4><p>'. $name .'</p>
                        <h4>email</h4><p>'. $email .'</p>
                        <h4>email</h4><p>'. $number .'</p>
                        <h4>Message</h4><p>'. $message .'</p>
                        <h4>IpAddress</h4><p>'. $ipAddr.'</p>
                        ';
                        
                        if(sendMail($subject,$body)){
                            // s-a trimis
                            $msg = 'Mesajul a fost trimis';
                

                            $msgClass = 'succes';
                            echo $msgClass . ' '. $msg;

                        }
                        else{
                            
                            $msg ='Something wrong, sorry';
                            $msgClass='failed';
                            echo $msgClass . ' '. $msg;

                        }
                    }
                }
                else
                {
                    // failed => un camp nu este completat :( 
                        $msg = 'Not enough data';
                        $msgClass ='failed';
                        echo $msgClass . ' '. $msg;

                }

            }
            return null ;
        }
    }
?>