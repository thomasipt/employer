<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;

    #Libraries
    use App\Libraries\Tabel;


    class Company extends BaseModel{
        public function getCompany($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $company                    =   $this->getData($t->company, $id, $options);

            return $company;
        }
    }
?>