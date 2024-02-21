<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class LokerFree extends BaseModel{
        public $tableId     =   'id';

        public function getLokerFree($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $lokerFree                  =   $this->getData($t->lokerFree, $id, $options, $this->tableId);

            return $lokerFree;
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
    }
?>