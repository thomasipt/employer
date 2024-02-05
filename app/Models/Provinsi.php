<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Provinsi extends BaseModel{
        public function getProvinsi($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $provinsi                   =   $this->getData($t->provinsi, $id, $options);

            return $provinsi;
        }
    }
?>