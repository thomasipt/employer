<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\Mitra;
    use App\Models\Loker as LokerModel;
    use App\Models\Kandidat;
    use App\Models\LokerApply;
    
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

        private function lokerChecking($idLoker){
            $loker          =   new LokerModel();
            $detailLoker    =   $loker->getLoker($idLoker);

            if(empty($detailLoker)){
                throw new Exception('Loker tidak ditemukan dengan pengenal '.$idLoker.'!');
            }

            return $detailLoker;
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
        public function applier($idLoker){
            $mitra      =   new Mitra();
            $lokerApply =   new LokerApply();
            $kandidat   =   new Kandidat();

            try{
                $detailLoker    =   $this->lokerChecking($idLoker);
                $judulLoker     =   $detailLoker['judul'];
                
                $idMitraLoker   =   $detailLoker['createdBy'];
                $detailMitra    =   $mitra->getMitra($idMitraLoker);

                $listApplierOpptions    =   [
                    'where' =>  [
                        'loker' =>  $idLoker
                    ]
                ];
                $listApplier    =   $lokerApply->getLokerApply(null, $listApplierOpptions);
               
                foreach($listApplier as $index => $applier){
                    $applierKandidat    =   $applier['kandidat'];

                    $kandidatOptions    =   [
                        'select'    =>  'id, foto, nama, tanggalLahir, alamat, email, telepon'
                    ];
                    $detailApplier      =   $kandidat->getKandidat($applierKandidat, $kandidatOptions);
                    $listApplier[$index]['kandidat']    =   $detailApplier;
                }

                $data           =   [
                    'pageTitle' =>  'Pelamar Lowongan Pekerjaan',
                    'pageDesc'  =>  $judulLoker,
                    'view'      =>  mitraView('loker/applier'),
                    'data'      =>  [
                        'detailLoker'   =>  $detailLoker,
                        'detailMitra'   =>  $detailMitra,
                        'listApplier'   =>  $listApplier
                    ]
                ];

                return view(adminView('index'), $data);
            }catch(Exception $e){
                $data   =   [
                    'judul'     =>  'Terjadi Kesalahan',
                    'deskripsi' =>  $e->getMessage()
                ];
                return view(adminView('error'), $data);
            }
        }
    }
?>