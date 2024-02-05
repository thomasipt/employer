<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Keranjang extends BaseModel{
        public function getKeranjang($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $keranjang                  =   $this->getData($t->keranjang, $id, $options);

            return $keranjang;
        }
        public function saveKeranjang($id = null, $dataKeranjang = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $keranjangBuilder   =   $db->table($tabel->keranjang);
            if(!empty($id)){
                $keranjangBuilder->where('id', $id);
                $saveKeranjang  =   $keranjangBuilder->update($dataKeranjang);
            }else{                
                $saveKeranjang  =   $keranjangBuilder->insert($dataKeranjang);
                $id                 =   $db->insertID();
            }

            return ($saveKeranjang)? $id : false;
        }
    }
?>