<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\Paket as PaketModel;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Paket extends BaseController{
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

        private function paketChecking($idPaket){
            $paket          =   new PaketModel();
            $detailPaket    =   $paket->getPaket($idPaket);
            if(empty($detailPaket)){
                throw new Exception('Tidak ditemukan paket dengan pengenal '.$idPaket.'!');
            }

            return $detailPaket;
        }
        public function index(){
            $data   =   [
                'view'      =>  adminView('paket/index'),
                'pageTitle' =>  'Paket',
                'pageDesc'  =>  'Daftar Paket'
            ];
            echo view(adminView('index'), $data);
        }
        public function getListPaket(){
            #Data
            $paket      =   new PaketModel();
            $request    =   $this->request;
            
            $draw       =   $request->getGet('draw');

            $start      =   $request->getGet('start');
            $start      =   (!is_null($start))? $start : 0;
    
            $length     =   $request->getGet('length');
            $length     =   (!is_null($length))? $length : 10;
            
            $search     =   $request->getGet('search');

            $options    =   [
                'limit'             =>  $length,
                'limitStartFrom'    =>  $start
            ];

            if(!empty($search)){
                if(is_array($search)){
                    if(array_key_exists('value', $search)){
                        $searchValue    =   $search['value'];
                        if(!empty($searchValue)){
                            $options['like']    =   [
                                'column'    =>  ['nama', 'keterangan'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }

            $listPaket     =   $paket->getPaket(null, $options);
            if(count($listPaket) >= 1){
                foreach($listPaket as $indexData => $paketItem){
                    $nomorUrut  =   $start + $indexData + 1;

                    $listPaket[$indexData]['nomorUrut']       =   $nomorUrut;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $paket->getPaket(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listPaket'         =>  $listPaket,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        /*
        public function add($idJenisLoker = null){
            try{
                $doesUpdate         =   !empty($idJenisLoker);
                $detailJenisLoker   =   ($doesUpdate)? $this->paketChecking($idJenisLoker) : null;

                $data   =   [
                    'pageTitle' =>  ($doesUpdate)? 'Update Jenis Loker' : 'Jenis Loker Baru',
                    'pageDesc'  =>  ($doesUpdate)? $detailJenisLoker['nama'] : null,
                    'view'      =>  adminView('paket/add'),
                    'data'      =>  [
                        'detailJenisLoker'   =>  $detailJenisLoker
                    ]
                ];
                return view(adminView('index'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(adminView('error'), $data);
            }
        }
        public function saveJenisLoker($idJenisLoker = null){
            $status     =   false;
            $message    =   'Gagal memproses jenis loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $request            =   request();
                $formValidation     =   new FormValidation();
                $paketModel    =   new JenisLokerModel();

                $doesUpdate         =   !empty($idJenisLoker);
                $detailJenisLoker   =   ($doesUpdate)? $this->paketChecking($idJenisLoker) : null;

                $validationRules    =   [
                    'nama' =>  'required'
                ];
    
                $validationMessage  =   $formValidation->generateCustomMessageForSingleRule($validationRules);
    
                if($this->validate($validationRules, $validationMessage)){
                    $nama       =   $request->getPost('nama');

                    $dataJenisLoker  =   [
                        'job_type_name' =>  $nama
                    ];

                    $saveJenisLoker     =   $paketModel->saveJenisLoker($idJenisLoker, $dataJenisLoker);

                    $message            =   (!$doesUpdate)? 'Gagal menambahkan jenis loker baru!' : 'Gagal mengupdate jenis loker!';
                    if($saveJenisLoker){
                        $idJenisLoker    =   $saveJenisLoker;
                        
                        $status     =   true;
                        $message    =   (!$doesUpdate)? 'Berhasil menambahkan jenis loker baru!' : 'Berhasil mengupdate jenis loker!';
                        $data       =   ['id' => $idJenisLoker];

                        $tabel      =   new Tabel();
                        $logModel   =   new LogModel();

                        $title      =   (!$doesUpdate)? 'Menambahkan jenis lowongan pekerjaan baru!' : 'Mengupdate data jenis lowongan pekerjaan!';
                        $logModel->saveAdministratorLogFromThisModule($tabel->jenis, $idJenisLoker, $title);
                    }
                }else{
                    $message    =   'Data yang dikirim tidak lengkap atau tidak memenuhi validasi!';
                    $data       =   $this->validator->getErrors();
                }
            }catch(Exception $e){
                $status     =   false;
                $message    =   $e->getMessage();
                $data       =   null;
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        */
    }
?>