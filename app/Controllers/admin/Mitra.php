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
    use App\Libraries\MitraJWT;

    use App\Models\Loker;
    use App\Models\Paket;
    use App\Models\Transaksi;

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
        public function detail($idMitra){
            $loker      =   new Loker();
            $mitra      =   new MitraModel();
            $paket      =   new Paket();
            $transaksi  =   new Transaksi();

            try{
                $detailMitra    =   $this->mitraChecking($idMitra);

                $idMitra    =   $detailMitra['id'];
                $namaMitra  =   $detailMitra['nama'];

                $mitraJWT       =   new MitraJWT();

                $mitraPayload   =   [
                    'iat'       =>  time(),
                    'iss'       =>  base_url(),
                    'mitra'     =>  [
                        'id'    =>  $idMitra
                    ]
                ];
                $mitraToken     =   $mitraJWT->encode($mitraPayload);

                $options    =   [
                    'singleRow' =>  true,
                    'select'    =>  'count(id) as jumlahData'
                ];

                $jumlahLokerOptions             =   $options;
                $jumlahLokerOptions['where']    =   [
                    'createdBy' =>  $idMitra
                ];
                $getJumlahLoker         =   $loker->getLoker(null, $jumlahLokerOptions);
                $jumlahLoker            =   !empty($getJumlahLoker)? $getJumlahLoker['jumlahData'] : 0;

                try{
                    $transaksiAktif         =   $mitra->getPaketAktif($idMitra, true);
                    if(!empty($transaksiAktif)){
                        $paketTransaksiAktif    =   $transaksiAktif['paket'];
                        $detailPaket            =   $paket->getPaket($paketTransaksiAktif, ['select' => 'nama']);
                        if(!empty($detailPaket)){
                            $paketAktif =   $detailPaket['nama'];
                        }
                    }
                }catch(Exception $e){
                    $paketAktif =   null;
                }

                $transaksiOptions   =   $options;
                $transaksiOptions['where']  =   [
                    'mitra'         =>  $idMitra,
                    'approvement'   =>  $transaksi->approvement_approved
                ];
                $getJumlahTransaksiBerhasil         =   $transaksi->getTransaksi(null, $transaksiOptions);

                $transaksiOptions['where']  =   [
                    'mitra'         =>  $idMitra,
                    'approvement'   =>  $transaksi->approvement_rejected
                ];
                $getJumlahTransaksiGagal            =   $transaksi->getTransaksi(null, $transaksiOptions);

                $jumlahTransaksiBerhasil            =   !empty($getJumlahTransaksiBerhasil)? $getJumlahTransaksiBerhasil['jumlahData'] : 0;
                $jumlahTransaksiGagal               =   !empty($getJumlahTransaksiGagal)? $getJumlahTransaksiGagal['jumlahData'] : 0;

                $data   =   [
                    'pageTitle' =>  'Detail Mitra',
                    'pageDesc'  =>  $namaMitra,
                    'view'      =>  adminView('mitra/detail'),
                    'data'      =>  [
                        'mitraToken'    =>  $mitraToken,
                        'jumlahTransaksiBerhasil'   =>  $jumlahTransaksiBerhasil,
                        'jumlahTransaksiGagal'      =>  $jumlahTransaksiGagal,
                        'jumlahLoker'               =>  $jumlahLoker,
                        'paketAktif'                =>  $paketAktif
                    ]
                ];

                return view(adminView('index'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Tejadi Kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(adminView('error'), $data);
            }
        }
    }
?>
