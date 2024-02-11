<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Loker extends BaseModel{
        public function getLoker($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $loker                      =   $this->getData($t->loker, $id, $options);

            return $loker;
        }
        public function saveLoker($id = null, $dataLoker = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $lokerBuilder   =   $db->table($tabel->loker);
            if(!empty($id)){
                $lokerBuilder->where('id', $id);
                $saveLoker  =   $lokerBuilder->update($dataLoker);
            }else{                
                $saveLoker  =   $lokerBuilder->insert($dataLoker);
                $id         =   $db->insertID();
            }

            return ($saveLoker)? $id : false;
        }
        public function getJumlahApply($idLoker){
            $lokerApply     =   new LokerApply();

            $options            =   [
                'singleRow' =>  true,
                'select'    =>  'count('.$lokerApply->tableId.') as jumlahData',
                'where' =>  [
                    'loker' =>  $idLoker
                ]
            ];
            $getJumlahApply     =   $lokerApply->getLokerApply(null, $options);
            $jumlahApply        =   !empty($getJumlahApply)? $getJumlahApply['jumlahData'] : null;

            return $jumlahApply;
        }
        public function deleteLoker($idLoker){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $lokerBuilder  =   $db->table($tabel->loker);

            $lokerBuilder->where('id', $idLoker);
            $deleteLoker   =   $lokerBuilder->delete();

            return $deleteLoker;
        }
    }
?>