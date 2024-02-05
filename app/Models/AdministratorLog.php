<?php
    namespace App\Models;

    use App\Models\BaseModel;
    use Config\Database;
    use App\Libraries\Tabel;

    class AdministratorLog extends BaseModel{
        public $module;
        public function getAdministratorLog($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $administratorLog           =   $this->getData($t->administratorLog, $id, $options);

            return $administratorLog;
        }
        public function saveAdministratorLog($id = null, $dataLog = null){
            helper('CustomDate');

            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $administratorLogBuilder    =   $db->table($tabel->administratorLog);
            if(!empty($id)){
                $administratorLogBuilder->where('id', $id);
                $saveAdministratorLog   =   $administratorLogBuilder->update($dataLog);
            }else{
                $dataLog['createdAt']   =   rightNow();
                
                $saveAdministratorLog   =   $administratorLogBuilder->insert($dataLog);
                $id                     =   $db->insertID();
            }

            return ($saveAdministratorLog)? $id : false;
        }
        public function saveAdministratorLogFromThisModule($module, $idModule, $title, $keterangan = null){
            $session        =   session();
            $administrator  =   $session->get('administrator');

            $dataAdministratorLog   =   [
                'title'     =>  $title,
                'module'    =>  $module,
                'idModule'  =>  $idModule,
                'createdBy' =>  $administrator['id']
            ];
            if(!empty($keterangan)){
                $dataAdministratorLog['keterangan'] =   $keterangan;
            }

            $saveLog    =   $this->saveAdministratorLog(null, $dataAdministratorLog);
            return $saveLog;
        }
    }
?>