<?php
    namespace App\Libs;
    use Resource\PHPMailer\PHPMailer;
    use Resource\PHPMailer\PHPMailerException;

    class Email {
        private $subject, $content, $recipient;

        public function __construct() {
        }

        public function sendEmail() {
    
            $mail = new PHPMailer;
    
            $mail->isSMTP();                                   // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                    // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                            // Enable SMTP authentication
            $mail->Username = 'email@gmail.com';          // SMTP username
            $mail->Password = 'pwd'; // SMTP password
            $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPOptions = array(
    
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
    
            );
    
            $mail->Port = 25;                                 // TCP port to connect to
    
            $mail->setFrom('password_reset@email.com', 'Orjah');
            $mail->addReplyTo('password_reset@email.com', 'Orjah');
            $mail->addAddress($this->recipient);   // Add a recipient

            $mail->isHTML(true);  // Set email format to HTML
    
            $mail->Subject = $this->subject;
            $mail->Body    = $this->content;
    
            if(!$mail->send()) {
                return false;
            } 
            return true;
        }

        public function setEmailSubject($subject) {
            return $this->subject = $subject;
        }

        public function setRecipientEmail($email) {
            return $this->recipient = $email;
        }

        public function setEmailContent($content) {
            return $this->content = $content;
        }

    }

?>