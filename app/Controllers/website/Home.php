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
    use App\Models\Kota;
    use App\Models\Loker;
    use App\Models\Mitra;
    use App\Models\JenisLoker;
    use App\Models\LokerFree;
    use App\Models\Company;
use App\Models\Homepage;
use App\Models\HomepageElement;
use App\Models\MitraLog;
    use App\Models\Transaksi;

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
            $mitraModel         =   new Mitra();
            $emailSender        =   new EmailSender();

            $db     =   $mitraModel->db;

            $db->transBegin();

            try{
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
                        'password'  =>  md5($passwordMitra),
                        'approvement'  =>  $mitraModel->approvement_approved,
                        'createdFrom'  =>  $mitraModel->createdFrom_employer
                    ];
                    $saveMitra  =   $mitraModel->saveMitra(null, $dataMitra);

                    $message    =   'Gagal memproses pendaftaran mitra! Silahkan ulangi!';
                    if($saveMitra){
                        $idMitraBaru    =   $saveMitra;

                        $encodedIDMitra =   base64_encode($idMitraBaru);
                        $linkVerifikasi =   site_url(websiteController('verifikasi/'.$encodedIDMitra));

                        $status     =   true;
                        $message    =   'Pendaftaran Berhasil, Cek Email Untuk Verifikasi!';
                        $data       =   ['id' => $idMitraBaru];

                        $htmlBody   =   '<div style="width: 100%; border: 1px solid #0D6EFD; border-radius: 10px; padding: 15px;">
                                            <img src="https://employer.kubu.id/assets/img/icon.png" style="float: left; width: 50px;" alt="Employer">
                                            <p><span style="font-size: 30px; color: rgb(44, 130, 201);">Kubu Employer</span></p>
                                            <br />
                                            <p>Anda telah mendaftar sebagai mitra ke dalam website <a href="https://employer.kubu.id">Employer</a>.
                                            Silahkan klik link berikut ini untuk memverifikasi pendaftaran anda <a href="'.$linkVerifikasi.'">'.$linkVerifikasi.'</a></p>
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
            helper('CustomDate');

            $mitra              =   new Mitra();
            $decodedIdMitra     =   base64_decode($encodedIDMitra);

            try{
                $detailMitra    =   $mitra->getMitra($decodedIdMitra);
                if(empty($detailMitra)){
                    throw new Exception('Gagal memverifikasi email, mitra tidak terdaftar!');
                }

                $emailSender    =   new EmailSender();

                $idMitra        =   $detailMitra['id'];
                $namaMitra      =   $detailMitra['nama'];
                $emailMitra     =   $detailMitra['email'];
                $verifiedMitra  =   $detailMitra['verified'];

                $message        =   'Anda sudah terverifikasi!';

                $isVerified     =   $verifiedMitra == $mitra->emailVerification_verified;
                if(!$isVerified){
                    $passwordDefault    =   $mitra->passwordDefault;

                    $subject    =   'Verifikasi Pendaftaran Mitra '.$namaMitra;
                    $body       =   '<div>
                                        <p>
                                        Selamat, pendaftaran anda berhasil! Silahkan login ke halaman
                                        <a href="'.site_url(mitraController('login')).'">Login Mitra</a> untuk mulai memposting lowongan pekerjaan dan mendapatkan kandidat perusahaan anda
                                        </p>
                                        <p><b>Akun Mitra</b></p>
                                        <p>Username: '.$emailMitra.'</p>
                                        <p>Password: '.$passwordDefault.'</p>
                                        <br />
                                        <p>
                                            Anda tentunya bisa mengganti username dan password anda dengan dengan yang baru di halaman
                                            <a href="'.site_url(mitraController('profile')).'">ini</a>
                                        </p>
                                    </div>';


                    $emailParams    =   [
                        'subject'   =>  $subject,
                        'body'      =>  $body,
                        'receivers' =>  [
                            ['email' => $emailMitra, 'name' => $namaMitra]
                        ]
                    ];
                    $sendEmail      =   $emailSender->sendEmail($emailParams);
                    $statusSend     =   $sendEmail['statusSend'];

                    $message    =   'Verifikasi email gagal, silahkan coba lagi!';
                    if($statusSend){
                        $dataVerifikasi =   [
                            'verified'      =>  $mitra->emailVerification_verified,
                            'verifiedAt'    =>  rightNow()
                        ];
                        $updateMitra    =   $mitra->saveMitra($idMitra, $dataVerifikasi);

                        $message    =   'Gagal verifikasi email!';
                        if($updateMitra){
                            $message    =   'Berhasil verifikasi email. Silakan cek email kembali, Kami telah mengirimkan username & password Login Mitra';

                            $mitraLog   =   new MitraLog();
                            $tabel      =   new Tabel();

                            $dataMitraLog   =   [
                                'title'     =>  'Verifikasi Pendaftaran',
                                'module'    =>  $tabel->mitra,
                                'idModule'  =>  $idMitra,
                                'createdBy' =>  $idMitra
                            ];
                            $mitraLog->saveMitraLog(null, $dataMitraLog);
                        }
                    }
                }

                $pageData   =   [
                    'pageTitle' =>  'Verifikasi Pendaftaran',
                    'view'      =>  websiteView('verifikasi-pendaftaran'),
                    'data'      =>  [
                        'message'   =>  $message
                    ]
                ];
                return view(websiteView('index'), $pageData);
            }catch(Exception $e){
                $data   =   [
                    'title'     =>  'Verifikasi Email',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(websiteView('error'), $data);
            }
        }
        public function loker($statusLoker, $encodedIDLoker = null){
            helper('CustomDate');

            $loker              =   new Loker();
            $lokerFree          =   new LokerFree();
            $mitra              =   new Mitra();
            $company            =   new Company();
            $kategoriLoker      =   new KategoriLoker();
            $tabel              =   new Tabel();

            $isDetail   =   !empty($encodedIDLoker);

            try{
                if(!in_array($statusLoker, ['free', 'premium'])){
                    throw new Exception('Jenis Loker (Premium/Free) tidak valid1');
                }

                $isPremium  =   $statusLoker == 'premium';

                if($isDetail){
                    $idLoker        =   base64_decode($encodedIDLoker);

                    $options        =   [
                        'select'    =>  'pT.*, kota.nama as namaKota, jenis.job_type_name as namaJenis',
                        'join'      =>  [
                            ['table' => $tabel->kota.' kota', 'condition' => 'kota.kode=pT.kota'],
                            ['table' => $tabel->jenis.' jenis', 'condition' => 'jenis.id=pT.jenis']
                        ]
                    ];
                    $detailLoker    =   ($isPremium)? $loker->getLoker($idLoker, $options) : $lokerFree->getLokerFree($idLoker, $options);
                    if(empty($detailLoker)){
                        throw new Exception('Loker dengan pengenal '.$encodedIDLoker.' tidak ditemukan!');
                    }

                    $judulLoker         =   $detailLoker['judul'];
                    $perusahaanLoker    =   $detailLoker['createdBy'];

                    $detailPerusahaan   =   ($isPremium)? $mitra->getMitra($perusahaanLoker) : $company->getCompany($perusahaanLoker);

                    $sektorPerusahaan   =   $detailPerusahaan['sector'];
                    $detailSektor       =   $kategoriLoker->getKategoriLoker($sektorPerusahaan, ['select' => 'name as nama']);

                    $data   =   [
                        'pageTitle' =>  $judulLoker,
                        'view'      =>  websiteView('detail-loker'),
                        'data'      =>  [
                            'isPremium'         =>  $isPremium,
                            'detailLoker'       =>  $detailLoker,
                            'detailPerusahaan'  =>  $detailPerusahaan,
                            'sektorPerusahaan'  =>  $detailSektor
                        ]
                    ];
                    return view(websiteView('index'), $data);
                }else{
                    $request            =   request();
                    $kota               =   new Kota();
                    $jenis              =   new JenisLoker();
                    $transaksi          =   new Transaksi();

                    $search     =   $request->getGet('search');
                    $page       =   $request->getGet('page');
                    $batas      =   12;
                    $pageKe     =   !empty($page)? $page : 1;
                    $firstIndex =   ($pageKe > 1)? ($pageKe * $batas) - $batas : 0;

                    $prevIndex  =   $pageKe - 1;
                    $nextIndex  =   $pageKe + 1;

                    $options            =   [
                        'select'            =>  'pT.*',
                        'limit'             =>  $batas,
                        'limitStartFrom'    =>  $firstIndex
                    ];

                    if(!$isPremium){
                        $options['where']   =   [
                            'status'    =>  1
                        ];
                    }else{
                        $rightNow           =   date('Y-m-d H:i:s', strtotime(rightNow()));
                        $options['join']    =   [
                            ['table' => $tabel->transaksi.' transaksi', 'condition' => 'transaksi.mitra=pT.createdBy'],
                        ];
                        $options['where']   =   [
                            'transaksi.approvement'         =>  $transaksi->approvement_approved,
                            'transaksi.stackedBy'           =>  null,
                            'transaksi.berlakuMulai <='     =>  $rightNow,
                            'transaksi.berlakuSampai >='    =>  $rightNow
                        ];
                    }

                    if(!empty($search)){
                        $options['likeGroup']   =   [
                            'operator'  =>  $loker->likeGroupOperator_or,
                            'like'      =>  [
                                ['judul' => $search],
                                ['deskripsi' => $search]
                            ]
                        ];
                    }

                    $listLoker   =   ($isPremium)? $loker->getLoker(null, $options) : $lokerFree->getLokerFree(null, $options);
                    foreach($listLoker as $index => $lokerPremium){
                        $jenisLoker         =   $lokerPremium['jenis'];
                        $kotaLoker          =   $lokerPremium['kota'];
                        $perusahaan         =   $lokerPremium['createdBy'];

                        $detailPerusahaanOptions    =   ['select' => 'id, foto, cover, nama'];
                        $detailPerusahaan           =   ($isPremium)? $mitra->getMitra($perusahaan, $detailPerusahaanOptions) : $company->getCompany($perusahaan, $detailPerusahaanOptions);

                        $kotaOptions    =   [
                            'select'    =>  'pT.nama as namaKota, provinsi.nama as namaProvinsi',
                            'join'      =>  [
                                ['table' => $tabel->provinsi.' provinsi', 'condition' => 'provinsi.kode=pT.province']
                            ]
                        ];
                        $detailKota         =   $kota->getKota($kotaLoker, $kotaOptions);

                        $detailJenisPekerjaan   =   $jenis->getJenisLoker($jenisLoker, ['select' => 'job_type_name as nama']);

                        $listLoker[$index]['jenis']  =   $detailJenisPekerjaan;
                        $listLoker[$index]['lokasi'] =   $detailKota;
                        $listLoker[$index]['mitra']  =   $detailPerusahaan;
                    }

                    $jumlahLokerOptions     =   $options;
                    $jumlahLokerOptions['singleRow']    =   true;
                    $jumlahLokerOptions['select']       =   ($isPremium)? 'count(pT.'.$loker->tableId.') as jumlahData' : 'count(pT.'.$lokerFree->tableId.') as jumlahData';
                    unset($jumlahLokerOptions['limit']);
                    unset($jumlahLokerOptions['limitStartFrom']);
                    $getJumlahLoker         =   ($isPremium)? $loker->getLoker(null, $jumlahLokerOptions) : $lokerFree->getLokerFree(null, $jumlahLokerOptions);
                    $jumlahLoker            =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

                    $totalPage      =   ceil($jumlahLoker / $batas);

                    $pageData   =   [
                        'pageTitle' =>  ($isPremium)? 'Loker Premium' : 'Loker Free',
                        'view'      =>  ($isPremium)? websiteView('loker-premium') : websiteView('loker-free'),
                        'data'      =>  [
                            'listLoker'     =>  $listLoker,
                            'jumlahLoker'   =>  $jumlahLoker,
                            'totalPage'     =>  $totalPage,
                            'page'          =>  $pageKe,
                            'next'          =>  $nextIndex,
                            'previous'      =>  $prevIndex
                        ]
                    ];
                    return view(websiteView('index'), $pageData);
                }
            }catch(Exception $e){
                $data   =   [
                    'pageTitle' =>  'Lowongan Pekerjaan',
                    'view'      =>  websiteView('error'),
                    'title'     =>  'Lowongan Pekerjaan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(websiteView('index'), $data);
            }
        }
        public function contact(){
            $status     =   false;
            $message    =   'Gagal memproses! Silahkan ulangi!';
            $data       =   null;
            $code       =   null;

            $request        =   request();
            $formValidation =   new FormValidation();

            try{
                $validationRules    =   [
                    'nama'      =>  'required',
                    'email'     =>  'required|valid_email',
                    'subject'   =>  'required',
                    'message'   =>  'required'
                ];
                $validationMessages =   $formValidation->generateCustomMessageForSingleRule($validationRules);
                $validationStatus   =   $this->validate($validationRules, $validationMessages);
                if($validationStatus){
                    $homepage           =   new Homepage();
                    $homepageElement    =   new HomepageElement();

                    $nama       =   $request->getPost('nama');
                    $email      =   $request->getPost('email');
                    $subject    =   $request->getPost('subject');
                    $pesan      =   $request->getPost('message');

                    $options            =   [
                        'where' =>  [
                            'parent'    =>  $homepage->emailPerusahaanId
                        ]
                    ];
                    $emailPerusahaanElements  =   $homepageElement->getHomepageElement(null, $options);
                    $emailPerusahaanElements  =   $homepageElement->convertListELementToKeyValueMap($emailPerusahaanElements);

                    $companyEmail   =   $emailPerusahaanElements['_email'];

                    $message    =   'Ini email perusahaan kami, tidak dapat digunakan!';
                    if($email != $companyEmail){
                        $emailSender    =   new EmailSender();

                        #reassign
                        $pesan  =   '<div>
                                        <p>Hai, seseorang dengan nama <b>'.$nama.'</b> dan email '.$email.' menghubungi anda melalui website (form contact us):</p>
                                        <p><b>Berikut pesan yang dikirimkan:</b></p>
                                        '.$pesan.'
                                    </div>';

                        $emailParams    =   [
                            'subject'   =>  $subject,
                            'body'      =>  $pesan,
                            'receivers' =>  [
                                ['email' => $companyEmail]
                            ]
                        ];
                        $sendEmail          =   $emailSender->sendEmail($emailParams);
                        $statusSendEmail    =   $sendEmail['statusSend'];
                        $messageSendEmail   =   $sendEmail['message'];

                        $message    =   'Gagal mengirim email! '.$messageSendEmail;
                        if($statusSendEmail){
                            $status     =   true;
                            $message    =   'Berhasil mengirim email!';
                            $data       =   null;
                        }
                    }
                }else{
                    $errorCode  =   new ErrorCode();

                    $code       =   $errorCode->formValidationError;
                    $message    =   $this->validator->getErrors();
                }
            }catch(Exception $e){
                $message    =   $e->getMessage();
            }


            $arf        =   new APIRespondFormat($status, $message, $data, $code);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
    }
?>
