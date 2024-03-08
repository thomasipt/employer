<?php
    namespace App\Controllers\mitra;

    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra;
    use App\Models\Transaksi as TransaksiModel;
    use App\Models\Paket;
    use App\Models\MitraLog;
    use App\Models\Homepage;
    use App\Models\HomepageElement;

    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\EmailSender;
    use App\Libraries\PDF;
    use App\Libraries\Tabel;
    
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Config\Database;
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

                try{
                    $transaksiAktif     =   $mitra->getPaketAktif($loggedInIDMitra, true);
                }catch(Exception $e){
                    $transaksiAktif     =   null;
                }

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

                $emailSender        =   new EmailSender();
                $paket              =   new Paket();
                $tabel              =   new Tabel();

                $detailPaket    =   $this->paketChecking($paketCode);

                $currentDate    =   currentDate();

                $nomorTransaksi =   strtoupper(uniqid($paketCode));
                $idPaket        =   $detailPaket['id'];
                $codePaket      =   $detailPaket['code'];
                $namaPaket      =   $detailPaket['nama'];
                $durasiPaket    =   $detailPaket['durasi'];
                $hargaPaket     =   $detailPaket['harga'];

                $persentasePPN  =   $paket->persentasePPN;
                $ppnPaket       =   (!empty($persentasePPN))? $persentasePPN / 100 * $hargaPaket : 0;

                $dataTransaksi  =   [
                    'nomor' =>  $nomorTransaksi,
                    'mitra' =>  $this->loggedInIDMitra,
                    'paket' =>  $idPaket,
                    'harga' =>  $hargaPaket,
                    'ppn'   =>  $ppnPaket
                ];

                $saveTransaksi  =   $transaksi->saveTransaksi(null, $dataTransaksi);

                $message    =   'Gagal checkout! Silahkan ulangi lagi!';
                if($saveTransaksi){
                    $mitraLog           =   new MitraLog();

                    $idTransaksiBaru    =   $saveTransaksi;

                    $detailMitra    =   $this->loggedInDetailMitra;
                    $emailMitra     =   $detailMitra['email'];
                    $namaMitra      =   $detailMitra['nama'];

                    $linkUploadBuktiBayar   =   site_url(mitraController('transaksi'));

                    $htmlBody   =   '<div style="width: 100%; border: 1px solid #0D6EFD; border-radius: 10px; padding: 15px;">
                                      <img src="https://employer.kubu.id/assets/img/icon.png" style="float: left; width: 50px;" alt="Employer">
                                      <p><span style="font-size: 30px; color: rgb(44, 130, 201);">Kubu Employer</span></p>
                                        <br />
                                        <p>Pembelian paket <b>'.$namaPaket.'</b> dengan durasi <b>'.number_format($durasiPaket).' hari</b> berhasil!</p>
                                        <p>Selanjutnya anda harus melakukan pembayaran dan mengupload bukti pembayaran anda pada halaman <a href="'.$linkUploadBuktiBayar.'">ini</a> dan menunggu admin untuk memvalidasi pembayaran anda</p>
                                    </div>';

                    $emailParams    =   [
                        'subject'   =>  'Pembelian Paket',
                        'body'      =>  $htmlBody,
                        'receivers' =>  [
                            ['email' => $emailMitra, 'name' => $namaMitra]
                        ]
                    ];
                    $sendEmail          =   $emailSender->sendEmail($emailParams);
                    $statusKirimEmail   =   $sendEmail['statusSend'];
                    if(!$statusKirimEmail){
                        $db->transRollback();
                    }

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
            $database       =   new Database();
            $db             =   $database->connect($database->default);

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

                $idTransaksi            =   $detailTransaksi['id'];
                $mitraTransaksi         =   $detailTransaksi['mitra'];
                $loggedInIDMitra        =   $this->loggedInIDMitra;
                $loggedInDetailMitra    =   $this->loggedInDetailMitra;

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
                        $namaMitra      =   $loggedInDetailMitra['nama'];
                        $emailSender    =   new EmailSender();

                        $homepage           =   new Homepage();
                        $homepageElement    =   new HomepageElement();

                        $homepageElementOptions     =   [
                            'where' =>  ['parent' => $homepage->emailPerusahaanId]
                        ];
                        $emailPerusahaanElements    =   $homepageElement->getHomepageElement(null, $homepageElementOptions);
                        $emailPerusahaanElements    =   $homepageElement->convertListELementToKeyValueMap($emailPerusahaanElements);
                        
                        $emailPerusahaan    =   $emailPerusahaanElements['email'];

                        $htmlBody   =   '<div style="width: 100%; border: 1px solid #0D6EFD; border-radius: 10px; padding: 15px;">
                                            <img src="https://employer.kubu.id/assets/img/icon.png" style="float: left; width: 50px;" alt="Employer">
                                            <p><span style="font-size: 30px; color: rgb(44, 130, 201);">Kubu Employer</span></p>
                                            <br />
                                            <p>
                                                Mitra <b>'.$namaMitra.'</b> telah melakukan pembayaran transaksi <b>'.$nomorTransaksi.'</b>. 
                                                Bukti bayar bisa dilihat pada halaman berikut <a href="'.site_url(uploadGambarBuktiBayar($fileName)).'" target="_blank">ini</a>
                                            </p>
                                        </div>';
                        $emailParams    =   [
                            'subject'   =>  'Bukti Pembayaran Transaksi '.$nomorTransaksi,
                            'body'      =>  $htmlBody,
                            'receivers' =>  [
                                ['email' => $emailPerusahaan]
                            ]
                        ];
                        $sendEmail      =   $emailSender->sendEmail($emailParams);

                        $message    =   $sendEmail['message'];
                        if($sendEmail['statusSend']){
                            $status     =   true;
                            $message    =   'Berhasil upload bukti bayar!';
                            $data       =   null;

                            $mitraLog   =   new MitraLog();
                            $mitraLog->saveMitraLogFromThisModule($tabel->transaksi, $idTransaksi, 'Upload bukti bayar transaksi '.$nomorTransaksi);
                        }
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
        public function invoice($idTransaksi){
            try{
                helper('CustomDate');

                $transaksi          =   new TransaksiModel();
                $mitra              =   new Mitra();

                $detailTransaksi    =   $transaksi->getTransaksi($idTransaksi);
                if(empty($detailTransaksi)){
                    throw new Exception('Tidak ditemukan transaksi dengan pengenal '.$idTransaksi.'!');
                }

                $mitraTransaksi     =   $detailTransaksi['mitra'];
                $nomorTransaksi     =   $detailTransaksi['nomor'];
                $paketTransaksi     =   $detailTransaksi['paket'];

                $loggedInIDMitra    =   $this->loggedInIDMitra;
                if(!empty($loggedInIDMitra)){
                    if($mitraTransaksi != $loggedInIDMitra){
                        throw new Exception('Maaf, transaksi '.$nomorTransaksi.' bukan transaksi anda!');
                    }
                }

                $detailMitra    =   $mitra->getMitra($mitraTransaksi);

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
                        'detailMitra'       =>  $detailMitra,
                        'detailPaket'       =>  $detailPaket
                    ]
                ];

                $fileName   =   'invoice_'.$nomorTransaksi.'_'.date('YmdHis').'.pdf';

                $pdf->fileName  =   $fileName;
                $pdf->setOptions($options);
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadHtml(view(mitraView('transaksi/invoice'), $data));
                $pdf->render();
                $pdf->stream($fileName, ['Attachment' => false]);
                exit();
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

            $paketOptions   =   [
                'order_by'  =>  [
                    'column'        =>  'id',
                    'orientation'   =>  'asc'
                ]
            ];
            $listPaket  =   $paket->getPaket(null, $paketOptions);

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
        public function pending(){
            $loggedInIDMitra    =   $this->loggedInIDMitra;

            $transaksi  =   new TransaksiModel();
            $paket      =   new Paket();

            $options        =   [
                'where' =>  [
                    'approvement'   =>  $transaksi->approvement_pending,
                    'mitra'         =>  $loggedInIDMitra
                ]
            ];
            $listTransaksiPending  =   $transaksi->getTransaksi(null, $options);

            foreach($listTransaksiPending as $index => $transaksiPending){
                $paketTransaksi =   $transaksiPending['paket'];

                $detailPaket    =   $paket->getPaket($paketTransaksi, ['select' => 'id, nama, durasi, keterangan']);
                $listTransaksiPending[$index]['paket']   =   $detailPaket;
            }

            $data   =   [
                'pageTitle' =>  'Transaksi Pending',
                'pageDesc'  =>  'Transaksi yang belum terselesaikan',
                'view'  =>  mitraView('transaksi/pending'),
                'data'  =>  [
                    'listTransaksiPending'  =>  $listTransaksiPending
                ]
            ];
            return view(mitraView('index'), $data);
        }
    }
?>
