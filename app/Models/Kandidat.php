<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;
    use App\Models\Skill;
    use App\Models\Experience;
    use App\Models\Education;

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
        public function getSkill($idKandidat){
            $skill  =   new Skill();

            $options    =   [
                'where' =>  [
                    'user_id'   =>  $idKandidat
                ]
            ];
            $listSkill  =   $skill->getSkill(null, $options);
            return $listSkill;
        }
        public function getEducation($idKandidat){
            $education  =   new Education();

            $options    =   [
                'where' =>  [
                    'user_id'   =>  $idKandidat
                ]
            ];
            $listEducation  =   $education->getEducation(null, $options);
            return $listEducation;
        }
        public function getExperience($idKandidat){
            $experience =   new Experience();

            $options    =   [
                'where' =>  [
                    'user_id'   =>  $idKandidat
                ]
            ];
            $listExperience  =   $experience->getExperience(null, $options);
            return $listExperience;
        }
    }
?>