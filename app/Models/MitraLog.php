<?php
    namespace App\Models;

    use Config\Database;

    use App\Models\BaseModel;
    
    #Library
    use App\Libraries\Tabel;

    class MitraLog extends BaseModel{
        public $module;
        public function getMitraLog($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $mitraLog                  =   $this->getData($t->mitraLog, $id, $options);

            return $mitraLog;
        }
        public function saveMitraLog($id = null, $dataLog = null){
            helper('CustomDate');

            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $mitraLogBuilder    =   $db->table($tabel->mitraLog);
            if(!empty($id)){
                $mitraLogBuilder->where('id', $id);
                $saveMitraLog   =   $mitraLogBuilder->update($dataLog);
            }else{
                $dataLog['createdAt']   =   rightNow();
                
                $saveMitraLog   =   $mitraLogBuilder->insert($dataLog);
                $id                     =   $db->insertID();
            }

            return ($saveMitraLog)? $id : false;
        }
        public function saveMitraLogFromThisModule($module, $idModule, $title, $keterangan = null){
            $session        =   session();
            $mitra          =   $session->get('mitra');

            $dataMitraLog   =   [
                'title'     =>  $title,
                'module'    =>  $module,
                'idModule'  =>  $idModule,
                'createdBy' =>  $mitra['id']
            ];
            if(!empty($keterangan)){
                $dataMitraLog['keterangan'] =   $keterangan;
            }

            $saveLog    =   $this->saveMitraLog(null, $dataMitraLog);
            return $saveLog;
        }
    }
?>