<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra;
    use App\Models\Transaksi as TransaksiModel;
    use App\Models\Paket;
    use App\Models\MitraLog;
    
    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\PDF;
    use App\Libraries\Tabel;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;

    use Dompdf\Options;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Transaksi extends BaseController{
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

        private function paketChecking($paketCode){
            $paket          =   new Paket();

            $options        =   [
                'singleRow' =>  true,
                'where'     =>  [
                    'code'  =>  $paketCode
                ]
            ];
            $detailPaket    =   $paket->getPaket(null, $options);
            if(empty($detailPaket)){
                throw new Exception('Tidak ditemukan paket dengan kode <b>'.$paketCode.'</b>!');
            }

            return $detailPaket;
        }
        public function listTransaksi(){
            $data   =   [
                'view'      =>  mitraView('transaksi/index'),
                'pageTitle' =>  'Transaksi',
                'pageDesc'  =>  'History Transaksi'
            ];
            echo view(mitraView('index'), $data);
        }
        public function getListTransaksi(){
            #Data
            $transaksi  =   new TransaksiModel();
            $request    =   $this->request;
            
            $draw       =   $request->getGet('draw');

            $start      =   $request->getGet('start');
            $start      =   (!is_null($start))? $start : 0;
    
            $length     =   $request->getGet('length');
            $length     =   (!is_null($length))? $length : 10;
            
            $search     =   $request->getGet('search');

            $options    =   [
                'limit'             =>  $length,
                'limitStartFrom'    =>  $start,
                'where'     =>  [
                    'mitra' =>  $this->loggedInIDMitra
                ]
            ];

            if(!empty($search)){
                if(is_array($search)){
                    if(array_key_exists('value', $search)){
                        $searchValue    =   $search['value'];
                        if(!empty($searchValue)){
                            $options['like']    =   [
                                'column'    =>  ['nomor'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }
            

            $listTransaksi    =   $transaksi->getTransaksi(null, $options);
            if(count($listTransaksi) >= 1){
                $mitra  =   new Mitra();
                $paket  =   new Paket();

                foreach($listTransaksi as $indexData => $transaksiItem){
                    $nomorUrut          =   $start + $indexData + 1;
                    $transaksiMitra     =   $transaksiItem['mitra'];
                    $transaksiPaket     =   $transaksiItem['paket'];
                    $transaksiStackedBy =   $transaksiItem['stackedBy'];

                    $detailMitra            =   $mitra->getMitra($transaksiMitra, ['select' => 'id, nama']);
                    $detailPaket            =   $paket->getPaket($transaksiPaket, ['select' => 'id, nama, keterangan']);

                    $listTransaksi[$indexData]['nomorUrut']     =   $nomorUrut;
                    $listTransaksi[$indexData]['mitra']         =   $detailMitra;
                    $listTransaksi[$indexData]['paket']         =   $detailPaket;
                    
                    if(!empty($transaksiStackedBy)){
                        $detailTransaksiPenimpa =   $transaksi->getTransaksi($transaksiStackedBy, ['select' => 'id, nomor']);
                        $listTransaksi[$indexData]['stackedBy']     =   $detailTransaksiPenimpa;
                    }
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $transaksi->getTransaksi(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listTransaksi'     =>  $listTransaksi,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        public function checkout($paketCode = null){
            try{
                $mitra              =   new Mitra();
                $transaksi          =   new TransaksiModel();
                $tabel              =   new Tabel();
                $loggedInIDMitra    =   $this->loggedInIDMitra;

                $transaksiAktif     =   $mitra->getPaketAktif($loggedInIDMitra, true);

                $detailPaket        =   $this->paketChecking($paketCode);

                if(!empty($transaksiAktif)){
                    $idTransaksiAktif   =   $transaksiAktif['id'];

                    $options            =   [
                        'select'    =>  'pT.*, p.nama as namaPaket, p.durasi as durasiPaket',
                        'join'      =>  [
                            ['table'    =>  $tabel->paket.' p', 'condition' => 'p.id=pT.paket']
                        ]
                    ];
                    $transaksiAktif     =   $transaksi->getTransaksi($idTransaksiAktif, $options);
                }

                $namaPaket      =   $detailPaket['nama'];

                $data   =   [
                    'pageTitle' =>  'Checkout',
                    'pageDesc'  =>  'Paket '.$namaPaket,
                    'view'      =>  mitraView('transaksi/checkout'),
                    'data'      =>  [
                        'detailPaket'           =>  $detailPaket,
                        'transaksiAktif'        =>  $transaksiAktif
                    ]
                ];
                return view(mitraView('index'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(mitraView('error'), $data);
            }
        }
        public function processCheckout($paketCode){
            $transaksi      =   new TransaksiModel();

            $status     =   false;
            $message    =   'Gagal memproses checkout! Silahkan ulangi lagi!';
            $data       =   null;

            $db     =   $transaksi->db;
            $db->transBegin();

            try{
                helper('CustomDate');
                
                $mitra          =   new Mitra();
                $paket          =   new Paket();
                
                $tabel          =   new Tabel();

                $detailPaket    =   $this->paketChecking($paketCode);
                
                $currentDate    =   currentDate();

                $nomorTransaksi =   strtoupper(uniqid($paketCode));
                $idPaket        =   $detailPaket['id'];
                $codePaket      =   $detailPaket['code'];
                $namaPaket      =   $detailPaket['nama'];
                $durasiPaket    =   $detailPaket['durasi'];
                $hargaPaket     =   $detailPaket['harga'];
                $ppnPaket       =   $paket->persentasePPN / 100 * $hargaPaket;
                $berlakuSampai  =   date('Y-m-d H:i:s', strtotime($currentDate.' + '.$durasiPaket.' days'));

                $dataTransaksi  =   [
                    'nomor' =>  $nomorTransaksi,
                    'mitra' =>  $this->loggedInIDMitra,
                    'paket' =>  $idPaket,
                    'berlakuMulai'  =>  $currentDate,
                    'berlakuSampai' =>  $berlakuSampai,
                    'harga'         =>  $hargaPaket,
                    'ppn'           =>  $ppnPaket
                ];

                $saveTransaksi  =   $transaksi->saveTransaksi(null, $dataTransaksi);

                $message    =   'Gagal checkout! Silahkan ulangi lagi!';
                if($saveTransaksi){
                    $mitraLog           =   new MitraLog();

                    $idTransaksiBaru    =   $saveTransaksi;

                    $status     =   true;
                    $message    =   'Berhasil checkout!';
                    $data       =   ['id' => $idTransaksiBaru];

                    $title      =   'Beli paket baru '.$namaPaket.'  ('.$codePaket.')';
                    $mitraLog->saveMitraLogFromThisModule($tabel->transaksi, $idTransaksiBaru, $title);
                }
            }catch(Exception $e){
                $status     =   false;
                $message    =   $e->getMessage();
                $data       =   null;
            }

            if($db->transStatus()){
                $db->transCommit();
            }else{
                $db->transRollback();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function uploadBuktiBayar(){
            $transaksi      =   new TransaksiModel();
            $db             =   $transaksi->db;

            $db->transBegin();

            $status     =   false;
            $message    =   'Gagal mengupload bukti bayar! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                $request        =   request();
                $tabel          =   new Tabel();

                $nomorTransaksi     =   $request->getPost('nomorTransaksi');
                $fileBuktiBayar     =   $request->getFile('buktiBayar');

                $options    =   [
                    'singleRow' =>  true,
                    'where'     =>  [
                        'nomor'    =>  $nomorTransaksi
                    ]
                ];
                $detailTransaksi    =   $transaksi->getTransaksi(null, $options);
                if(empty($detailTransaksi)){
                    throw new Exception('Maaf, tidak ada transaksi dengan nomor transaksi '.$nomorTransaksi.'!');
                }

                $idTransaksi        =   $detailTransaksi['id'];
                $mitraTransaksi     =   $detailTransaksi['mitra'];
                $loggedInIDMitra    =   $this->loggedInIDMitra;

                if($mitraTransaksi != $loggedInIDMitra){
                    throw new Exception('Maaf, transaksi ini '.$nomorTransaksi.' bukan transaksi anda!');
                }
                
                $fileExtension  =   $fileBuktiBayar->getClientExtension();
                $fileName       =   'BuktiBayar-'.$idTransaksi.'-'.date('YmdHis').'.'.$fileExtension;
                $fileBuktiBayar->move(uploadGambarBuktiBayar(), $fileName);
                
                $isSuccessUploadFile    =   file_exists(uploadGambarBuktiBayar($fileName));

                $message    =   'Gagal mengupload bukti bayar!';
                if($isSuccessUploadFile){
                    $dataTransaksi  =   [
                        'buktiBayar'    =>  $fileName
                    ];
                    $saveBuktiBayarTransaksi     =   $transaksi->saveTransaksi($idTransaksi, $dataTransaksi);
                    
                    $message    =   'Gagal mengupload bukti bayar!';
                    if($saveBuktiBayarTransaksi){
                        $status     =   true;
                        $message    =   'Berhasil upload bukti bayar!';
                        $data       =   null;

                        $mitraLog   =   new MitraLog();
                        $mitraLog->saveMitraLogFromThisModule($tabel->transaksi, $idTransaksi, 'Upload bukti bayar transaksi '.$nomorTransaksi);
                    }
                }
            }catch(Exception $e){
                $status     =   false;
                $message    =   $e->getMessage();
                $data       =   null;
            }

            if($db->transStatus()){
                $db->transCommit();
            }else{
                $db->transRollback();
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();
            return $this->respond($respond);
        }
        public function invoice($idTransaksi){
            try{
                helper('CustomDate');

                $transaksi          =   new TransaksiModel();

                $detailTransaksi    =   $transaksi->getTransaksi($idTransaksi);
                if(empty($detailTransaksi)){
                    throw new Exception('Tidak ditemukan transaksi dengan pengenal '.$idTransaksi.'!');
                }

                $mitraTransaksi     =   $detailTransaksi['mitra'];
                $nomorTransaksi     =   $detailTransaksi['nomor'];
                $paketTransaksi     =   $detailTransaksi['paket'];

                $loggedInIDMitra    =   $this->loggedInIDMitra;

                if($mitraTransaksi != $loggedInIDMitra){
                    throw new Exception('Maaf, transaksi '.$nomorTransaksi.' bukan transaksi anda!');
                }

                $paket          =   new Paket();
                $detailPaket    =   $paket->getPaket($paketTransaksi);

                $pdf        =   new PDF($this);
                $options    =   new Options();
                $options->setIsRemoteEnabled(true);

                $data   =   [
                    'model' =>  [
                        'transaksi'    =>   $transaksi
                    ],
                    'data'  =>  [
                        'detailTransaksi'   =>  $detailTransaksi,
                        'detailMitra'       =>  $this->loggedInDetailMitra,
                        'detailPaket'       =>  $detailPaket
                    ]
                ];

                $pdf->fileName  =   'invoice_'.$nomorTransaksi.'_'.date('YmdHis').'.pdf';
                $pdf->setOptions($options);
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView(mitraView('transaksi/invoice'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(mitraView('error'), $data);
            }
        }
        public function pilihanPaket(){
            $paket      =   new Paket();

            $listPaket  =   $paket->getPaket();

            $data   =   [
                'view'      =>  mitraView('transaksi/pilihan-paket'),
                'pageTitle' =>  'Transaksi',
                'pageDesc'  =>  'Pilihan Paket',
                'data'  =>  [
                    'paketModel'    =>  $paket,
                    'listPaket'     =>  $listPaket
                ]
            ];
            echo view(mitraView('index'), $data);
        }
    }
?>