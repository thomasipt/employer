<?php
    namespace App\Controllers\mitra;
    
    use App\Controllers\BaseController;
    use CodeIgniter\API\ResponseTrait;

    #Model
    use App\Models\MitraLog as LogModel;
    use App\Models\Mitra;

    class MitraLog extends BaseController{
        use ResponseTrait;

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

            $listLog    =   $log->getMitraLog(null, $options);
            if(count($listLog) >= 1){
                $mitra  =   new Mitra();

                foreach($listLog as $indexData => $logItem){
                    $nomorUrut  =   $start + $indexData + 1;
                    $createdBy  =   $logItem['createdBy'];

                    $detailMitra    =   $mitra->getMitra($createdBy, ['select' => 'id, nama']);

                    $listLog[$indexData]['nomorUrut']       =   $nomorUrut;
                    $listLog[$indexData]['mitra']   =   $detailMitra;
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