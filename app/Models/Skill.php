<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;

    class Skill extends BaseModel{      
        public $tableId     =   'id';

        public function getSkill($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $skill                      =   $this->getData($t->skill, $id, $options, $this->tableId);

            return $skill;
        }
    }
?>