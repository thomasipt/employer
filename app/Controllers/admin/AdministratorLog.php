<?php
    namespace App\Controllers\admin;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\AdministratorLog as LogModel;
    use App\Models\Administrator;

    class AdministratorLog extends BaseController{
        use ResponseTrait;

        public function listLog(){
            $data   =   [
                'view'      =>  adminView('administrator-log/index'),
                'pageTitle' =>  'Administrator Log',
                'pageDesc'  =>  'History Log Dashboard Administrator'
            ];
            echo view(adminView('index'), $data);
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
                'limitStartFrom'    =>  $start
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

            $listLog    =   $log->getAdministratorLog(null, $options);
            if(count($listLog) >= 1){
                $administrator  =   new Administrator();

                foreach($listLog as $indexData => $logItem){
                    $nomorUrut  =   $start + $indexData + 1;
                    $createdBy  =   $logItem['createdBy'];

                    $detailAdministrator    =   $administrator->getAdministrator($createdBy, ['select' => 'id, nama']);

                    $listLog[$indexData]['nomorUrut']       =   $nomorUrut;
                    $listLog[$indexData]['administrator']   =   $detailAdministrator;
                }
            }

            #Record Total
            $recordsTotalOptions    =   $options;
            $recordsTotalOptions['singleRow']   =   true;
            $recordsTotalOptions['select']      =   'count(id) as jumlahData';
            unset($recordsTotalOptions['limit']);
            unset($recordsTotalOptions['limitStartFrom']);

            $getRecordsTotal    =   $log->getAdministratorLog(null, $recordsTotalOptions);
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