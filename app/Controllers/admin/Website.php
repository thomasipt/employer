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
                $detailSection  =   $this->sectionChecking($section);
                $idSection      =   $detailSection['id'];

                $data   =   [
                    'pageTitle' =>  'Hero',
                    'pageDesc'  =>  'Hero Divison',
                    'view'      =>  adminView('website/landing-page/'.$section),
                    'data'      =>  []
                ];

                $elementOptions =   [
                    'where' =>  [
                        'parent'    =>  $idSection
                    ]
                ];
                $sectionElement    =   $homepageElement->getHomepageElement(null, $elementOptions);
                $sectionElement    =   $homepageElement->convertListELementToKeyValueMap($sectionElement);

                $sectionName    =   '';
                if($idSection == $homepage->heroId){
                    $sectionName    =   'heroElement';
                }
                if($idSection == $homepage->aboutUsId){
                    $sectionName    =   'aboutUsElement';
                }
                if($idSection == $homepage->featuresId){
                    $sectionName    =   'featuresElement';
                }
                if($idSection == $homepage->whatsappId){
                    $sectionName    =   'whatsappElement';
                }
                if($idSection == $homepage->paketId){
                    $sectionName    =   'paketElement';
                }
                if($idSection == $homepage->rekeningPerusahaanId){
                    $sectionName    =   'rekeningPerusahaanElement';
                }
                if($idSection == $homepage->emailPerusahaanId){
                    $sectionName    =   'emailPerusahaanElement';
                }
                
                $data['data']   =   [
                    $sectionName    =>  $sectionElement
                ];

                if($idSection == $homepage->featuresId){                    
                    $listIcons      =   $homepageElement->getRemixicon();
                    $data['data']['listIcons']  =   $listIcons;
                }

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
            $request            =   request();
            $homepage           =   new Homepage();
            $homepageElement    =   new HomepageElement();

            $status     =   false;
            $message    =   'Gagal memproses! Silahkan ulangi!';
            $data       =   null;

            try{
                $detailSection  =   $this->sectionChecking($section);
                $idSection      =   $detailSection['id'];

                $options        =   [
                    'where' =>  [
                        'parent'    =>  $idSection
                    ]
                ];
                $sectionElement    =   $homepageElement->getHomepageElement(null, $options);

                $message    =   'Section tidak memiliki elemen, tidak ada data yang diproses!';
                if(count($sectionElement) >= 1){
                    $database   =   new Database();
                    $tabel      =   new Tabel();

                    $db         =   $database->connect($database->default);
                    $builder    =   $db->table($tabel->homepageElement);
                    
                    foreach($sectionElement as $element){
                        $key    =   $element['key'];

                        $newValue   =   $request->getPost($key);
                        
                        if($idSection == $homepage->featuresId){
                            if($key == '_feature'){
                                $featureIcon            =   $request->getPost('icon');
                                $featureTitle           =   $request->getPost('title');
                                $featureDescription     =   $request->getPost('description');

                                $listIconsFeature   =   [];
                                for($i = 0; $i <= count($featureIcon) - 1; $i++){
                                    $featureIconItem    =   [
                                        'icon'          =>  $featureIcon[$i],
                                        'title'         =>  $featureTitle[$i],
                                        'description'   =>  $featureDescription[$i]
                                    ];
                                    array_push($listIconsFeature, $featureIconItem);
                                }
                                $newValue   =   json_encode($listIconsFeature);
                            }
                        }

                        if(!empty($newValue)){
                            $newData    =   [
                                'value' =>  $newValue
                            ];

                            $builder->where('parent', $idSection);
                            $builder->where('key', $key);
                            $builder->update($newData);
                        }
                    }

                    $status     =   true;
                    $message    =   'Berhasil mengupdate section!';
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
            helper('CustomDate');

            $request    =   request();
            $homepage   =   new Homepage();
            
            $status     =   false;
            $message    =   'Gagal memproses! Silahkan ulangi!';
            $data       =   null;

            try{
                $detailSection  =   $this->sectionChecking($section);
                $idSection      =   $detailSection['id'];
                
                $database   =   new Database();
                $tabel      =   new Tabel();

                $db         =   $database->connect($database->default);
                $builder    =   $db->table($tabel->homepageElement);
                
                $builder->where('parent', $idSection);
                $builder->where('key', '_image');
                $getOldImage    =   $builder->get();
                $oldImage       =   ($getOldImage->getNumRows() >= 1)? $getOldImage->getRowArray() : null;      

                if(!empty($oldImage)){
                    $oldImage       =   $oldImage['value'];
                    if(!empty($oldImage)){
                        $oldImage       =   uploadGambarWebsite('landing-page/'.$oldImage);
                        if(file_exists($oldImage)){
                            unlink($oldImage);
                        }
                    }
                }

                $now        =   date('YmdHis', strtotime(rightNow()));

                $image      =   $request->getFile('_image');
                $extension  =   $image->getClientExtension();

                $imageName  =   '';
                if($idSection == $homepage->heroId){
                    $imageName  =   'HeroImage';
                }
                if($idSection == $homepage->aboutUsId){
                    $imageName  =   'AboutUsImage';
                }
                if($idSection == $homepage->featuresId){
                    $imageName  =   'FeaturesImage';
                }
                
                $fileName       =   $imageName.'-'.$now.'.'.$extension;
                $uploadFile     =   $image->move(uploadGambarWebsite('landing-page'), $fileName);

                $message        =   'Gagal memproses gambar!';
                if($uploadFile){
                    $newData    =   [
                        'value' =>  $fileName
                    ];

                    $builder->where('parent', $idSection);
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