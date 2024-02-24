<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\Administrator;
    use App\Models\Mitra as MitraModel;

    #Library
    use App\Libraries\Tabel;
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
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

            $database   =   new Database();
            $builder    =   $database->connect($database->default);
            $db     =   $builder->table('mitra');
            $listMitra  =   $db->get()->getResultArray();
            var_dump($listMitra);
            echo '<br />';



            $listMitra     =   $mitraModel->getMitra(null, $options);
            var_dump($listMitra);
            exit;
            
            if(count($listMitra) >= 1){
                $administrator  =   new Administrator();

                foreach($listMitra as $indexData => $mitraItem){
                    $nomorUrut      =   $start + $indexData + 1;
                    $approvementBy  =   $mitraItem['approvementBy'];

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
            
            $mitra  =   new MitraModel();
            $db     =   $mitra->db;

            $db->transBegin();

            try{
                $detailMitra    =   $this->mitraChecking($idMitra);
                $idMitra        =   $detailMitra['id'];
                $namaMitra      =   $detailMitra['nama'];
                $emailMitra     =   $detailMitra['email'];

                helper('CustomDate');

                $request        =   $this->request;
                $approvement    =   $request->getPost('approvement');

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
                    $emailSender    =   new EmailSender();
                    
                    $passwordDefault    =   $mitra->passwordDefault;

                    $subject    =   'Penerimaan Pendaftaran Mitra an '.$namaMitra;
                    $body       =   '<div>
                                        <p>
                                        Selamat, pendaftaran anda <b style="color: green">diterima</b> oleh Administrator Employer. Silahkan login ke halaman  
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
                    if(!$isApproved){
                        $subject    =   'Penolakan Pendaftaran Mitra an '.$namaMitra;
                        $body       =   '<div>
                                            Mohon maaf, pendaftaran anda <b style="color: red">ditolak</b> oleh Administrator Employer.
                                        </div>';
                    }

                    $emailParams    =   [
                        'subject'   =>  $subject,
                        'body'      =>  $body,
                        'receivers' =>  [
                            ['email' => $emailMitra, 'name' => $namaMitra]
                        ]
                    ];
                    $sendEmail      =   $emailSender->sendEmail($emailParams);
                    $statusSend     =   $sendEmail['statusSend'];
                    if($statusSend){
                        $status     =   true;
                        $message    =   ($isApproved)? 'Berhasil menerima pendaftaran mitra!' : 'Berhasil menolak pendaftaran mitra!';
                        $data       =   [
                            'id'    =>  $idMitra
                        ];

                        $adminLog   =   new LogModel();
                        $tabel      =   new Tabel();

                        $title      =   ($isApproved)? 'Menyetujui pendaftaran mitra' : 'Menolak pendaftaran mitra';

                        $adminLog->saveAdministratorLogFromThisModule($tabel->mitra, $idMitra, $title);

                        $db->transCommit();
                    }else{
                        $db->transRollback();
                    }
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