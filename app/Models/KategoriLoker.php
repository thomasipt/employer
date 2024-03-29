<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class KategoriLoker extends BaseModel{
        public $tableId     =   'sektor_id';
        
        public function getKategoriLoker($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $kategoriLoker              =   $this->getData($t->kategori, $id, $options, $this->tableId);

            return $kategoriLoker;
        }
        public function saveKategoriLoker($id = null, $dataKategoriLoker = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $kategoriLokerBuilder   =   $db->table($tabel->kategori);
            if(!empty($id)){
                $kategoriLokerBuilder->where($this->tableId, $id);
                $saveKategoriLoker  =   $kategoriLokerBuilder->update($dataKategoriLoker);
            }else{                
                $saveKategoriLoker  =   $kategoriLokerBuilder->insert($dataKategoriLoker);
                $id                 =   $db->insertID();
            }

            return ($saveKategoriLoker)? $id : false;
        }
        public function deleteKategoriLoker($id){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $kategoriLokerBuilder  =   $db->table($tabel->kategori);

            $kategoriLokerBuilder->where($this->tableId, $id);
            $deleteKategoriLoker   =   $kategoriLokerBuilder->delete();

            return $deleteKategoriLoker;
        }
    }
?>