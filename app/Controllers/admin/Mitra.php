<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\JenisLoker as JenisLokerModel;
    use App\Models\Administrator;
    use App\Models\Mitra as MitraModel;

    #Library
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Mitra extends BaseController{
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

        private function mitraChecking($idMitra){
            $mitra          =   new MitraModel();
            $detailMitra    =   $mitra->getMitra($idMitra);
            if(empty($detailMitra)){
                throw new Exception('Tidak ditemukan mitra dengan pengenal '.$idMitra.'!');
            }

            return $detailMitra;
        }
        public function index(){
            $data   =   [
                'view'      =>  adminView('mitra/index'),
                'pageTitle' =>  'Mitra',
                'pageDesc'  =>  'Daftar Mitra'
            ];
            echo view(adminView('index'), $data);
        }
        public function getListMitra(){
            #Data
            $mitraModel =   new MitraModel();
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
                                'column'    =>  ['nama', 'alamat', 'telepon', 'email', 'username'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }

            $listMitra     =   $mitraModel->getMitra(null, $options);
            if(count($listMitra) >= 1){
                $administrator  =   new Administrator();

                foreach($listMitra as $indexData => $mitraModelItem){
                    $nomorUrut      =   $start + $indexData + 1;
                    $approvementBy  =   $mitraModelItem['approvementBy'];

                    $detailAdministrator    =   $administrator->getAdministrator($approvementBy, ['select' => 'id, nama']);

                    $listMitra[$indexData]['nomorUrut']     =   $nomorUrut;
                    $listMitra[$indexData]['approver']      =   [
                        'administrator' =>  $detailAdministrator
                    ];
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $mitraModel->getMitra(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listMitra'         =>  $listMitra,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        public function approvement($idMitra){
            $status     =   false;
            $message    =   'Gagal menjalankan approvement (penerimaan/penolakan) mitra!';
            $data       =   null;

            try{
                $this->mitraChecking($idMitra);

                helper('CustomDate');

                $request        =   $this->request;
                $approvement    =   $request->getPost('approvement');

                $mitra  =   new MitraModel();
                if(!in_array($approvement, $mitra->approvement)){
                    throw new Exception('Approvement action tidak dikenal "'.$approvement.'"!');
                }

                $now        =   rightNow();
                $isApproved =   $approvement == $mitra->approvement_approved;

                $dataMitra  =   [
                    'approvement'   =>  $approvement,
                    'approvementBy' =>  $this->loggedInIDAdministrator,
                    'approvementAt' =>  $now
                ];
                $saveApprovementMitra   =   $mitra->saveMitra($idMitra, $dataMitra);

                $message    =   ($isApproved)? 'Gagal melakukan penyetujuan pendaftaran mitra!' : 'Gagal melakukan penolakan pendaftaran mitra!';
                if($saveApprovementMitra){
                    $status     =   true;
                    $message    =   ($isApproved)? 'Berhasil menerima pendaftaran mitra!' : 'Berhasil menolak pendaftaran mitra!';
                    $data       =   [
                        'id'    =>  $idMitra
                    ];

                    $adminLog   =   new LogModel();
                    $tabel      =   new Tabel();

                    $title      =   ($isApproved)? 'Menyetujui pendaftaran mitra' : 'Menolak pendaftaran mitra';

                    $adminLog->saveAdministratorLogFromThisModule($tabel->mitra, $idMitra, $title);
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
        public function needApprove(){
            helper('CustomDate');

            $mitra                  =   new MitraModel();
            $request                =   request();

            $mitraQueryString       =   $request->getGet('mitra');
            $mitraQueryString       =   trim($mitraQueryString);
            
            $listMitraNeedApprove   =   [];
            if(!empty($mitraQueryString)){
                $options                =   [
                    'likeGroup' =>  [
                        'operator'  =>  $mitra->likeGroupOperator_or,
                        'like'      =>  [
                            ['nama'     =>  $mitraQueryString],
                            ['alamat'   =>  $mitraQueryString],
                            ['email'    =>  $mitraQueryString],
                            ['telepon'  =>  $mitraQueryString]
                        ]
                    ]
                ];
                $listMitraNeedApprove   =   $mitra->getMitraNeedApprove($options);
            }

            $data   =   [
                'view'      =>  adminView('mitra/need-approve'),
                'pageTitle' =>  'Approvement Mitra',
                'pageDesc'  =>  'Mitra yang perlu Approvement',
                'data'      =>  [
                    'mitraSearch'           =>  $mitraQueryString,
                    'mitraModel'            =>  $mitra,
                    'listMitraNeedApprove'  =>  $listMitraNeedApprove
                ]
            ];
            echo view(adminView('index'), $data);
        }
    }
?>