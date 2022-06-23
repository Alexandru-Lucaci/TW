<?php 


    require 'Exception.php';
    require 'PHPMailer.php';
    require 'SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($subject, $mesaj){
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug    = 0;
        $mail->Host         ="smtp.mail.yahoo.com";
        $mail->SMTPAuth     =TRUE;
        $mail->SMTPSecure   ="tls";
        $mail->Port         ="587";
        $mail->Username     ="meowproj@yahoo.com";
        $mail->Password     ="qeizpvlxwoyysuaw";
        $mail->isHTML(true);
        $mail->addAddress("meowproj@yahoo.com");
        $mail->Subject      =$subject;
        $mail->setFrom("meowproj@yahoo.com","set-from-name");
        // $mail->body="helo";
        $mail->msgHTML($mesaj);
        $mail->smtpClose();
        if($mail->Send())
        {
            // echo "email send";
            return true;
        }
        else{
            return false;
        }
        
    }
?>