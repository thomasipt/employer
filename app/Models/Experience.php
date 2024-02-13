<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Experience extends BaseModel{      
        public $tableId     =   'id';

        public function getExperience($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $experience                 =   $this->getData($t->experience, $id, $options, $this->tableId);

            return $experience;
        }
    }
?>