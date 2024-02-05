<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class TransaksiItem extends BaseModel{
        public function getTransaksiItem($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $transaksiItem              =   $this->getData($t->transaksiItem, $id, $options);

            return $transaksiItem;
        }
        public function saveTransaksiItem($id = null, $dataTransaksiItem = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $transaksiItemBuilder   =   $db->table($tabel->transaksiItem);
            if(!empty($id)){
                $transaksiItemBuilder->where('id', $id);
                $saveTransaksiItem  =   $transaksiItemBuilder->update($dataTransaksiItem);
            }else{                
                $saveTransaksiItem  =   $transaksiItemBuilder->insert($dataTransaksiItem);
                $id                 =   $db->insertID();
            }

            return ($saveTransaksiItem)? $id : false;
        }
    }
?>