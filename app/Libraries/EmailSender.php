<?php
    namespace App\Libraries;

    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    use App\Models\Homepage;
    use App\Models\HomepageElement;

    class EmailSender{
        private $mail;

        public $username;
        public $password;
        
        public function __construct(){
            $mail       =   new PHPMailer();
            
            $this->mail         =   $mail;

            $homepage           =   new Homepage();
            $homepageElement    =   new HomepageElement();

            $options    =   [
                'where' =>  ['parent' => $homepage->emailPerusahaanId]
            ];
            $emailPerusahaanElement     =   $homepageElement->getHomepageElement(null, $options);
            $emailPerusahaanElement     =   $homepageElement->convertListELementToKeyValueMap($emailPerusahaanElement);

            $username =   $emailPerusahaanElement['_email'];
            $password =   $emailPerusahaanElement['_password'];
            
            if(!empty($username)){
                $this->username =   $username;
            }
            if(!empty($password)){
                $this->password =   $password;
            }
        }
        public function isConfigComplete(){
            $username   =   $this->username;
            $password   =   $this->password;

            $complete   =   !empty($username) && !empty($password);
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
                        
                        $username   =   $this->username;
                        $password   =   $this->password;

                        if(array_key_exists('smtpDebug', $emailParams)){
                            $smtpDebug  =   $emailParams['smtpDebug'];

                            if($smtpDebug){
                                $mail->SMTPDebug    =   SMTP::DEBUG_SERVER; 
                            }    
                        }                 
                        $mail->isSMTP();                                            
                        $mail->Host         =   '103.30.145.71';               
                        $mail->SMTPAuth     =   true;                                   
                        $mail->Username     =   'root';                                 
                        $mail->Password     =   '@Kubu_Indo24!';                                            
                        $mail->SMTPSecure   =   PHPMailer::ENCRYPTION_STARTTLS;            
                        $mail->Port         =   587;                                    
                    
                        //Recipients
                        $mail->setFrom($username, $username);
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
                        $mail->addReplyTo($username, $username);
                    
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
