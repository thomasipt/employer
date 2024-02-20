<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Homepage extends BaseModel{
        public $heroId      =   1;
        public $aboutUsId   =   2;
        public $featuresId  =   3;
        
        public function getHomepage($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $homepage                   =   $this->getData($t->homepage, $id, $options);

            return $homepage;
        }
        public function saveHomepage($id = null, $dataHomepage = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $builder    =   $db->table($tabel->homepage);
            if(!empty($id)){
                $builder->where('id', $id);
                $saveHomepage   =   $builder->update($dataHomepage);
            }else{                
                $saveHomepage   =   $builder->insert($dataHomepage);
                $id             =   $db->insertID();
            }

            return ($saveHomepage)? $id : false;
        }
    }
?>