<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class HomepageElement extends BaseModel{
        public function getHomepageElement($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $homepageElement            =   $this->getData($t->homepageElement, $id, $options);

            return $homepageElement;
        }
        public function saveHomepageElement($id = null, $dataHomepageElement = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $builder    =   $db->table($tabel->homepageElement);
            if(!empty($id)){
                $builder->where('id', $id);
                $saveHomepageElement   =   $builder->update($dataHomepageElement);
            }else{                
                $saveHomepageElement   =   $builder->insert($dataHomepageElement);
                $id             =   $db->insertID();
            }

            return ($saveHomepageElement)? $id : false;
        }
        public function convertListELementToKeyValueMap($listElement){
            $keyValueMap    =   [];
            foreach($listElement as $element){
                $key    =   $element['key'];
                $value  =   $element['value'];
                
                $keyValueMap[$key]  =   $value;
            }

            return $keyValueMap;
        }
    }
?>