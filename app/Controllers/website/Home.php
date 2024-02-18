<?php
    namespace App\Controllers\website;
    
    use App\Controllers\BaseController;
    
    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;
    use App\Libraries\ErrorCode;
    
    #Models
    use App\Models\KategoriLoker;
    use App\Models\Mitra;

    use CodeIgniter\API\ResponseTrait;

    use Exception;

    class Home extends BaseController{
        use ResponseTrait;

        public function sk(){
            $pageData   =   [
                'pageTitle' =>  'Syarat dan Ketentuan',
                'view'      =>  websiteView('syarat-dan-ketentuan')
            ];
            return view(websiteView('index'), $pageData);
        }
        public function kebijakanPrivasi(){
            $pageData   =   [
                'pageTitle' =>  'Kebijakan Privasi',
                'view'      =>  websiteView('kebijakan-privasi')
            ];
            return view(websiteView('index'), $pageData);
        }
        public function registrasi(){
            $kategoriLoker  =   new KategoriLoker();
            $error          =   new ErrorCode();
            $emailSender    =   new EmailSender();

            $options        =   [
                'select'    =>  'sektor_id as id, name as nama',
                'order_by'  =>  [
                    'column'        =>  'nama',
                    'orientation'   =>  'asc'
                ]
            ];
            $listSektor     =   $kategoriLoker->getKategoriLoker(null, $options);

            $pageData   =   [
                'pageTitle' =>  'Registrasi',
                'view'      =>  websiteView('registrasi'),
                'library'   =>  [
                    'error'         =>  $error,
                    'emailSender'   =>  $emailSender
                ],
                'data'      =>  [
                    'listSektor'    =>  $listSektor
                ]
            ];
            return view(websiteView('index'), $pageData);
        }
        public function prosesRegistrasi(){
            $status     =   false;
            $message    =   'Gagal memproses registrasi mitra! Silahkan ulangi!';
            $data       =   null;
            $code       =   null;

            $formValidation     =   new FormValidation();
            $tabel              =   new Tabel();
            $emailSender        =   new EmailSender();
            $mitraModel         =   new Mitra();

            $db     =   $mitraModel->db;

            $db->transBegin();

            try{
                $configComplete     =   $emailSender->isConfigComplete();
                if(!$configComplete){
                    throw new Exception('Harap hubungi administrator untuk menyelesaikan konfigurasi email');
                }

                $dataMitra          =   [];
                $validationRules    =   [
                    'nama'      =>  $formValidation->rule_required,
                    'alamat'    =>  $formValidation->rule_required,
                    'telepon'   =>  $formValidation->rule_required.'|'.$formValidation->rule_numeric.'|is_unique['.$tabel->mitra.'.telepon]',
                    'email'     =>  $formValidation->rule_validEmail.'|'.$formValidation->rule_required.'|is_unique['.$tabel->mitra.'.email]',
                    'sektor'    =>  $formValidation->rule_required,
                    'pic'       =>  $formValidation->rule_required
                ];
                $validationMessages     =   $formValidation->generateCustomMessageForSingleRule($validationRules);

                $message    =   'Data tidak valid!';
                if($this->validate($validationRules, $validationMessages)){
                    $request    =   request();

                    $nama       =   $request->getPost('nama');
                    $alamat     =   $request->getPost('alamat');
                    $telepon    =   $request->getPost('telepon');
                    $email      =   $request->getPost('email');
                    $sektor     =   $request->getPost('sektor');
                    $pic        =   $request->getPost('pic');

                    $sektorModel    =   new KategoriLoker();
                    $detailSektor   =   $sektorModel->getKategoriLoker($sektor);
                    if(empty($detailSektor)){
                        throw new Exception('Sektor pekerjaan tidak terdaftar!');
                    }

                    $passwordMitra  =   $mitraModel->passwordDefault;

                    $dataMitra  =   [
                        'nama'      =>  $nama,
                        'alamat'    =>  $alamat,
                        'telepon'   =>  $telepon,
                        'pic_name'  =>  $pic,
                        'email'     =>  $email,
                        'sector'    =>  $sektor,
                        'password'  =>  md5($passwordMitra)
                    ];
                    $saveMitra  =   $mitraModel->saveMitra(null, $dataMitra);
                    
                    $message    =   'Gagal memproses pendaftaran mitra! Silahkan ulangi!';
                    if($saveMitra){
                        $idMitraBaru    =   $saveMitra;

                        $encodedIDMitra =   base64_encode($idMitraBaru);
                        $linkVerifikasi =   site_url(websiteController('verifikasi/'.$encodedIDMitra));

                        $status     =   true;
                        $message    =   'Pendaftaran berhasil!';
                        $data       =   ['id' => $idMitraBaru];

                        $htmlBody   =   '<div style="width: 100%; border: 1px solid #0D6EFD; border-radius: 10px; padding: 15px;">
                                            <center>
                                                <img src="https://employer.kubu.id/assets/img/icon.png" style="width: 150px; display: block; margin: auto;"
                                                    alt="Employer" />
                                            </center>
                                            <br />
                                            <p>Anda telah mendaftar sebagai mitra ke dalam website <a href="https://employer.kubu.id">Employer</a>.
                                            Silahkan klik link berikut ini untuk memverifikasi pendaftaran anda <a href="'.$linkVerifikasi.'">'.$linkVerifikasi.'</a></p>
                                            <p>Selanjutnya anda hanya perlu menunggu approvement dari Administrator Employer untuk mengaktifkan akun anda.</p>
                                            <p style="color: red;">Jika anda tidak melakukan pendaftaran, silahkan abaikan pesan ini</p>
                                        </div>';

                        $emailParams    =   [
                            'subject'   =>  'Pendaftaran Mitra Employer',
                            'body'      =>  $htmlBody,
                            'receivers' =>  [
                                ['email' => $email, 'name' => $nama]
                            ]
                        ];
                        $sendEmail          =   $emailSender->sendEmail($emailParams);   
                        $statusKirimEmail   =   $sendEmail['statusSend'];
                        if($statusKirimEmail){
                            $db->transCommit();
                        }else{
                            $db->transRollback();
                        }
                    }
                }else{
                    $error  =   new ErrorCode();

                    $code   =   $error->formValidationError;
                    $data   =   $this->validator->getErrors();
                }
            }catch(Exception $e){
                $status     =   false;
                $message    =   $e->getMessage();
                $data       =   null;
            }

            $arf        =   new APIRespondFormat($status, $message, $data, $code);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function verifikasi($encodedIDMitra){
            $mitra              =   new Mitra();
            $decodedIdMitra     =   base64_decode($encodedIDMitra);

            try{
                $detailMItra    =   $mitra->getMitra($decodedIdMitra);
                if(empty($detailMItra)){
                    throw new Exception('Gagal memverifikasi email, mitra tidak terdaftar!');
                }

                helper('CustomDate');

                $idMitra    =   $detailMItra['id'];
                
                $dataVerifikasi =   [
                    'verified'      =>  $mitra->emailVerification_verified,
                    'verifiedAt'    =>  rightNow()
                ];
                $updateMitra    =   $mitra->saveMitra($idMitra, $dataVerifikasi);
                if($updateMitra){
                    echo 'Berhasil verifikasi email!';
                }else{
                    echo 'Gagal verifikasi email!';
                }
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Verifikasi Email',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(websiteView('error'), $data);
            }
        }
    }
?>