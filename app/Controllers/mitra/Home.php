<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
use App\Libraries\APIRespondFormat;
use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra as MitraModel;
    use App\Models\MitraLog;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

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
           return view(mitraView('home'));
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
    }
?>