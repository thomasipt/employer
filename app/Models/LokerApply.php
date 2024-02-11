<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class LokerApply extends BaseModel{      
        public $tableId     =   'id';

        public function getLokerApply($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $lokerApply                 =   $this->getData($t->lokerApply, $id, $options, $this->tableId);

            return $lokerApply;
        }
    }
?>