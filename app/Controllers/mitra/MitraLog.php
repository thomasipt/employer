<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\MitraLog as LogModel;
    use App\Models\Mitra;
    
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Psr\Log\LoggerInterface;

    class MitraLog extends BaseController{
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
        public function listLog(){
            $data   =   [
                'view'      =>  mitraView('mitra-log/index'),
                'pageTitle' =>  'Mitra Log',
                'pageDesc'  =>  'History Log Dashboard Mitra'
            ];
            echo view(mitraView('index'), $data);
        }
        public function getListLog(){
            #Data
            $log        =   new LogModel();
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
                'where'             =>  [
                    'createdBy' =>  $this->loggedInIDMitra
                ]
            ];

            if(!empty($search)){
                if(is_array($search)){
                    if(array_key_exists('value', $search)){
                        $searchValue    =   $search['value'];
                        if(!empty($searchValue)){
                            $options['like']    =   [
                                'column'    =>  ['title', 'keterangan'],
                                'value'     =>  $searchValue
                            ];
                        }
                    }
                }
            }

            $listLog    =   $log->getMitraLog(null, $options);
            if(count($listLog) >= 1){
                $mitra  =   new Mitra();

                foreach($listLog as $indexData => $logItem){
                    $nomorUrut  =   $start + $indexData + 1;
                    $createdBy  =   $logItem['createdBy'];

                    $detailMitra    =   $mitra->getMitra($createdBy, ['select' => 'id, nama']);

                    $listLog[$indexData]['nomorUrut']       =   $nomorUrut;
                    $listLog[$indexData]['mitra']           =   $detailMitra;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $log->getMitraLog(null, $recordsTotalOptions);
            $recordsTotal       =   (!empty($getRecordsTotal))? $getRecordsTotal['jumlahData'] : 0;

            $response   =   [
                'listLog'           =>  $listLog,
                'draw'              =>  $draw,
                'recordsFiltered'   =>  $recordsTotal,
                'recordsTotal'      =>  $recordsTotal
            ];
            
            return $this->respond($response);
        }
    }
?>