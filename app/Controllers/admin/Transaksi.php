<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\MitraLog;
    use App\Models\Mitra;
    use App\Models\Transaksi as TransaksiModel;
    
    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\Tabel;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
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

                $approvement    =   $request->getPost('approvement');

                if(!in_array($approvement, $transaksi->approvement)){
                    throw new Exception('Approvement action tidak dikenal "'.$approvement.'"!');
                }

                $idMitra    =   $detailTransaksi['mitra'];
                $nomor      =   $detailTransaksi['nomor'];
                $now        =   rightNow();
                $isApproved =   $approvement == $transaksi->approvement_approved;

                $transaksiAktifMitra     =   $mitra->getPaketAktif($idMitra, true);

                $dataTransaksi  =   [
                    'approvement'   =>  $approvement,
                    'approvementBy' =>  $this->loggedInIDAdministrator,
                    'approvementAt' =>  $now
                ];
                $saveApprovementTransaksi   =   $transaksi->saveTransaksi($idTransaksi, $dataTransaksi);

                $message    =   ($isApproved)? 'Gagal melakukan penyetujuan pembelian paket!' : 'Gagal melakukan penolakan pembelian paket!';
                if($saveApprovementTransaksi){
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