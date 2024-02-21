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
                        'password'  =>  md5($passwordMitra),
                        'approvement'  =>  $mitraModel->approvement_approved,
                        'createdFrom'  =>  $mitraModel->createdFrom_employer
                    ];
                    $saveMitra  =   $mitraModel->saveMitra(null, $dataMitra);
                    
                    $message    =   'Gagal memproses pendaftaran mitra! Silahkan ulangi!';
                    if($saveMitra){
                        $idMitraBaru    =   $saveMitra;

                        $status     =   true;
                        $message    =   'Pendaftaran berhasil!';
                        $data       =   ['id' => $idMitraBaru];

                        $db->transCommit();
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

                    $search     =   $request->getGet('search');

                    $options            =   [
                        'limit' =>  10
                    ];

                    if(!empty($search)){
                        unset($options['limit']); #unlimited
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

                        $detailPerusahaanOptions    =   ['select' => 'id, foto, nama'];
                        $detailPerusahaan           =   ($isPremium)? $mitra->getMitra($perusahaan, $detailPerusahaanOptions) : $company->getCompany($perusahaan, $detailPerusahaanOptions);

                        $options            =   [
                            'select'    =>  'pT.nama as namaKota, provinsi.nama as namaProvinsi',
                            'join'      =>  [
                                ['table' => $tabel->provinsi.' provinsi', 'condition' => 'provinsi.id=pT.province']
                            ]
                        ];
                        $detailKota         =   $kota->getKota($kotaLoker, $options);

                        $detailJenisPekerjaan   =   $jenis->getJenisLoker($jenisLoker, ['select' => 'job_type_name as nama']);

                        $listLoker[$index]['jenis']  =   $detailJenisPekerjaan;
                        $listLoker[$index]['lokasi'] =   $detailKota;
                        $listLoker[$index]['mitra']  =   $detailPerusahaan;
                    }

                    var_dump($listLoker);
                    exit;

                    $pageData   =   [
                        'pageTitle' =>  ($isPremium)? 'Loker Premium' : 'Loker Free',
                        'view'      =>  ($isPremium)? websiteView('loker-premium') : websiteView('loker-free'),
                        'data'      =>  [
                            'listLoker'  =>  $listLoker
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
    }
?>