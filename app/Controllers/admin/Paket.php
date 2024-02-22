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
    use App\Libraries\FileUpload;

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
        public function add($idPaket = null){
            try{
                $doesUpdate         =   !empty($idPaket);
                $detailPaket        =   ($doesUpdate)? $this->paketChecking($idPaket) : null;

                $data   =   [
                    'pageTitle' =>  ($doesUpdate)? 'Update Paket' : 'Paket Baru',
                    'pageDesc'  =>  ($doesUpdate)? $detailPaket['nama'] : null,
                    'view'      =>  adminView('paket/add'),
                    'data'      =>  [
                        'detailPaket'   =>  $detailPaket
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
        public function savePaket($idPaket = null){
            $status     =   false;
            $message    =   'Gagal memproses paket! Silahkan ulangi lagi!';
            $data       =   null;

            try{
                helper('CustomDate');
                
                $request            =   request();
                $formValidation     =   new FormValidation();
                $paketModel         =   new PaketModel();

                $doesUpdate         =   !empty($idPaket);
                $detailPaket        =   ($doesUpdate)? $this->paketChecking($idPaket) : null;

                $fileFotoPaket          =   $request->getFile('foto');
                $isFileAttached         =   !empty($fileFotoPaket);

                $validationRules    =   [
                    'nama'      =>  'required',
                    'durasi'    =>  'required|numeric',
                    'harga'     =>  'required|numeric'
                ];
    
                $validationMessage  =   $formValidation->generateCustomMessageForSingleRule($validationRules);
    
                if($this->validate($validationRules, $validationMessage)){
                    $nama       =   $request->getPost('nama');
                    $durasi     =   $request->getPost('durasi');
                    $harga      =   $request->getPost('harga');
                    $keterangan =   $request->getPost('keterangan');

                    $dataPaket  =   [
                        'nama'      =>  $nama,
                        'durasi'    =>  $durasi,
                        'harga'     =>  $harga
                    ];

                    if(!empty($keterangan)){
                        $dataPaket['keterangan']    =   $keterangan;
                    }

                    $savePaket  =   $paketModel->savePaket($idPaket, $dataPaket);

                    $message            =   (!$doesUpdate)? 'Gagal menambahkan paket baru!' : 'Gagal mengupdate paket!';
                    if($savePaket){
                        $idPaket    =   $savePaket;
                        
                        if($isFileAttached){
                            $fileUpload     =   new FileUpload();

                            $fileExtension  =   $fileFotoPaket->getExtension();
                            $fileName       =   'Paket-'.$idPaket.'-'.date('YmdHis').'.'.$fileExtension;

                            $uploadOriginalFile     =   $fileFotoPaket->move(uploadGambarPaket(), $fileName);
                            $fileUpload->resizeImage(uploadGambarPaket($fileName), uploadGambarPaket('compress/'.$fileName));
                            if($uploadOriginalFile){
                                $dataFotoPaket  =   [
                                    'foto'  =>  $fileName
                                ];
                                $savePaket  =   $paketModel->savePaket($idPaket, $dataFotoPaket);
                                if($savePaket){
                                    if($doesUpdate){
                                        $oldFoto        =   $detailPaket['foto'];
                                        if($oldFoto != $paketModel->fotoDefault){
                                            $oldFotoPaket           =   uploadGambarPaket($oldFoto);
                                            $oldFotoCompressPaket   =   uploadGambarPaket('compress/'.$oldFoto);
                                            
                                            if(file_exists($oldFotoPaket)){
                                                unlink($oldFotoPaket);
                                            }
                                            if(file_exists($oldFotoCompressPaket)){
                                                unlink($oldFotoCompressPaket);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $status     =   true;
                        $message    =   (!$doesUpdate)? 'Berhasil menambahkan paket baru!' : 'Berhasil mengupdate paket!';
                        $data       =   ['id' => $idPaket];

                        $tabel      =   new Tabel();
                        $logModel   =   new LogModel();

                        $title      =   (!$doesUpdate)? 'Menambahkan paket baru!' : 'Mengupdate data paket!';
                        $logModel->saveAdministratorLogFromThisModule($tabel->paket, $idPaket, $title);
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
    }
?>