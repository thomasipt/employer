<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;
    use Config\Database;

    #Model
    use App\Models\Mitra;

    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;

    use Exception;

    class Auth extends BaseController{
        use ResponseTrait;
        public function index(){
            $session        =   session();
            $detailMitra    =   $session->get('mitra');
            if(!empty($detailMitra)){
                return redirect(mitraController());
            }

            echo view(mitraView('login'));
        }
        public function authProcess(){
            $status     =   false;
            $message    =   'Login gagal! Pastikan username dan password sesuai!';
            $data       =   null;

            try{
                $r  =   $this->request;
                $username   =   $r->getPost('username');
                $password   =   $r->getPost('password');

                $mitra                  =   new Mitra();
                $mitraCheckingProcess   =   $mitra->authenticationProcess($username, $password);
                if(!empty($mitraCheckingProcess)){
                    $idMitra    =   $mitraCheckingProcess['id'];
                    $isActive   =   $mitra->isApproved($idMitra);
                    if(!$isActive){
                        throw new Exception('Maaf, pendaftaran anda tidak disetujui!');
                    }

                    $status     =   true;
                    $message    =   null;
                    $data   =   [
                        'mitra' =>  $mitraCheckingProcess
                    ];

                    $session    =   session();
                    $session->set('mitra', $mitraCheckingProcess);
                }
            }catch(Exception $e){
                $message    =   $e->getMessage();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function logout(){
            $session    =   session();
            $session->set('mitra', null);

            return redirect(mitraController());
        }
        public function notAuthorized(){
            echo view(mitraView('403'));
        }
        public function lupaPassword(){
            $request            =   $this->request;
            $emailOrUsername    =   $request->getPost('username');
            
            if(empty($emailOrUsername)){
                $emailSender    =   new EmailSender();

                $data   =   [
                    'library' =>  [
                        'emailSender'   =>  $emailSender
                    ]
                ];
                echo view(mitraView('lupa-password'), $data);
            }else{
                $status     =   false;
                $message    =   'Mitra tidak dikenal dengan username tersebut!';

                $mitra              =   new Mitra();
                $database           =   new Database();
                $db                 =   $database->connect($database->default);
                
                $db->transBegin();

                $mitraOptions   =   [
                    'singleRow'     =>  true,
                    'select'        =>  'id, nama, email',
                    'whereGroup'    =>  [
                        'operator'  =>  $mitra->whereGroupOperator_or,
                        'where'     =>  [
                            ['username' => $emailOrUsername],
                            ['email'    => $emailOrUsername]
                        ]
                    ]
                ];
                $detailMitra    =   $mitra->getMitra(null, $mitraOptions);
                if(!empty($detailMitra)){
                    $emailSender        =   new EmailSender();
                    
                    $idMitra            =   $detailMitra['id'];
                    $namaMitra          =   $detailMitra['nama'];
                    $emailMitra         =   $detailMitra['email'];

                    $passwordBaru   =   (string) rand(000000, 999999);

                    $subject    =   'Reset Password Mitra an '.$namaMitra;
                    $body       =   'Password anda sudah direset menjadi '.$passwordBaru.'. Silahkan login kembali dengan password baru anda.';

                    $emailParameters    =   [
                        'smtpDebug' =>  true,
                        'subject'   =>  $subject,
                        'body'      =>  $body,
                        'receivers' =>  [
                            ['email' => $emailMitra, 'name' => $namaMitra]
                        ]
                    ];
                    $sendEmail      =   $emailSender->sendEmail($emailParameters);

                    $statusSend     =   $sendEmail['statusSend'];
                    $messageSend    =   $sendEmail['message'];

                    $message        =   'Gagal mengirim email reset kata sandi! '.$messageSend;

                    if($statusSend){
                        $dataPasswordMitra  =   [
                            'password'  =>  md5($passwordBaru)
                        ];
                        $saveMitra  =   $mitra->saveMitra($idMitra, $dataPasswordMitra);

                        $message    =   'Gagal mereset data password mitra! '.$messageSend;
                        if($saveMitra){
                            $status     =   true;
                            $message    =   'Berhasil mereset password mitra!';
                        }
                    }
                }

                // if($status){
                //     $db->transCommit();
                // }else{
                //     $db->transRollback();
                // }

                // $arf        =   new APIRespondFormat($status, $message, null);
                // $respond    =   $arf->getRespond();
                // return $this->respond($respond);
            }
        }
    }
?>