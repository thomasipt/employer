<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Kandidat;
    use App\Models\Loker;
    use App\Models\Mitra;
    use App\Models\Transaksi;
    use App\Models\AdministratorLog;
    use App\Models\Administrator;

    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\ErrorCode;
    use App\Libraries\FileUpload;
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    class Home extends BaseController{
        use ResponseTrait;

        private $loggedInDetaiAdministrator;
        private $loggedInIDAdministrator;
        public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger){
            parent::initController($request, $response, $logger);
            
            if(property_exists($request, 'administrator')){
                $detailAdministratorFromFilter  =   $request->administrator;
                $idAdministrator                =   $detailAdministratorFromFilter['id'];

                $this->loggedInDetaiAdministrator   =   $detailAdministratorFromFilter;
                $this->loggedInIDAdministrator      =   $idAdministrator;
            }
        }
        public function index(){
            $mitra      =   new Mitra();
            $loker      =   new Loker();
            $kandidat   =   new Kandidat();
            $transaksi  =   new Transaksi();

            $options    =   [
                'singleRow' =>  true,
                'select'    =>  'count(id) as jumlahData'
            ];

            $jumlahMitraOptions     =   $options;
            $jumlahMitraOptions['where']    =   [
                'approvement'   =>  $mitra->approvement_approved
            ];
            $getJumlahMitra         =   $mitra->getMitra(null, $jumlahMitraOptions);
            $jumlahMitra            =   (!empty($getJumlahMitra))? $getJumlahMitra['jumlahData'] : 0;

            $jumlahPembelianPaketOptions     =   $options;
            $jumlahPembelianPaketOptions['where']    =   [
                'approvement'   =>  null
            ];
            $getJumlahPembelianPaket     =   $transaksi->getTransaksi(null, $jumlahPembelianPaketOptions);
            $jumlahPembelianPaket        =   (!empty($getJumlahPembelianPaket))? $getJumlahPembelianPaket['jumlahData'] : 0;

            $lokerOptions       =   $options;
            $getJumlahLoker     =   $loker->getLoker(null, $lokerOptions);
            $jumlahLoker        =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

            $kandidatOptions    =   $options;
            $getJumlahKandidat  =   $kandidat->getKandidat(null, $kandidatOptions);
            $jumlahKandidat     =   !empty($getJumlahKandidat)? $getJumlahKandidat['jumlahData'] : 0;

            $data   =   [
                'data'  =>  [
                    'jumlahMitra'           =>  $jumlahMitra,
                    'jumlahLoker'           =>  $jumlahLoker,
                    'jumlahKandidat'        =>  $jumlahKandidat,
                    'jumlahPembelianPaket'  =>  $jumlahPembelianPaket
                ]
            ];
           return view(adminView('home'), $data);
        }
        public function profile(){
            helper('CustomDate');

            $errorCode              =   new ErrorCode();
            $detailAdministrator    =   $this->loggedInDetaiAdministrator;

            $data   =   [
                'library'   =>  [
                    'errorCode'     =>  $errorCode
                ],
                'data' =>   [
                    'detailAdministrator'   =>  $detailAdministrator
                ]  
            ];
            return view(adminView('profile'), $data);
        }
        public function updateProfile(){
            $status     =   false;
            $message    =   'Tidak dapat memproses update data administrator!';
            $data       =   null;
            $code       =   null;

            $idAdministrator    =   $this->loggedInIDAdministrator;

            $request            =   request();
            $formValidation     =   new FormValidation();
            $tabel              =   new Tabel();
            $admin              =   new Administrator();
            $errorCode          =   new ErrorCode();

            $namaAdmin      =   $request->getPost('nama');
            $usernameAdmin  =   $request->getPost('username');
            $alamatAdmin    =   $request->getPost('alamat');
            $emailAdmin     =   $request->getPost('email');
            $teleponAdmin   =   $request->getPost('telepon');

            $dataUpdate         =   [];
            $validationRules    =   [];

            if(!empty($namaAdmin)){
                $dataUpdate['nama']        =   $namaAdmin;
            }
            if(!empty($usernameAdmin)){
                $validationRules['username']    =   'is_unique['.$tabel->administrator.'.username,'.$admin->tableId.','.$idAdministrator.']';
                $dataUpdate['username']         =   $usernameAdmin;
            }
            if(!empty($alamatAdmin)){
                $dataUpdate['alamat']      =   $alamatAdmin;
            }

            if(!empty($emailAdmin)){
                $validationRules['email']   =   $formValidation->rule_validEmail.'|is_unique['.$tabel->administrator.'.email,'.$admin->tableId.','.$idAdministrator.']';
                $dataUpdate['email']        =   $emailAdmin;
            }
            if(!empty($teleponAdmin)){
                $validationRules['telepon'] =   $formValidation->rule_numeric.'|is_unique['.$tabel->administrator.'.telepon,'.$admin->tableId.','.$idAdministrator.']';
                $dataUpdate['telepon']      =   $teleponAdmin;
            }
            
            $validationMessages     =   $formValidation->generateCustomMessageForSingleRule($validationRules);
            if($this->validate($validationRules, $validationMessages)){
                $udpateAdmin    =   $admin->saveAdministrator($idAdministrator, $dataUpdate);

                $message    =   'Gagal mengupdate data administrator!';
                if($udpateAdmin){
                    $status     =   true;
                    $message    =   'Berhasil mengupdate data administrator!';
                    $data       =   [
                        'id'    =>  $idAdministrator
                    ];

                    $adminLog   =   new AdministratorLog();
                    $adminLog->saveAdministratorLogFromThisModule($tabel->administrator, $idAdministrator, 'Update Data Profile');
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
            $message    =   'Tidak dapat memproses update password administrator!';
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


                $loggedInDetailAdministrator    =   $this->loggedInDetaiAdministrator;
                $idAdministrator                =   $loggedInDetailAdministrator['id'];
                $passwordAdministrator          =   $loggedInDetailAdministrator['password'];

                $message    =   'Password tidak sesuai dengan password aktif saat ini!';
                if(md5($password) === $passwordAdministrator){
                    $message    =   'Password baru dan Konfirmasi password baru harus sama!';
                    if(md5($passwordBaru) === md5($konfirmasiPasswordBaru)){
                        $admin  =   new Administrator();

                        $datapasswordAdministrator  =   [
                            'password'  =>  md5($konfirmasiPasswordBaru)
                        ];
                        $udpateAdmin    =   $admin->saveAdministrator($idAdministrator, $datapasswordAdministrator);

                        $message    =   'Gagal mengupdate data password administrator!';
                        if($udpateAdmin){
                            $status     =   true;
                            $message    =   'Berhasil mengupdate data password administrator!';
                            $data       =   [
                                'id'    =>  $idAdministrator
                            ];
        
                            $adminLog   =   new AdministratorLog();
                            $tabel      =   new Tabel();
        
                            $adminLog->saveAdministratorLogFromThisModule($tabel->administrator, $idAdministrator, 'Update Password');
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
                $detailAdministrator    =   $this->loggedInDetaiAdministrator;
                $idAdministrator        =   $detailAdministrator['id'];
                $fotoAdmin              =   $detailAdministrator['foto'];

                $fileUpload     =   new FileUpload();
                $adminModel     =   new Administrator();

                $fileExtension  =   $fileFoto->getExtension();
                $fileName       =   'Admin-'.$idAdministrator.'-'.date('YmdHis').'.'.$fileExtension;

                $uploadOriginalFile     =   $fileFoto->move(uploadGambarAdmin(), $fileName);
                $fileUpload->resizeImage(uploadGambarAdmin($fileName), uploadGambarAdmin('compress/'.$fileName));

                $message    =   'Gagal mengupload foto baru!';
                if($uploadOriginalFile){
                    $message    =   'Gagal menyimpan foto baru, file berhasil diupload!';

                    $dataFotoAdmin  =   [
                        'foto'  =>  $fileName
                    ];
                    $saveAdmin  =   $adminModel->saveAdministrator($idAdministrator, $dataFotoAdmin);
                    if($saveAdmin){
                        $tabel      =   new Tabel();

                        $status     =   true;
                        $message    =   'Berhasil mengupload foto baru administrator!';

                        if($fotoAdmin != $adminModel->fotoDefault){
                            $oldFotoAdmin           =   uploadGambarAdmin($fotoAdmin);
                            $oldFotoCompressAdmin   =   uploadGambarAdmin('compress/'.$fotoAdmin);
                            
                            if(file_exists($oldFotoAdmin)){
                                unlink($oldFotoAdmin);
                            }
                            if(file_exists($oldFotoCompressAdmin)){
                                unlink($oldFotoCompressAdmin);
                            }
                        }

                        $adminLog   =   new AdministratorLog();
                        $adminLog->saveAdministratorLogFromThisModule($tabel->administrator, $idAdministrator, 'Ganti Foto Administrator');
                    }
                }
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
    }
?>