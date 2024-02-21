<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra;
    
    #Library
    use App\Libraries\MitraJWT;
use App\Libraries\Tabel;
use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    use Exception;

    class Loker extends BaseController{
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
                    'view'      =>  ($mitraExist)? mitraView('loker/index') : adminView('loker/mitra'),
                    'pageTitle' =>  'Lowongan Pekerjaan Mitra',
                    'pageDesc'  =>  ($mitraExist)? $detailMitra['nama'] : 'List Lowongan Pekerjaan Mitra'
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