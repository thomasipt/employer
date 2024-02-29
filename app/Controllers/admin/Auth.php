<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;
    use Config\Database;

    #Model
    use App\Models\Administrator;

    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;
    
    class Auth extends BaseController{
        use ResponseTrait;
        public function index(){
            $session                =   session();
            $detailAdministrator    =   $session->get('administrator');
            if(!empty($detailAdministrator)){
                return redirect(adminController());
            }

            echo view(adminView('login'));
        }
        public function authProcess(){
            $status     =   false;
            $message    =   'Login gagal! Pastikan username dan password sesuai!';
            $data       =   null;

            $r  =   $this->request;
            $username   =   $r->getPost('username');
            $password   =   $r->getPost('password');

            $administrator                   =   new Administrator();
            $administratorCheckingProcess   =   $administrator->authenticationProcess($username, $password);
            if(!empty($administratorCheckingProcess)){
                $status     =   true;
                $message    =   null;
                $data   =   [
                    'administrator' =>  $administratorCheckingProcess
                ];

                $session    =   session();
                $session->set('administrator', $administratorCheckingProcess);
            }

            $respond    =   [
                'status'    =>  $status,
                'message'   =>  $message,
                'data'      =>  $data
            ];

            return $this->respond($respond);
        }
        public function logout(){
            $session    =   session();
            $session->set('administrator', null);

            return redirect(adminController());
        }
        public function notAuthorized(){
            echo view(adminView('403'));
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
                echo view(adminView('lupa-password'), $data);
            }else{
                $status     =   false;
                $message    =   'Administrator tidak dikenal dengan username tersebut!';

                $administrator      =   new Administrator();
                $database           =   new Database();
                $db                 =   $database->connect($database->default);
                
                $db->transBegin();

                $administratorOptions   =   [
                    'singleRow'     =>  true,
                    'select'        =>  'id, nama, email',
                    'whereGroup'    =>  [
                        'operator'  =>  $administrator->whereGroupOperator_or,
                        'where'     =>  [
                            ['username' => $emailOrUsername],
                            ['email'    => $emailOrUsername]
                        ]
                    ]
                ];
                $detailAdministrator    =   $administrator->getAdministrator(null, $administratorOptions);
                if(!empty($detailAdministrator)){
                    $emailSender        =   new EmailSender();
                    
                    $idAdministrator    =   $detailAdministrator['id'];
                    $namaAdministrator  =   $detailAdministrator['nama'];
                    $emailAdministrator =   $detailAdministrator['email'];

                    $passwordBaru   =   (string) rand(000000, 999999);

                    $subject    =   'Reset Password Administrator an '.$namaAdministrator;
                    $body       =   'Password anda sudah direset menjadi '.$passwordBaru.'. Silahkan login kembali dengan password baru anda.';

                    $emailParameters    =   [
                        'subject'   =>  $subject,
                        'body'      =>  $body,
                        'receivers' =>  [
                            ['email' => $emailAdministrator, 'name' => $namaAdministrator]
                        ]
                    ];
                    $sendEmail      =   $emailSender->sendEmail($emailParameters);

                    $statusSend     =   $sendEmail['statusSend'];
                    $messageSend    =   $sendEmail['message'];

                    $message        =   'Gagal mengirim email reset kata sandi! '.$messageSend;

                    if($statusSend){
                        $dataPasswordAdministrator  =   [
                            'password'  =>  md5($passwordBaru)
                        ];
                        $saveAdministrator  =   $administrator->saveAdministrator($idAdministrator, $dataPasswordAdministrator);

                        $message    =   'Gagal mereset data password administrator! '.$messageSend;
                        if($saveAdministrator){
                            $status     =   true;
                            $message    =   'Berhasil mereset password administrator!';
                        }
                    }
                }

                if($status){
                    $db->transCommit();
                }else{
                    $db->transRollback();
                }

                $arf        =   new APIRespondFormat($status, $message, null);
                $respond    =   $arf->getRespond();
                return $this->respond($respond);
            }
        }
    }
?>