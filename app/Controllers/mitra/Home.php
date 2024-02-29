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
    use App\Libraries\ErrorCode;
    use App\Libraries\FileUpload;

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

            $errorCode          =   new ErrorCode();
            $detailMitra        =   $this->loggedInDetailMitra;

            $data   =   [
                'library'   =>  [
                    'errorCode'     =>  $errorCode
                ],
                'data' =>   [
                    'detailMitra'   =>  $detailMitra
                ]  
            ];
            return view(mitraView('profile'), $data);
        }
        public function updateProfile(){
            $status     =   false;
            $message    =   'Tidak dapat memproses update data mitra!';
            $data       =   null;
            $code       =   null;

            $idMitra            =   $this->loggedInIDMitra;

            $request            =   request();
            $formValidation     =   new FormValidation();
            $tabel              =   new Tabel();
            $mitra              =   new MitraModel();
            $errorCode          =   new ErrorCode();

            $namaMitra      =   $request->getPost('nama');
            $usernameMitra  =   $request->getPost('username');
            $alamatMitra    =   $request->getPost('alamat');
            $emailMitra     =   $request->getPost('email');
            $teleponMitra   =   $request->getPost('telepon');

            $dataUpdateMitra        =   [];
            $validationRules        =   [];

            if(!empty($namaMitra)){
                $dataUpdateMitra['nama']        =   $namaMitra;
            }
            if(!empty($usernameMitra)){
                $validationRules['username']    =   'is_unique['.$tabel->mitra.'.username,'.$mitra->tableId.','.$idMitra.']';
                $dataUpdateMitra['username']    =   $usernameMitra;
            }
            if(!empty($alamatMitra)){
                $dataUpdateMitra['alamat']      =   $alamatMitra;
            }

            if(!empty($emailMitra)){
                $validationRules['email']   =   $formValidation->rule_validEmail.'|is_unique['.$tabel->mitra.'.email,'.$mitra->tableId.','.$idMitra.']';
                $dataUpdateMitra['email']   =   $emailMitra;
            }
            if(!empty($teleponMitra)){
                $validationRules['telepon'] =   $formValidation->rule_numeric.'|is_unique['.$tabel->mitra.'.telepon,'.$mitra->tableId.','.$idMitra.']';
                $dataUpdateMitra['telepon'] =   $teleponMitra;
            }
            
            $validationMessages     =   $formValidation->generateCustomMessageForSingleRule($validationRules);
            if($this->validate($validationRules, $validationMessages)){
                $updateMitra    =   $mitra->saveMitra($idMitra, $dataUpdateMitra);

                $message    =   'Gagal mengupdate data mitra!';
                if($updateMitra){
                    $status     =   true;
                    $message    =   'Berhasil mengupdate data mitra!';
                    $data       =   [
                        'id'    =>  $idMitra
                    ];

                    $mitraLog   =   new MitraLog();
                    $mitraLog->saveMitraLogFromThisModule($tabel->mitra, $idMitra, 'Update Data Profile');
                }
            }else{
                $message    =   'Validasi tidak terpenuhi!';
                $data       =   $this->validator->getErrors();
                $code       =   $errorCode->formValidationError;
            }

            $arf        =   new APIRespondFormat($status, $message, $data, $code);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function gantiPassword(){
            $status     =   false;
            $message    =   'Tidak dapat memproses update password mitra!';
            $data       =   null;
            $code       =   null;

            $request            =   request();
            $formValidation     =   new FormValidation();
            $errorCode          =   new ErrorCode();

            $validationRules        =   [
                'password'                  =>  $formValidation->rule_required,
                'passwordBaru'              =>  $formValidation->rule_required.'|min_length[8]|matches[konfirmasiPasswordBaru]',
                'konfirmasiPasswordBaru'    =>  $formValidation->rule_required.'|min_length[8]|matches[passwordBaru]'
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
                $code       =   $errorCode->formValidationError;
            }

            $arf        =   new APIRespondFormat($status, $message, $data, $code);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function gantiFoto(){
            $request                =   request();
            $fileFoto               =   $request->getFile('foto');
            $isFileAttached         =   !empty($fileFoto);

            $status     =   false;
            $message    =   'Foto harus disematkan!';
            $data       =   null;

            if($isFileAttached){
                $detailMitra    =   $this->loggedInDetailMitra;
                $idMitra        =   $detailMitra['id'];
                $fotoMitra      =   $detailMitra['foto'];

                $fileUpload     =   new FileUpload();
                $mitraModel     =   new MitraModel();

                $fileExtension  =   $fileFoto->getExtension();
                $fileName       =   'Mitra-'.$idMitra.'-'.date('YmdHis').'.'.$fileExtension;

                $uploadOriginalFile     =   $fileFoto->move(uploadGambarMitra(), $fileName);
                $fileUpload->resizeImage(uploadGambarMitra($fileName), uploadGambarMitra('compress/'.$fileName));

                $message    =   'Gagal mengupload foto baru!';
                if($uploadOriginalFile){
                    $message    =   'Gagal menyimpan foto baru, file berhasil diupload!';

                    $dataFotoMitra  =   [
                        'foto'  =>  $fileName
                    ];
                    $saveMitra  =   $mitraModel->saveMitra($idMitra, $dataFotoMitra);
                    if($saveMitra){
                        $tabel      =   new Tabel();

                        $status     =   true;
                        $message    =   'Berhasil mengupload foto baru mitra!';

                        if($fotoMitra != $mitraModel->fotoDefault){
                            $oldFotoMitra           =   uploadGambarMitra($fotoMitra);
                            $oldFotoCompressMitra   =   uploadGambarMitra('compress/'.$fotoMitra);
                            
                            if(file_exists($oldFotoMitra)){
                                unlink($oldFotoMitra);
                            }
                            if(file_exists($oldFotoCompressMitra)){
                                unlink($oldFotoCompressMitra);
                            }
                        }

                        $mitraLog   =   new MitraLog();
                        $mitraLog->saveMitraLogFromThisModule($tabel->mitra, $idMitra, 'Ganti Foto Mitra');
                    }
                }
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
    }
?>