<?php
    namespace App\Controllers\admin;

    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\MitraLog;
    use App\Models\Mitra;
    use App\Models\Transaksi as TransaksiModel;
    use App\Models\Paket;

    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;
    use App\Libraries\Tabel;
    use App\Libraries\MitraJWT;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;

    use Config\Database;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Transaksi extends BaseController{
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

        public function pending(){
            helper('CustomDate');

            $transaksiModel =   new TransaksiModel();
            $mitraModel     =   new Mitra();

            $listPembelian      =   $transaksiModel->getTransaksiPending();
            foreach($listPembelian as $index => $transaksiItem){
                $transaksiMitra =   $transaksiItem['mitra'];

                $detailMitra    =   $mitraModel->getMitra($transaksiMitra);

                $listPembelian[$index]['mitra'] =   $detailMitra;
            }

            $data   =   [
                'view'      =>  adminView('transaksi/pending'),
                'pageTitle' =>  'Transaksi',
                'pageDesc'  =>  'Pembelian Paket',
                'data'  =>  [
                    'transaksiModel'    =>  $transaksiModel,
                    'listPembelian'     =>  $listPembelian
                ]
            ];
            echo view(adminView('index'), $data);
        }
        public function approvement($idTransaksi){
            $transaksi  =   new TransaksiModel();

            $status     =   false;
            $message    =   'Gagal menjalankan approvement (penerimaan/penolakan) transaksi!';
            $data       =   null;

            $db     =   new Database();
            $db     =   $db->connect($db->default);
            $db->transBegin();

            try{
                $request            =   request();

                $detailTransaksi    =   $transaksi->getTransaksi($idTransaksi);
                if(empty($detailTransaksi)){
                    throw new Exception('Tidak ada transaksi dengan pengenal '.$idTransaksi.'!');
                }

                helper('CustomDate');

                $request        =   $this->request;
                $mitra          =   new Mitra();
                $tabel          =   new Tabel();
                $paket          =   new Paket();

                $approvement    =   $request->getPost('approvement');

                if(!in_array($approvement, $transaksi->approvement)){
                    throw new Exception('Approvement action tidak dikenal "'.$approvement.'"!');
                }

                $idMitra    =   $detailTransaksi['mitra'];
                $nomor      =   $detailTransaksi['nomor'];
                $idPaket    =   $detailTransaksi['paket'];

                $detailPaket    =   $paket->getPaket($idPaket, ['select' => 'nama, durasi']);
                if(empty($detailPaket)){
                    throw new Exception('Paket tidak terdaftar pada transaksi ini!');
                }
                $namaPaket      =   $detailPaket['nama'];
                $durasiPaket    =   $detailPaket['durasi'];

                $now        =   rightNow();
                $isApproved =   $approvement == $transaksi->approvement_approved;

                try{
                    $transaksiAktifMitra    =   $mitra->getPaketAktif($idMitra, true);
                }catch(Exception $e){
                    $transaksiAktifMitra    =   null;
                }

                $detailMitra    =   $mitra->getMitra($idMitra, ['select' => 'nama, email']);
                $namaMitra      =   $detailMitra['nama'];
                $emailMitra     =   $detailMitra['email'];

                $dataTransaksi  =   [
                    'approvement'   =>  $approvement,
                    'approvementBy' =>  $this->loggedInIDAdministrator,
                    'approvementAt' =>  $now
                ];

                if($isApproved){
                    $berlakuMulai   =   $now;
                    $berlakuSampai  =   date('Y-m-d H:i:s', strtotime($berlakuMulai.' + '.$durasiPaket.' days'));

                    $dataTransaksi['berlakuMulai']      =   $berlakuMulai;
                    $dataTransaksi['berlakuSampai']     =   $berlakuSampai;
                }

                $saveApprovementTransaksi   =   $transaksi->saveTransaksi($idTransaksi, $dataTransaksi);

                $message    =   ($isApproved)? 'Gagal melakukan penyetujuan pembelian paket!' : 'Gagal melakukan penolakan pembelian paket!';
                if($saveApprovementTransaksi){
                    $emailSender    =   new EmailSender();
                    $htmlBody       =   '<div style="width: 100%; border: 1px solid #0D6EFD; border-radius: 10px; padding: 15px;">
                                          <img src="https://employer.kubu.id/assets/img/icon.png" style="float: left; width: 50px;" alt="Employer">
                                          <p><span style="font-size: 30px; color: rgb(44, 130, 201);">Kubu Employer</span></p>
                                            <br />
                                            <p>Pembelian paket '.$namaPaket.' dengan nomor transaksi <b>'.$nomor.'</b> '.(($isApproved)? '<b class="text-success">disetujui</b>' : '<b class="text-danger">ditolak</b>').'!</p>
                                        </div>';

                    $emailParams    =   [
                        'subject'   =>  ($isApproved)? 'Pembelian Paket Disetujui' : 'Pembelian Paket Ditolak',
                        'body'      =>  $htmlBody,
                        'receivers' =>  [
                            ['email' => $emailMitra, 'name' => $namaMitra]
                        ]
                    ];
                    $sendEmail          =   $emailSender->sendEmail($emailParams);
                    $statusKirimEmail   =   $sendEmail['statusSend'];

                    if($statusKirimEmail){
                        $status     =   true;
                        $message    =   ($isApproved)? 'Berhasil menerima pembelian paket!' : 'Berhasil menolak pembelian paket!';
                        $data       =   [
                            'id'    =>  $idTransaksi
                        ];

                        if($isApproved){
                            if(!empty($transaksiAktifMitra)){
                                $idTransaksiLama    =   $transaksiAktifMitra['id'];
                                $nomorTransaksiLama =   $transaksiAktifMitra['nomor'];

                                $idTransaksiBaru    =   $idTransaksi;
                                $nomorTransaksiBaru =   $nomor;

                                $saveStackedBy  =   $transaksi->saveTransaksi($idTransaksiLama, ['stackedBy' => $idTransaksiBaru]);
                                if($saveStackedBy){
                                    $mitraLog   =   new MitraLog();

                                    $titleLogMitra  =   'Transaksi '.$nomorTransaksiLama.' ditimpa dengan transaksi '.$nomorTransaksiBaru;
                                    $mitraLog->saveMitraLogFromThisModule($tabel->transaksi, $idTransaksiLama, $titleLogMitra);
                                }
                            }
                        }

                        $adminLog   =   new LogModel();
                        $tabel      =   new Tabel();

                        $title      =   ($isApproved)? 'Menyetujui pembelian paket' : 'Menolak pembelian paket';
                        $adminLog->saveAdministratorLogFromThisModule($tabel->transaksi, $idTransaksi, $title);
                    }
                }
            }catch(Exception $e){
                $status     =   false;
                $message    =   $e->getMessage();
                $data       =   null;
            }

            if($status){
                $db->transCommit();
            }else{
                $db->transRollback();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();
            return $this->respond($respond);
        }
        public function mitra($idMitra = null){
            try{
                $mitra      =   new Mitra();
                $tabel      =   new Tabel();

                $mitraExist =   !empty($idMitra);

                if($mitraExist){
                    $detailMitra    =    $mitra->getMitra($idMitra);
                    if(empty($detailMitra)){
                        throw new Exception('Tidak ditemukan mitra dengan pengenal '.$idMitra.'!');
                    }
                }

                $data   =   [
                    'view'      =>  ($mitraExist)? mitraView('transaksi/index') : adminView('transaksi/mitra'),
                    'pageTitle' =>  'Transaksi Mitra',
                    'pageDesc'  =>  ($mitraExist)? $detailMitra['nama'] : 'List Transaksi Mitra'
                ];

                if(!$mitraExist){
                    $options    =   [
                        'select'    =>  'pT.id, pT.nama, count(transaksi.id) as jumlahTransaksi',
                        'join'      =>  [
                            ['table' => $tabel->transaksi.' transaksi', 'condition' => 'transaksi.mitra=pT.id']
                        ],
                        'having'    =>  ['jumlahTransaksi >=' => 1],
                        'group_by'  =>  'pT.id'
                    ];
                    $listMitra  =   $mitra->getMitra(null, $options);

                    $data['data']   =   ['listMitra' => $listMitra];
                }else{
                    $mitraJWT       =   new MitraJWT();

                    $idMitra        =   $detailMitra['id'];

                    $mitraPayload   =   [
                        'iat'       =>  time(),
                        'iss'       =>  base_url(),
                        'mitra'     =>  [
                            'id'    =>  $idMitra
                        ]
                    ];
                    $mitraToken     =   $mitraJWT->encode($mitraPayload);

                    $data['data']   =   [
                        'mitraToken'    =>  $mitraToken
                    ];
                }

                echo view(adminView('index'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi Kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                echo view(adminView('error'), $data);
            }
        }
    }
?>
