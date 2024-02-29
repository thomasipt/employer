<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra as MitraModel;
    use App\Models\MitraLog;
    use App\Models\Loker;
    use App\Models\Paket;
    use App\Models\Transaksi;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Home extends BaseController{
        use ResponseTrait;
        
        private $loggedInDetailMitra;
        private $loggedInIDMitra;
        public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger){
            parent::initController($request, $response, $logger);
            
            if(property_exists($request, 'mitra')){
                $detailMitraFromFilter  =   $request->mitra;
                $idMitra                =   $detailMitraFromFilter['id'];

                $this->loggedInDetailMitra  =   $detailMitraFromFilter;
                $this->loggedInIDMitra      =   $idMitra;
            }
        }
        public function index(){
            $loggedInIDMitra    =   $this->loggedInIDMitra;

            $loker  =   new Loker();
            $mitra  =   new MitraModel();
            $paket  =   new Paket();
            $transaksi  =   new Transaksi();
            
            $options    =   [
                'singleRow' =>  true,
                'select'    =>  'count(id) as jumlahData'
            ];

            $jumlahLokerOptions             =   $options;
            $jumlahLokerOptions['where']    =   [
                'createdBy' =>  $loggedInIDMitra
            ];
            $getJumlahLoker         =   $loker->getLoker(null, $jumlahLokerOptions);
            $jumlahLoker            =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

            try{
                $transaksiAktif         =   $mitra->getPaketAktif($loggedInIDMitra, true);
                if(!empty($transaksiAktif)){
                    $paketTransaksiAktif    =   $transaksiAktif['paket'];
                    $detailPaket            =   $paket->getPaket($paketTransaksiAktif, ['select' => 'nama']);
                    if(!empty($detailPaket)){
                        $paketAktif =   $detailPaket['nama'];
                    }
                }
            }catch(Exception $e){
                $paketAktif =   null;
            }
            
            $transaksiOptions   =   $options;
            $transaksiOptions['where']  =   [
                'mitra'         =>  $loggedInIDMitra,
                'approvement'   =>  $transaksi->approvement_approved
            ];
            $getJumlahTransaksi     =   $transaksi->getTransaksi(null, $transaksiOptions);
            $jumlahHistoryTransaksi =   !empty($getJumlahTransaksi)? $getJumlahTransaksi['jumlahData'] : 0;

            $transaksiPendingOptions    =   $transaksiOptions;
            $transaksiPendingOptions['where']['approvement']    =   null;
            $getJumlahTransaksiPending      =   $transaksi->getTransaksi(null, $transaksiPendingOptions);
            $jumlahTransaksiPending         =   !empty($getJumlahTransaksiPending)? $getJumlahTransaksiPending['jumlahData'] : 0;

            $data   =   [
                'data'  =>  [
                    'jumlahLoker'   =>  $jumlahLoker,
                    'paketAktif'    =>  $paketAktif,
                    'jumlahHistoryTransaksi'    =>  $jumlahHistoryTransaksi,
                    'jumlahTransaksiPending'    =>  $jumlahTransaksiPending
                ]
            ];   
            return view(mitraView('home'), $data);
        }
        public function profile(){
            helper('CustomDate');

            $data   =   [
                'data' =>   [
                    'detailMitra'   =>  $this->loggedInDetailMitra
                ]  
            ];
            return view(mitraView('profile'), $data);
        }
        public function updateProfile(){
            $status     =   false;
            $message    =   'Tidak dapat memproses update data mitra!';
            $data       =   null;

            $request            =   request();
            $formValidation     =   new FormValidation();

            $namaMitra      =   $request->getPost('nama');
            $alamatMitra    =   $request->getPost('alamat');
            $emailMitra     =   $request->getPost('email');
            $teleponMitra   =   $request->getPost('telepon');

            $dataUpdateMitra        =   [];
            $validationRules        =   [];

            if(!empty($namaMitra)){
                $dataUpdateMitra['nama']    =   $namaMitra;
            }
            if(!empty($alamatMitra)){
                $dataUpdateMitra['alamat']  =   $alamatMitra;
            }

            if(!empty($emailMitra)){
                $validationRules['email']   =   $formValidation->rule_validEmail;
                $dataUpdateMitra['email']   =   $emailMitra;
            }
            if(!empty($teleponMitra)){
                $validationRules['telepon'] =   $formValidation->rule_numeric;
                $dataUpdateMitra['telepon'] =   $teleponMitra;
            }
            
            $validationMessages     =   $formValidation->generateCustomMessageForSingleRule($validationRules);
            if($this->validate($validationRules, $validationMessages)){
                $mitra  =   new MitraModel();

                $idMitra        =   $this->loggedInIDMitra;

                $updateMitra    =   $mitra->saveMitra($idMitra, $dataUpdateMitra);

                $message    =   'Gagal mengupdate data mitra!';
                if($updateMitra){
                    $status     =   true;
                    $message    =   'Berhasil mengupdate data mitra!';
                    $data       =   [
                        'id'    =>  $idMitra
                    ];

                    $mitraLog   =   new MitraLog();
                    $tabel      =   new Tabel();

                    $mitraLog->saveMitraLogFromThisModule($tabel->mitra, $idMitra, 'Update Data Profile');
                }
            }else{
                $message    =   'Validasi tidak terpenuhi!';
                $data       =   $this->validator->getErrors();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function gantiPassword(){
            $status     =   false;
            $message    =   'Tidak dapat memproses update password mitra!';
            $data       =   null;

            $request            =   request();
            $formValidation     =   new FormValidation();

            $validationRules        =   [
                'password'                  =>  $formValidation->rule_required,
                'passwordBaru'              =>  $formValidation->rule_required,
                'konfirmasiPasswordBaru'    =>  $formValidation->rule_required
            ];
            
            $validationMessages     =   $formValidation->generateCustomMessageForSingleRule($validationRules);
            if($this->validate($validationRules, $validationMessages)){
                $password                   =   $request->getPost('password');
                $passwordBaru               =   $request->getPost('passwordBaru');
                $konfirmasiPasswordBaru     =   $request->getPost('konfirmasiPasswordBaru');


                $loggedInDetailMitra    =   $this->loggedInDetailMitra;
                $idMitra                =   $loggedInDetailMitra['id'];
                $passwordMitra          =   $loggedInDetailMitra['password'];

                $message    =   'Password tidak sesuai dengan password aktif saat ini!';
                if(md5($password) === $passwordMitra){
                    $message    =   'Password baru dan Konfirmasi password baru harus sama!';
                    if(md5($passwordBaru) === md5($konfirmasiPasswordBaru)){
                        $mitra  =   new MitraModel();

                        $dataPasswordMitra  =   [
                            'password'  =>  md5($konfirmasiPasswordBaru)
                        ];
                        $updateMitra    =   $mitra->saveMitra($idMitra, $dataPasswordMitra);

                        $message    =   'Gagal mengupdate data password mitra!';
                        if($updateMitra){
                            $status     =   true;
                            $message    =   'Berhasil mengupdate data password mitra!';
                            $data       =   [
                                'id'    =>  $idMitra
                            ];
        
                            $mitraLog   =   new MitraLog();
                            $tabel      =   new Tabel();
        
                            $mitraLog->saveMitraLogFromThisModule($tabel->mitra, $idMitra, 'Update Password');
                        }
                    }
                }
            }else{
                $message    =   'Validasi tidak terpenuhi!';
                $data       =   $this->validator->getErrors();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
    }
?>