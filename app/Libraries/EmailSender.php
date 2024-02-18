<?php
    namespace App\Libraries;

    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    use Config\App;

    class EmailSender{
        private $mail;
        private $appConfig;
        
        public function __construct(){
            $mail       =   new PHPMailer();
            $appConfig  =   new App();
            
            $this->mail         =   $mail;
            $this->appConfig    =   $appConfig;
        }
        public function isConfigComplete(){
            $appConfig              =   $this->appConfig;
            $emailAccountUsername   =   $appConfig->emailAccountUsername;
            $emailAccountPassword   =   $appConfig->emailAccountPassword;

            $complete   =   !empty($emailAccountUsername) && !empty($emailAccountPassword);
            return $complete;
        }
        public function sendEmail($emailParams = null){
            $statusSend     =   false;
            $message        =   'Email Params not set!';

            if(!is_null($emailParams)){
                $mail   =   $this->mail;

                if(array_key_exists('subject', $emailParams) && array_key_exists('body', $emailParams)){
                    extract($emailParams);

                    try {
                        $configComplete =   $this->isConfigComplete();
                        if(!$configComplete){
                            throw new Exception('Harap lakukan konfigurasi akun email (username dan password)!');
                        }
                        
                        $appConfig              =   $this->appConfig;
                        $emailAccountUsername   =   $appConfig->emailAccountUsername;
                        $emailAccountPassword   =   $appConfig->emailAccountPassword;

                        if(array_key_exists('smtpDebug', $emailParams)){
                            $smtpDebug  =   $emailParams['smtpDebug'];

                            if($smtpDebug){
                                $mail->SMTPDebug    =   SMTP::DEBUG_SERVER; 
                            }    
                        }                 
                        $mail->isSMTP();                                            
                        $mail->Host         =   'smtp.gmail.com';               
                        $mail->SMTPAuth     =   true;                                   
                        $mail->Username     =   $emailAccountUsername;                                 
                        $mail->Password     =   $emailAccountPassword;                                            
                        $mail->SMTPSecure   =   PHPMailer::ENCRYPTION_STARTTLS;            
                        $mail->Port         =   587;                                    
                    
                        //Recipients
                        $mail->setFrom($emailAccountUsername, $emailAccountUsername);
                        if(array_key_exists('receivers', $emailParams)){
                            extract($emailParams);

                            if(is_array($receivers)){
                                if(count($receivers) >= 1){
                                    foreach($receivers as $penerima){
                                        if(array_key_exists('email', $penerima)){
                                            $withName   =   (array_key_exists('name', $penerima));
                                            $mail->addAddress($penerima['email'], ($withName)? $penerima['name'] : ''); 
                                        }
                                    }
                                }
                            }
                        }              
                        $mail->addReplyTo($emailAccountUsername, $emailAccountUsername);
                    
                        //Content
                        $mail->isHTML(true);                                 
                        $mail->Subject =    $subject;
                        $mail->Body    =    $body;
                    
                        if($mail->send()){
                            $statusSend     =   true;
                            $message        =   null;
                        }else{
                            $message        =   'Could not send email! Please contact the developer on whatsapp 082362249483 or email falentinodjoka2801@gmail.com!';
                        }
                    } catch (Exception $e) {
                        $message    =   $mail->ErrorInfo;
                    }
                }else{
                    $message    =   'You dont include subject and body!';
                }
            }

            return ['statusSend' => $statusSend, 'message' => $message];
        }
    }
?>