<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;

    #Libraries
    use App\Libraries\APIRespondFormat;
    use App\Libraries\Tabel;

    #Model
    use App\Models\Homepage;
    use App\Models\HomepageElement;
    
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Config\Database;
    use Psr\Log\LoggerInterface;
    use CodeIgniter\API\ResponseTrait;

    use Exception;

    class Website extends BaseController{
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
        private function sectionChecking($section){
            $homepage           =   new Homepage();

            $options            =   [
                'singleRow' =>  true,
                'where'     =>  [
                    'name'  =>  $section
                ]
            ];
            $detailSection      =   $homepage->getHomepage(null, $options);
            if(empty($detailSection)){
                throw new Exception('Tidak ada section homepage dengan pengenal '.$section.'!');
            }

            return $detailSection;   
        }
        public function landingPage($section){
            $homepage           =   new Homepage();
            $homepageElement    =   new HomepageElement();

            try{
                $this->sectionChecking($section);

                $options        =   [
                    'where' =>  [
                        'parent'    =>  $homepage->heroId
                    ]
                ];
                $heroElement    =   $homepageElement->getHomepageElement(null, $options);
                $heroElement    =   $homepageElement->convertListELementToKeyValueMap($heroElement);

                $data   =   [
                    'pageTitle' =>  'Hero',
                    'pageDesc'  =>  'Hero Divison',
                    'view'      =>  adminView('website/landing-page/'.$section),
                    'data'      =>  [
                        'heroElement'  =>  $heroElement
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
        public function saveLandingPage($section){
            $homepage           =   new Homepage();
            $homepageElement    =   new HomepageElement();

            $status     =   false;
            $message    =   'Gagal memproses! Silahkan ulangi!';
            $data       =   null;

            try{
                $this->sectionChecking($section);

                $options        =   [
                    'where' =>  [
                        'parent'    =>  $homepage->heroId
                    ]
                ];
                $heroElement    =   $homepageElement->getHomepageElement(null, $options);

                $message    =   'Hero tidak memiliki elemen, tidak ada data yang diproses!';
                if(count($heroElement) >= 1){
                    $database   =   new Database();
                    $tabel      =   new Tabel();

                    $db         =   $database->connect($database->default);
                    $builder    =   $db->table($tabel->homepageElement);
                    
                    foreach($heroElement as $element){
                        $key    =   $element['key'];

                        if(!empty($newValue)){
                            $newData    =   [
                                'value' =>  $newValue
                            ];

                            $builder->where('parent', $homepage->heroId);
                            $builder->where('key', $key);
                            $builder->update($newData);
                        }
                    }

                    $status     =   true;
                    $message    =   'Berhasil mengupdate Hero!';
                }
            }catch(Exception $e){
                $message    =   $e->getMessage();
                $data       =   null;
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
        public function saveLandingPageImage($section){
            $request            =   request();
            $homepage           =   new Homepage();
            
            $status     =   false;
            $message    =   'Gagal memproses! Silahkan ulangi!';
            $data       =   null;

            try{
                helper('CustomDate');

                $this->sectionChecking($section);
                
                $database   =   new Database();
                $tabel      =   new Tabel();

                $db         =   $database->connect($database->default);
                $builder    =   $db->table($tabel->homepageElement);
                
                $builder->where('parent', $homepage->heroId);
                $builder->where('key', '_image');
                $getHeroImage   =   $builder->get();
                $oldHeroImage   =   ($getHeroImage->getNumRows() >= 1)? $getHeroImage->getRowArray() : null;      

                if(!empty($oldHeroImage)){
                    $oldImage       =   $oldHeroImage['value'];
                    $oldImage       =   uploadGambarWebsite('landing-page/'.$oldImage);
                    if(file_exists($oldImage)){
                        unlink($oldImage);
                    }
                }

                $now        =   date('YmdHis', strtotime(rightNow()));

                $heroImage  =   $request->getFile('_image');
                $extension  =   $heroImage->getClientExtension();
                
                $fileName       =   'HeroImage-'.$now.'.'.$extension;
                $uploadFile     =    $heroImage->move(uploadGambarWebsite('landing-page'), $fileName);

                $message        =   'Gagal memproses gambar!';
                if($uploadFile){
                    $newData    =   [
                        'value' =>  $fileName
                    ];

                    $builder->where('parent', $homepage->heroId);
                    $builder->where('key', '_image');
                    $updateData     =   $builder->update($newData);

                    $message    =   'Gagal memproses database, gambar berhasil diupload!';
                    if($updateData){
                        $status     =   true;
                        $message    =   'Berhasil memperoses gambar!';
                        $data       =   null;
                    }
                }
            }catch(Exception $e){
                $message    =   $e->getMessage();
                $data       =   null;
            }

            $arf        =   new APIRespondFormat($status, $message, $data);
            $respond    =   $arf->getRespond();

            return $this->respond($respond);
        }
    }
?>