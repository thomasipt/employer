<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Education extends BaseModel{      
        public $tableId     =   'id';

        public function getEducation($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $education                  =   $this->getData($t->education, $id, $options, $this->tableId);

            return $education;
        }
    }
?>