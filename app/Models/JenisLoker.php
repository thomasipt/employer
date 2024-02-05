<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class JenisLoker extends BaseModel{
        public function getJenisLoker($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $jenisLoker                 =   $this->getData($t->jenis, $id, $options);

            return $jenisLoker;
        }
        public function saveJenisLoker($id = null, $dataJenisLoker = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $jenisLokerBuilder  =   $db->table($tabel->jenis);
            if(!empty($id)){
                $jenisLokerBuilder->where('id', $id);
                $saveJenisLoker =   $jenisLokerBuilder->update($dataJenisLoker);
            }else{                
                $saveJenisLoker =   $jenisLokerBuilder->insert($dataJenisLoker);
                $id             =   $db->insertID();
            }

            return ($saveJenisLoker)? $id : false;
        }
        public function deleteJenisLoker($id){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $jenisLokerBuilder  =   $db->table($tabel->jenis);

            $jenisLokerBuilder->where('id', $id);
            $deleteJenisLoker   =   $jenisLokerBuilder->delete();

            return $deleteJenisLoker;
        }
    }
?>