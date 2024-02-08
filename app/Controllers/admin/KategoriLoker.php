<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\KategoriLoker as KategoriLokerModel;
    use App\Models\Administrator;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class KategoriLoker extends BaseController{
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

        private function kategoriLokerChecking($idKategoriLoker){
            $kategoriLoker          =   new KategoriLokerModel();
            $options                =   [
                'select'    =>  'sektor_id as id, name as nama'
            ];
            $detailKategoriLoker    =   $kategoriLoker->getKategoriLoker($idKategoriLoker, $options);
            if(empty($detailKategoriLoker)){
                throw new Exception('Tidak ditemukan kategori loker dengan pengenal '.$idKategoriLoker.'!');
            }

            return $detailKategoriLoker;
        }
        public function index(){
            $data   =   [
                'view'      =>  adminView('kategori-loker/index'),
                'pageTitle' =>  'Kategori Loker',
                'pageDesc'  =>  'Daftar Kategori Loker'
            ];
            echo view(adminView('index'), $data);
        }
        public function getListKategoriLoker(){
            #Data
            $kategoriLoker  =   new KategoriLokerModel();
            $request        =   $this->request;
            
            $draw       =   $request->getGet('draw');

            $start      =   $request->getGet('start');
            $start      =   (!is_null($start))? $start : 0;
    
            $length     =   $request->getGet('length');
            $length     =   (!is_null($length))? $length : 10;
            
            $search     =   $request->getGet('search');

            $options    =   [
                'select'            =>  'sektor_id as id, name as nama',
                'limit'             =>  $length,
                'limitStartFrom'    =>  $start
            ];

            if(!empty($search)){
                if(is_array($search)){
                    if(array_key_exists('value', $search)){
                        $searchValue    =   $search['value'];
                        if(!empty($searchValue)){
                            $options['like']    =   [
                                'column'    =>  ['name'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }

            $listKategoriLoker     =   $kategoriLoker->getKategoriLoker(null, $options);
            if(count($listKategoriLoker) >= 1){

                foreach($listKategoriLoker as $indexData => $kategoriLokerItem){
                    $nomorUrut  =   $start + $indexData + 1;

                    $listKategoriLoker[$indexData]['nomorUrut']       =   $nomorUrut;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count('.$kategoriLoker->tableId.') as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $kategoriLoker->getKategoriLoker(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listKategoriLoker'    =>  $listKategoriLoker,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        public function add($idKategoriLoker = null){
            try{
                $doesUpdate             =   !empty($idKategoriLoker);
                $detailKategoriLoker    =   ($doesUpdate)? $this->kategoriLokerChecking($idKategoriLoker) : null;

                $data   =   [
                    'pageTitle' =>  ($doesUpdate)? 'Update Kategori Loker' : 'Kategori Loker Baru',
                    'pageDesc'  =>  ($doesUpdate)? $detailKategoriLoker['nama'] : null,
                    'view'      =>  adminView('kategori-loker/add'),
                    'data'      =>  [
                        'detailKategoriLoker'   =>  $detailKategoriLoker
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
        public function saveKategoriLoker($idKategoriLoker = null){
            $status     =   false;
            $message    =   'Gagal memproses kategori loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $request                =   request();
                $formValidation         =   new FormValidation();
                $kategoriLokerModel     =   new KategoriLokerModel();

                $doesUpdate             =   !empty($idKategoriLoker);
                $detailKategoriLoker    =   ($doesUpdate)? $this->kategoriLokerChecking($idKategoriLoker) : null;

                $validationRules    =   [
                    'nama' =>  'required'
                ];
    
                $validationMessage  =   $formValidation->generateCustomMessageForSingleRule($validationRules);
    
                if($this->validate($validationRules, $validationMessage)){
                    $nama       =   $request->getPost('nama');

                    $dataKategoriLoker  =   [
                        'name'  =>  $nama
                    ];

                    $saveKategoriLoker  =   $kategoriLokerModel->saveKategoriLoker($idKategoriLoker, $dataKategoriLoker);

                    $message            =   (!$doesUpdate)? 'Gagal menambahkan kategori loker baru!' : 'Gagal mengupdate kategori loker!';
                    if($saveKategoriLoker){
                        $idKategoriLoker    =   $saveKategoriLoker;
                        
                        $status     =   true;
                        $message    =   (!$doesUpdate)? 'Berhasil menambahkan kategori loker baru!' : 'Berhasil mengupdate kategori loker!';
                        $data       =   ['id' => $idKategoriLoker];

                        $tabel      =   new Tabel();
                        $logModel   =   new LogModel();

                        $title      =   (!$doesUpdate)? 'Menambahkan kategori lowongan pekerjaan baru!' : 'Mengupdate data kategori lowongan pekerjaan!';
                        $logModel->saveAdministratorLogFromThisModule($tabel->kategori, $idKategoriLoker, $title);
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
        public function deleteKategoriLoker($idKategoriLoker){
            $status     =   false;
            $message    =   'Gagal menghapus kategori loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $kategoriLokerModel    =   new KategoriLokerModel();

                $this->kategoriLokerChecking($idKategoriLoker);

                $deleteKategoriLoker   =   $kategoriLokerModel->deleteKategoriLoker($idKategoriLoker);

                $message    =   'Gagal menghapus kategori loker!';
                if($deleteKategoriLoker){
                    $status     =   true;
                    $message    =   'Berhasil menghapus kategori loker!';

                    $logModel   =   new LogModel();
                    $tabel      =   new Tabel();
                    $logModel->saveAdministratorLogFromThisModule($tabel->kategori, $idKategoriLoker, 'Menghapus kategori loker');
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