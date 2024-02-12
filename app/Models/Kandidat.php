<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Kandidat extends BaseModel{      
        public $tableId     =   'id';

        public function getKandidat($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $kandidat                   =   $this->getData($t->kandidat, $id, $options, $this->tableId);

            return $kandidat;
        }
    }
?>