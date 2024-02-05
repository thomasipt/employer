<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\JenisLoker as JenisLokerModel;
    use App\Models\Administrator;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class JenisLoker extends BaseController{
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

        private function jenisLokerChecking($idJenisLoker){
            $jenisLoker         =   new JenisLokerModel();
            $detailJenisLoker   =   $jenisLoker->getJenisLoker($idJenisLoker);
            if(empty($detailJenisLoker)){
                throw new Exception('Tidak ditemukan jenis loker dengan pengenal '.$idJenisLoker.'!');
            }

            return $detailJenisLoker;
        }
        public function index(){
            $data   =   [
                'view'      =>  adminView('jenis-loker/index'),
                'pageTitle' =>  'Jenis Loker',
                'pageDesc'  =>  'Daftar Jenis Loker'
            ];
            echo view(adminView('index'), $data);
        }
        public function getListJenisLoker(){
            #Data
            $jenisLoker =   new JenisLokerModel();
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

            $listJenisLoker     =   $jenisLoker->getJenisLoker(null, $options);
            if(count($listJenisLoker) >= 1){
                $administrator  =   new Administrator();

                foreach($listJenisLoker as $indexData => $jenisLokerItem){
                    $nomorUrut  =   $start + $indexData + 1;
                    $createdBy  =   $jenisLokerItem['createdBy'];

                    $detailAdministrator    =   $administrator->getAdministrator($createdBy, ['select' => 'id, nama']);

                    $listJenisLoker[$indexData]['nomorUrut']       =   $nomorUrut;
                    $listJenisLoker[$indexData]['administrator']   =   $detailAdministrator;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $jenisLoker->getJenisLoker(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listJenisLoker'    =>  $listJenisLoker,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        public function add($idJenisLoker = null){
            try{
                $doesUpdate         =   !empty($idJenisLoker);
                $detailJenisLoker   =   ($doesUpdate)? $this->jenisLokerChecking($idJenisLoker) : null;

                $data   =   [
                    'pageTitle' =>  ($doesUpdate)? 'Update Jenis Loker' : 'Jenis Loker Baru',
                    'pageDesc'  =>  ($doesUpdate)? $detailJenisLoker['nama'] : null,
                    'view'      =>  adminView('jenis-loker/add'),
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
                $jenisLokerModel    =   new JenisLokerModel();

                $doesUpdate         =   !empty($idJenisLoker);
                $detailJenisLoker   =   ($doesUpdate)? $this->jenisLokerChecking($idJenisLoker) : null;

                $validationRules    =   [
                    'nama' =>  'required'
                ];
    
                $validationMessage  =   $formValidation->generateCustomMessageForSingleRule($validationRules);
    
                if($this->validate($validationRules, $validationMessage)){
                    $nama       =   $request->getPost('nama');
                    $keterangan =   $request->getPost('keterangan');

                    $dataJenisLoker  =   [
                        'nama'          =>  $nama,
                        'createdBy'     =>  $this->loggedInIDAdministrator
                    ];

                    if(!empty($keterangan)){
                        $dataJenisLoker['keterangan']   =   $keterangan;
                    }

                    $saveJenisLoker     =   $jenisLokerModel->saveJenisLoker($idJenisLoker, $dataJenisLoker);

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
        public function deleteJenisLoker($idJenisLoker){
            $status     =   false;
            $message    =   'Gagal menghapus jenis loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $jenisLokerModel    =   new JenisLokerModel();

                $this->jenisLokerChecking($idJenisLoker);

                $deleteJenisLoker   =   $jenisLokerModel->deleteJenisLoker($idJenisLoker);

                $message    =   'Gagal menghapus jenis loker!';
                if($deleteJenisLoker){
                    $status     =   true;
                    $message    =   'Berhasil menghapus jenis loker!';

                    $logModel   =   new LogModel();
                    $tabel      =   new Tabel();
                    $logModel->saveAdministratorLogFromThisModule($tabel->jenis, $idJenisLoker, 'Menghapus jenis loker');
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
    }
?>