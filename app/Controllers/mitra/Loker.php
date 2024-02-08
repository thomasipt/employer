<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Loker as LokerModel;
    use App\Models\Mitra;
    use App\Models\KategoriLoker;
    use App\Models\JenisLoker;
    use App\Models\Provinsi;
    use App\Models\Paket;
    use App\Models\MitraLog;
    
    #Library
    use App\Libraries\APIRespondFormat;
    use App\Libraries\FormValidation;
    use App\Libraries\Tabel;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Loker extends BaseController{
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

        private function lokerChecking($idLoker){
            $loker          =   new LokerModel();
            $detailLoker    =   $loker->getLoker($idLoker);
            if(empty($detailLoker)){
                throw new Exception('Tidak ditemukan loker dengan pengenal '.$idLoker.'!');
            }

            return $detailLoker;
        }
        public function listLoker(){
            $data   =   [
                'view'      =>  mitraView('loker/index'),
                'pageTitle' =>  'Loker',
                'pageDesc'  =>  'List Loker'
            ];
            echo view(mitraView('index'), $data);
        }
        public function getListLoker(){
            #Data
            $loker      =   new LokerModel();
            $request    =   $this->request;
            
            $draw       =   $request->getGet('draw');

            $start      =   $request->getGet('start');
            $start      =   (!is_null($start))? $start : 0;
    
            $length     =   $request->getGet('length');
            $length     =   (!is_null($length))? $length : 10;
            
            $search     =   $request->getGet('search');

            $options    =   [
                'select'            =>  'id, judul, deskripsi, namaPIC, emailPIC, batasAwalPendaftaran, batasAkhirPendaftaran, createdBy, createdAt, expiredAt',
                'limit'             =>  $length,
                'limitStartFrom'    =>  $start,
                'where'     =>  [
                    'createdBy' =>  $this->loggedInIDMitra
                ]
            ];

            if(!empty($search)){
                if(is_array($search)){
                    if(array_key_exists('value', $search)){
                        $searchValue    =   $search['value'];
                        if(!empty($searchValue)){
                            $options['like']    =   [
                                'column'    =>  ['judul', 'deskripsi', 'keterangan', 'namaPIC', 'emailPIC'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }
            

            $listLoker    =   $loker->getLoker(null, $options);
            if(count($listLoker) >= 1){
                $mitra  =   new Mitra();

                foreach($listLoker as $indexData => $lokerItem){
                    $nomorUrut  =   $start + $indexData + 1;
                    $createdBy  =   $lokerItem['createdBy'];

                    $detailMitra    =   $mitra->getMitra($createdBy, ['select' => 'id, nama']);

                    $listLoker[$indexData]['nomorUrut']     =   $nomorUrut;
                    $listLoker[$indexData]['mitra']         =   $detailMitra;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $loker->getLoker(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listLoker'         =>  $listLoker,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
        public function addLoker($idLoker = null){
            try{
                $doesUpdate     =   !empty($idLoker);
                $detailLoker    =   ($doesUpdate)? $this->lokerChecking($idLoker) : null;

                $mitra          =   new Mitra();
                $provinsi       =   new Provinsi();
                $kategori       =   new KategoriLoker();
                $jenis          =   new JenisLoker();

                $detailMitra    =   $mitra->getMitra($this->loggedInIDMitra);
                $listProvinsi   =   $provinsi->getProvinsi(null, ['select' => 'id, nama']);
                $listKategori   =   $kategori->getKategoriLoker(null, ['select' => 'id, nama']);
                $listJenis      =   $jenis->getJenisLoker(null, ['select' => 'id, nama']);

                $data   =   [
                    'pageTitle' =>  ($doesUpdate)? 'Update Loker' : 'Loker Baru',
                    'pageDesc'  =>  ($doesUpdate)? $detailLoker['judul'] : null,
                    'view'      =>  mitraView('loker/add'),
                    'data'      =>  [
                        'detailLoker'   =>  $detailLoker,
                        'detailMitra'   =>  $detailMitra,
                        'listProvinsi'  =>  $listProvinsi,
                        'listKategori'  =>  $listKategori,
                        'listJenis'     =>  $listJenis
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
        public function saveLoker($idLoker = null){
            $status     =   false;
            $message    =   'Gagal memproses loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $request        =   request();
                $formValidation =   new FormValidation();
                $mitra          =   new Mitra();
                $paket          =   new Paket();
                $loker          =   new LokerModel();

                $doesUpdate     =   !empty($idLoker);
                $detailLoker    =   ($doesUpdate)? $this->lokerChecking($idLoker) : null;

                $validationRules    =   [
                    'judul'                     =>  'required',
                    'kategori'                  =>  'required',
                    'deskripsi'                 =>  'required',
                    'kualifikasi'               =>  'required',
                    'namaPIC'                   =>  'required',
                    'emailPIC'                  =>  'required|valid_email',
                    'batasAwalPendaftaran'      =>  'required|valid_date[Y-m-d]',
                    'batasAkhirPendaftaran'     =>  'required|valid_date[Y-m-d]',
                    'provinsi'                  =>  'required',
                    'kota'                      =>  'required',
                    'gajiMinimum'               =>  'required',
                    'gajiMaximum'               =>  'required',
                    'jenis'                     =>  'required',
                    'benefit'                   =>  'required'
                ];
    
                $validationMessage  =   $formValidation->generateCustomMessageForSingleRule($validationRules);
    
                if($this->validate($validationRules, $validationMessage)){
                    $judul                  =   $request->getPost('judul');
                    $kategori               =   $request->getPost('kategori');
                    $deskripsi              =   $request->getPost('deskripsi');
                    $kualifikasi            =   $request->getPost('kualifikasi');
                    $namaPIC                =   $request->getPost('namaPIC');
                    $emailPIC               =   $request->getPost('emailPIC');
                    $batasAwalPendaftaran   =   $request->getPost('batasAwalPendaftaran');
                    $batasAkhirPendaftaran  =   $request->getPost('batasAkhirPendaftaran');
                    $provinsi               =   $request->getPost('provinsi');
                    $kota                   =   $request->getPost('kota');
                    $gajiMinimum            =   $request->getPost('gajiMinimum');
                    $gajiMaximum            =   $request->getPost('gajiMaximum');
                    $jenis                  =   $request->getPost('jenis');
                    $benefit                =   $request->getPost('benefit');
                    $keterangan             =   $request->getPost('keterangan');

                    $currentActivePaket     =   $mitra->getPaketAktif($this->loggedInIDMitra);
                    $detailPaket            =   $paket->getPaket($currentActivePaket, ['select' => 'durasi']);
                    $durasiPaket            =   $detailPaket['durasi'];
                    $expiredAt              =   date('Y-m-d H:i:s', strtotime(rightNow().' + '.$durasiPaket.' days'));

                    $dataLoker  =   [
                        'judul'                 =>  $judul,
                        'deskripsi'             =>  $deskripsi,
                        'kategori'              =>  $kategori,
                        'jenis'                 =>  $jenis,
                        'namaPIC'               =>  $namaPIC,
                        'emailPIC'              =>  $emailPIC,
                        'batasAwalPendaftaran'  =>  $batasAwalPendaftaran,
                        'batasAkhirPendaftaran' =>  $batasAkhirPendaftaran,
                        'kualifikasi'           =>  $kualifikasi,
                        'provinsi'              =>  $provinsi,
                        'kota'                  =>  $kota,
                        'keterangan'            =>  $keterangan,
                        'gajiMinimum'           =>  $gajiMinimum,
                        'gajiMaximum'           =>  $gajiMaximum,
                        'benefit'               =>  $benefit,
                        'paket'                 =>  $currentActivePaket,
                        'createdBy'             =>  $this->loggedInIDMitra,
                        'expiredAt'             =>  $expiredAt
                    ];

                    $saveLoker  =   $loker->saveLoker($idLoker, $dataLoker);

                    $message    =   (!$doesUpdate)? 'Gagal menambahkan loker baru!' : 'Gagal mengupdate loker!';
                    if($saveLoker){
                        $idLoker    =   $saveLoker;
                        
                        $status     =   true;
                        $message    =   (!$doesUpdate)? 'Berhasil menambahkan loker baru!' : 'Berhasil mengupdate loker!';
                        $data       =   ['id' => $idLoker];

                        $mitraLog   =   new MitraLog();
                        $tabel      =   new Tabel();

                        $title      =   (!$doesUpdate)? 'Menambahkan lowongan pekerjaan baru!' : 'Mengupdate data lowongan pekerjaan!';
                        $mitraLog->saveMitraLogFromThisModule($tabel->loker, $idLoker, $title);
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
        public function deleteLoker($idLoker){
            $status     =   false;
            $message    =   'Gagal menghapus loker! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                $this->lokerChecking($idLoker);

                $loker          =   new LokerModel();
                
                //TODO Seharusnya ada pengecekan terlebih dahulu, apakah loker sudah ada yang apply atau belum. Jika sudah maka tolak action hapus, jika belum ada yang apply lanjutkan action hapus
                $jumlahApplier  =   $loker->getJumlahApply($idLoker);

                $message    =   'Maaf, lowongan pekerjaan ini sudah diapply oleh beberapa orang. Lowongan pekerjaan tidak bisa dihapus!';
                if($jumlahApplier <= 0){
                    $deleteLoker    =   $loker->deleteLoker($idLoker);

                    $message    =   'Gagal menghapus lowongan pekerjaan, silahkan ulangi lagi!';
                    if($deleteLoker){
                        $status     =   true;
                        $message    =   'Berhasil menghapus lowongan pekerjaan!';
                        $data       =   [
                            'id'    =>  $idLoker
                        ];
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
    }
?>